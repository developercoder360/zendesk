<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class KnowledgeBaseDocument extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id', 'title', 'source_type', 'source_reference', 'status',
    ];

    public function embeddings()
    {
        return $this->hasMany(KnowledgeBaseEmbedding::class, 'document_id');
    }
}
