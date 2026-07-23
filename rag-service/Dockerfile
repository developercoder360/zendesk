# syntax=docker/dockerfile:1.4
# Multi-Stage Dockerfile for FastAPI RAG Microservice (Development & Production)

# ==========================================
# STAGE 1: Base Environment
# ==========================================
FROM python:3.11-slim AS base

ENV PYTHONUNBUFFERED=1 \
    PYTHONDONTWRITEBYTECODE=1 \
    PIP_DISABLE_PIP_VERSION_CHECK=1 \
    HF_HOME=/app/.cache/huggingface

WORKDIR /app

# Install runtime OS dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpq5 \
    curl \
    && rm -rf /var/lib/apt/lists/*

# ==========================================
# STAGE 2: Dependency Builder & Model Bake
# ==========================================
FROM base AS builder

# Install build tools needed for native extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
    gcc \
    g++ \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

COPY requirements.txt .

# Use BuildKit pip cache mount + CPU-only PyTorch wheel index
RUN --mount=type=cache,target=/root/.cache/pip \
    pip install --prefix=/install -r requirements.txt

# Pre-download SentenceTransformer model weights during build to prevent cold-start latency
RUN --mount=type=cache,target=/root/.cache/pip \
    PYTHONPATH=/install/lib/python3.11/site-packages \
    python -c "from sentence_transformers import SentenceTransformer; SentenceTransformer('all-MiniLM-L6-v2')"

# ==========================================
# STAGE 3: Development Stage (Default target for compose dev)
# ==========================================
FROM base AS development

COPY --from=builder /install /usr/local
COPY --from=builder /app/.cache /app/.cache
COPY . .

EXPOSE 8080

CMD ["uvicorn", "main:app", "--host", "0.0.0.0", "--port", "8080", "--reload"]

# ==========================================
# STAGE 4: Production Stage (Minimal & Secure)
# ==========================================
FROM base AS production

# Create non-root system user
RUN groupadd -g 10001 appuser && \
    useradd -u 10000 -g appuser -s /bin/sh appuser

COPY --from=builder /install /usr/local
COPY --from=builder /app/.cache /app/.cache
COPY . .

RUN chown -R appuser:appuser /app
USER appuser

EXPOSE 8080

CMD ["uvicorn", "main:app", "--host", "0.0.0.0", "--port", "8080", "--workers", "2"]
