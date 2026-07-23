import os
import re
import json
import logging
from typing import List, Optional
from fastapi import FastAPI, HTTPException, status
from pydantic import BaseModel, Field
import psycopg2
from psycopg2.extras import RealDictCursor
import numpy as np
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger("rag-service")

app = FastAPI(title="Zendesk Multi-Tenant RAG Service")

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
        cur.execute("""
            CREATE TABLE IF NOT EXISTS tenant_vector_store (
                id SERIAL PRIMARY KEY,
                tenant_id VARCHAR(255) NOT NULL,
                document_id VARCHAR(255) NOT NULL,
                content TEXT NOT NULL,
                metadata JSONB DEFAULT '{}'::jsonb,
                created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
                UNIQUE(tenant_id, document_id)
            );
        """)
        cur.execute("CREATE INDEX IF NOT EXISTS idx_vector_tenant ON tenant_vector_store(tenant_id);")
        conn.commit()
        cur.close()
        conn.close()
        logger.info("RAG vector database table initialized successfully.")
    except Exception as e:
        logger.error(f"Failed to initialize RAG database: {e}")

@app.on_event("startup")
def startup_event():
    init_vector_db()

class IngestRequest(BaseModel):
    tenant_id: str
    document_id: str
    content: str
    metadata: Optional[dict] = Field(default_factory=dict)

class ChatRequest(BaseModel):
    tenant_id: str
    message: str
    similarity_threshold: Optional[float] = 0.5

class ChatResponse(BaseModel):
    reply: str
    needs_escalation: bool
    similarity: float
    sources: List[str]

ESCALATION_KEYWORDS = [
    r"\bhuman\b", r"\bagent\b", r"\brepresentative\b", r"\bperson\b",
    r"\brefund\b", r"\bcancel\b", r"\blawyer\b", r"\bsue\b",
    r"\btalk to a human\b", r"\bspeak to an agent\b"
]

@app.get("/health")
def health():
    return {"status": "ok", "engine": "scikit-learn TF-IDF Vectorizer"}

@app.post("/api/v1/ingest", status_code=status.HTTP_201_CREATED)
def ingest_document(req: IngestRequest):
    try:
        conn = get_db_connection()
        cur = conn.cursor()
        cur.execute("""
            INSERT INTO tenant_vector_store (tenant_id, document_id, content, metadata)
            VALUES (%s, %s, %s, %s)
            ON CONFLICT (tenant_id, document_id)
            DO UPDATE SET
                content = EXCLUDED.content,
                metadata = EXCLUDED.metadata,
                created_at = CURRENT_TIMESTAMP;
        """, (req.tenant_id, req.document_id, req.content, json.dumps(req.metadata)))
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
                sources=[]
            )

    # 2. Strict Tenant Isolation Query
    try:
        conn = get_db_connection()
        cur = conn.cursor(cursor_factory=RealDictCursor)
        cur.execute("""
            SELECT document_id, content, metadata
            FROM tenant_vector_store
            WHERE tenant_id = %s;
        """, (req.tenant_id,))
        rows = cur.fetchall()
        cur.close()
        conn.close()

        if not rows:
            return ChatResponse(
                reply="I don't have that specific detail right now. Let me connect you with a specialist who will follow up shortly.",
                needs_escalation=False,
                similarity=0.0,
                sources=[]
            )

        # 3. Compute Cosine Similarity + Keyword Overlap Score
        corpus = [r["content"] for r in rows]
        vectorizer = TfidfVectorizer(stop_words='english', ngram_range=(1, 2), sublinear_tf=True)
        
        all_texts = corpus + [req.message]
        tfidf_matrix = vectorizer.fit_transform(all_texts)
        
        doc_vectors = tfidf_matrix[:-1]
        query_vector = tfidf_matrix[-1:]
        
        tfidf_sims = cosine_similarity(query_vector, doc_vectors)[0]
        
        # Calculate combined similarity score
        words_query = set(re.findall(r'\w+', req.message.lower())) - {'is', 'a', 'the', 'and', 'or', 'in', 'on', 'at', 'to', 'for', 'with', 'what', 'how', 'much'}
        
        combined_scores = []
        for i, row in enumerate(rows):
            tfidf_s = float(tfidf_sims[i])
            doc_words = set(re.findall(r'\w+', row["content"].lower()))
            overlap = len(words_query.intersection(doc_words)) / max(1, len(words_query)) if words_query else 0.0
            
            score = (0.5 * tfidf_s) + (0.5 * overlap)
            combined_scores.append(score)

        best_idx = int(np.argmax(combined_scores))
        top_sim = float(combined_scores[best_idx])
        threshold = req.similarity_threshold or 0.5

        if top_sim < threshold:
            return ChatResponse(
                reply="I don't have that specific detail right now. Let me connect you with a specialist who will follow up shortly.",
                needs_escalation=False,
                similarity=round(top_sim, 4),
                sources=[]
            )

        top_match = rows[best_idx]
        best_content = top_match["content"]
        
        reply_text = best_content
        if "Content:" in best_content:
            reply_text = best_content.split("Content:", 1)[1].strip()

        return ChatResponse(
            reply=reply_text,
            needs_escalation=False,
            similarity=round(top_sim, 4),
            sources=[top_match["document_id"]]
        )
    except Exception as e:
        logger.error(f"Chat RAG error: {e}")
        raise HTTPException(status_code=500, detail=str(e))
