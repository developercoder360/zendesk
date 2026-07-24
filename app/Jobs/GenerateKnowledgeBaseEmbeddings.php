<?php

namespace App\Jobs;

use App\Models\KnowledgeBaseDocument;
use App\Models\KnowledgeBaseEmbedding;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GenerateKnowledgeBaseEmbeddings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $documentId;
    public string $rawTextContent;

    public function __construct(int $documentId, string $rawTextContent)
    {
        $this->documentId = $documentId;
        $this->rawTextContent = $rawTextContent;
    }

    public function handle(): void
    {
        $doc = KnowledgeBaseDocument::find($this->documentId);
        if (!$doc) {
            return;
        }

        try {
            $tenantId = $doc->tenant_id;
            $text = trim($this->rawTextContent);

            if (empty($text)) {
                $doc->update(['status' => 'failed']);
                return;
            }

            // Delete existing chunk embeddings for this document
            KnowledgeBaseEmbedding::where('document_id', $doc->id)->delete();

            // Simple text chunking (approx. 800 chars with 100 char overlap)
            $chunkSize = 800;
            $overlap = 100;
            $length = strlen($text);
            $chunks = [];

            for ($i = 0; $i < $length; $i += ($chunkSize - $overlap)) {
                $chunk = substr($text, $i, $chunkSize);
                if (trim($chunk) !== '') {
                    $chunks[] = trim($chunk);
                }
                if ($i + $chunkSize >= $length) {
                    break;
                }
            }

            foreach ($chunks as $index => $chunkText) {
                KnowledgeBaseEmbedding::create([
                    'tenant_id' => $tenantId,
                    'document_id' => $doc->id,
                    'chunk_text' => $chunkText,
                    'embedding_model_version' => 'all-MiniLM-L6-v2',
                ]);
            }

            // Ingest into Python RAG microservice
            $ragUrl = env('RAG_SERVICE_URL', 'http://rag:8080');
            $response = Http::timeout(20)->post("{$ragUrl}/api/v1/ingest", [
                'tenant_id' => (string) $tenantId,
                'document_id' => "kb-doc-{$doc->id}",
                'content' => $text,
                'metadata' => [
                    'title' => $doc->title,
                    'source_type' => $doc->source_type,
                ],
            ]);

            if ($response->successful()) {
                $doc->update(['status' => 'ready']);
            } else {
                Log::error("RAG Service Ingest Error [{$response->status()}]: " . $response->body());
                $doc->update(['status' => 'failed']);
            }
        } catch (\Throwable $e) {
            Log::error("Failed to generate KB embeddings for document {$this->documentId}: " . $e->getMessage());
            $doc->update(['status' => 'failed']);
        }
    }
}
