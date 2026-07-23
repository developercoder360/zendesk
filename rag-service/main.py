import os
import re
import json
import logging
from typing import List, Optional
from fastapi import FastAPI, HTTPException, status
from pydantic import BaseModel, Field
import psycopg2
from psycopg2.extras import RealDictCursor
from sentence_transformers import SentenceTransformer

from langchain_core.prompts import ChatPromptTemplate
from langchain_core.output_parsers import StrOutputParser

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger("rag-service")

app = FastAPI(title="Zendesk Multi-Tenant RAG Service with LangChain LLM Generation")

# Load dense embedding model (384 dimensions)
embedding_model = None

def get_embedding_model():
    global embedding_model
    if embedding_model is None:
        logger.info("Loading SentenceTransformer embedding model 'all-MiniLM-L6-v2'...")
        embedding_model = SentenceTransformer('all-MiniLM-L6-v2')
        logger.info("SentenceTransformer model loaded successfully.")
    return embedding_model

def get_db_connection():
    host = os.getenv("DB_HOST", "pgsql")
    port = int(os.getenv("DB_PORT", "5432"))
    database = os.getenv("DB_DATABASE", "zendesk")
    user = os.getenv("DB_USERNAME", "postgres")
    password = os.getenv("DB_PASSWORD", "secret")
    
    return psycopg2.connect(
        host=host,
        port=port,
        dbname=database,
        user=user,
        password=password
    )

def init_vector_db():
    try:
        conn = get_db_connection()
        cur = conn.cursor()
        cur.execute("CREATE EXTENSION IF NOT EXISTS vector;")
        cur.execute("""
            CREATE TABLE IF NOT EXISTS tenant_vector_store (
                id SERIAL PRIMARY KEY,
                tenant_id VARCHAR(255) NOT NULL,
                document_id VARCHAR(255) NOT NULL,
                content TEXT NOT NULL,
                metadata JSONB DEFAULT '{}'::jsonb,
                embedding vector(384),
                created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
                UNIQUE(tenant_id, document_id)
            );
        """)
        cur.execute("ALTER TABLE tenant_vector_store ADD COLUMN IF NOT EXISTS embedding vector(384);")
        cur.execute("CREATE INDEX IF NOT EXISTS idx_vector_tenant ON tenant_vector_store(tenant_id);")
        conn.commit()
        cur.close()
        conn.close()
        logger.info("pgvector database initialized successfully with vector(384) column.")
    except Exception as e:
        logger.error(f"Failed to initialize pgvector database: {e}")

@app.on_event("startup")
def startup_event():
    init_vector_db()
    get_embedding_model()

class IngestRequest(BaseModel):
    tenant_id: str
    document_id: str
    content: str
    metadata: Optional[dict] = Field(default_factory=dict)

class ChatRequest(BaseModel):
    tenant_id: str
    message: str
    similarity_threshold: Optional[float] = 0.7

class ChatResponse(BaseModel):
    reply: str
    needs_escalation: bool
    similarity: float
    sources: List[str]
    provider_used: str

ESCALATION_KEYWORDS = [
    r"\bhuman\b", r"\bagent\b", r"\brepresentative\b", r"\bperson\b",
    r"\brefund\b", r"\bcancel\b", r"\blawyer\b", r"\bsue\b",
    r"\btalk to a human\b", r"\bspeak to an agent\b"
]

RAG_PROMPT_TEMPLATE = """You are a helpful and polite AI support agent for a customer service workspace.
Answer the visitor's question naturally in your own words, strictly using ONLY the provided context below.
Do NOT invent or extrapolate facts not present in the context.
If the context does not contain enough information to answer the question, respond exactly with: "I don't have that specific detail right now. Let me connect you with a specialist who will follow up shortly."

Context:
{context}

Visitor Question:
{question}

Answer:"""

