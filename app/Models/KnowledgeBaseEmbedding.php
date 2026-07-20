<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class KnowledgeBaseEmbedding extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id', 'document_id', 'chunk_text', 'embedding_model_version',
    ];

    public function document()
    {
        return $this->belongsTo(KnowledgeBaseDocument::class, 'document_id');
    }

    /**
     * Scope a query to search for similar embeddings using pgvector.
     *
     * IMPORTANT: tenant_id is a MANDATORY parameter. The WHERE filter is applied
     * BEFORE the similarity ordering (<=>) to absolutely prevent cross-tenant leakage
     * during approximate nearest-neighbor HNSW searches.
     *
     * @param  string  $tenantId  MANDATORY tenant boundary
     * @param  string  $vector  The query vector (e.g., '[0.1, 0.2, ...]')
     */
    public function scopeSearchSimilarity(Builder $query, string $tenantId, string $vector, int $limit = 5): Builder
    {
        return $query->where('tenant_id', $tenantId)
            ->orderByRaw('embedding <=> ?', [$vector])
            ->limit($limit);
    }
}