def get_llm_instance(provider: str):
    """Instantiate provider-agnostic LangChain LLM model."""
    provider = provider.lower().strip()
    
    if provider == "gemini":
        google_api_key = os.getenv("GOOGLE_API_KEY")
        if not google_api_key:
            raise ValueError("GOOGLE_API_KEY environment variable not set")
        from langchain_google_genai import ChatGoogleGenerativeAI
        return ChatGoogleGenerativeAI(
            model="gemini-flash-latest",
            google_api_key=google_api_key,
            temperature=0.2,
            max_retries=1,
        )
    
    elif provider == "claude":
        anthropic_api_key = os.getenv("ANTHROPIC_API_KEY")
        if not anthropic_api_key:
            raise ValueError("ANTHROPIC_API_KEY environment variable not set")
        from langchain_anthropic import ChatAnthropic
        return ChatAnthropic(
            model="claude-3-5-haiku-20241022",
            anthropic_api_key=anthropic_api_key,
            temperature=0.2,
        )
    
    elif provider in ("openrouter", "claude-openrouter"):
        openrouter_api_key = os.getenv("OPENROUTER_API_KEY")
        if not openrouter_api_key:
            raise ValueError("OPENROUTER_API_KEY environment variable not set")
        try:
            from langchain_openai import ChatOpenAI
        except ImportError:
            from langchain_community.chat_models import ChatOpenAI
        return ChatOpenAI(
            model="anthropic/claude-3-haiku",
            base_url="https://openrouter.ai/api/v1",
            api_key=openrouter_api_key,
            temperature=0.2,
            max_retries=1,
        )
    
    else:
        raise ValueError(f"Unsupported LLM provider '{provider}'")

def generate_llm_response(context: str, question: str) -> tuple[str, str]:
    """
    Generate response using LangChain chain with primary provider and fallback provider.
    Returns (generated_text, provider_name_used).
    """
    primary_provider = os.getenv("AI_PROVIDER_PRIMARY", "gemini")
    fallback_provider = os.getenv("AI_PROVIDER_FALLBACK", "openrouter")

    prompt = ChatPromptTemplate.from_template(RAG_PROMPT_TEMPLATE)
    
    # 1. Attempt Primary Provider
    try:
        logger.info(f"Attempting LLM generation with Primary Provider: {primary_provider}")
        llm = get_llm_instance(primary_provider)
        chain = prompt | llm | StrOutputParser()
        result = chain.invoke({"context": context, "question": question})
        if result and result.strip():
            return result.strip(), primary_provider
    except Exception as e:
        logger.warning(f"Primary LLM Provider ({primary_provider}) failed: {e}. Retrying with Fallback Provider ({fallback_provider})...")

    # 2. Attempt Fallback Provider
    try:
        logger.info(f"Attempting LLM generation with Fallback Provider: {fallback_provider}")
        llm = get_llm_instance(fallback_provider)
        chain = prompt | llm | StrOutputParser()
        result = chain.invoke({"context": context, "question": question})
        if result and result.strip():
            return result.strip(), fallback_provider
    except Exception as e:
        logger.warning(f"Fallback LLM Provider ({fallback_provider}) failed: {e}. Using grounded template fallback.")

    # 3. Grounded fallback if no LLM API key configured or network offline
    logger.info("Using local grounded template fallback.")
    reply_text = context
    if "Content:" in context:
        reply_text = context.split("Content:", 1)[1].strip()
    return f"Here is what our knowledge base says: {reply_text}", "local-grounded-fallback"

@app.get("/health")
def health():
    return {
        "status": "ok",
        "vector_db": "pgvector(384)",
        "embedding_model": "all-MiniLM-L6-v2",
        "primary_provider": os.getenv("AI_PROVIDER_PRIMARY", "gemini"),
        "fallback_provider": os.getenv("AI_PROVIDER_FALLBACK", "claude"),
    }

@app.post("/api/v1/ingest", status_code=status.HTTP_201_CREATED)
def ingest_document(req: IngestRequest):
    try:
        model = get_embedding_model()
        vec = model.encode(req.content).tolist()
        vec_json = json.dumps(vec)

        conn = get_db_connection()
        cur = conn.cursor()
        cur.execute("""
            INSERT INTO tenant_vector_store (tenant_id, document_id, content, metadata, embedding)
            VALUES (%s, %s, %s, %s, %s::vector)
            ON CONFLICT (tenant_id, document_id)
            DO UPDATE SET
                content = EXCLUDED.content,
                metadata = EXCLUDED.metadata,
                embedding = EXCLUDED.embedding,
                created_at = CURRENT_TIMESTAMP;
        """, (req.tenant_id, req.document_id, req.content, json.dumps(req.metadata), vec_json))
        conn.commit()
        cur.close()
        conn.close()
        return {"status": "ingested", "document_id": req.document_id, "tenant_id": req.tenant_id}
    except Exception as e:
        logger.error(f"Ingestion error: {e}")
        raise HTTPException(status_code=500, detail=str(e))

@app.delete("/api/v1/ingest/{tenant_id}/{document_id}")
def delete_document(tenant_id: str, document_id: str):
    try:
        conn = get_db_connection()
        cur = conn.cursor()
        cur.execute("DELETE FROM tenant_vector_store WHERE tenant_id = %s AND document_id = %s;", (tenant_id, document_id))
        conn.commit()
        cur.close()
        conn.close()
        return {"status": "deleted", "document_id": document_id, "tenant_id": tenant_id}
    except Exception as e:
        logger.error(f"Delete error: {e}")
        raise HTTPException(status_code=500, detail=str(e))

@app.post("/api/v1/chat", response_model=ChatResponse)
def generate_chat_reply(req: ChatRequest):
    # 1. Escalation check
    message_text = req.message.lower()
    for kw in ESCALATION_KEYWORDS:
        if re.search(kw, message_text):
            return ChatResponse(
                reply="I'm connecting you with a human support agent who can best assist you with this request.",
                needs_escalation=True,
                similarity=1.0,
                sources=[],
                provider_used="rule-based-escalation"
            )

    # 2. Strict Tenant Isolation Query using pgvector native cosine distance (<=>)
    try:
        model = get_embedding_model()
        vec = model.encode(req.message).tolist()
        vec_json = json.dumps(vec)

        conn = get_db_connection()
        cur = conn.cursor(cursor_factory=RealDictCursor)
        
        # 1 - (embedding <=> vec) gives cosine similarity in pgvector
        cur.execute("""
            SELECT document_id, content, metadata, 1 - (embedding <=> %s::vector) AS similarity
            FROM tenant_vector_store
            WHERE tenant_id = %s AND embedding IS NOT NULL
            ORDER BY embedding <=> %s::vector
            LIMIT 3;
        """, (vec_json, req.tenant_id, vec_json))
        
        rows = cur.fetchall()
        cur.close()
        conn.close()

        if not rows:
            return ChatResponse(
                reply="I don't have that specific detail right now. Let me connect you with a specialist who will follow up shortly.",
                needs_escalation=False,
                similarity=0.0,
                sources=[],
                provider_used="none"
            )

        top_match = rows[0]
        top_sim = float(top_match["similarity"])
        threshold = req.similarity_threshold or 0.7

        if top_sim < threshold:
            return ChatResponse(
                reply="I don't have that specific detail right now. Let me connect you with a specialist who will follow up shortly.",
                needs_escalation=False,
                similarity=round(top_sim, 4),
                sources=[],
                provider_used="threshold-fallback"
            )

        # 3. Build RAG Context & Run LangChain LLM Generation Chain
        context_docs = [r["content"] for r in rows if float(r["similarity"]) >= threshold]
        context_text = "\n\n---\n\n".join(context_docs)
        sources = [r["document_id"] for r in rows if float(r["similarity"]) >= threshold]

        generated_reply, provider_used = generate_llm_response(context_text, req.message)

        return ChatResponse(
            reply=generated_reply,
            needs_escalation=False,
            similarity=round(top_sim, 4),
            sources=sources,
            provider_used=provider_used
        )
    except Exception as e:
        logger.error(f"Chat RAG error: {e}")
        raise HTTPException(status_code=500, detail=str(e))
