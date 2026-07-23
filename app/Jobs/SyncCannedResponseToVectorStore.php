<?php

namespace App\Jobs;

use App\Models\CannedResponse;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncCannedResponseToVectorStore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $action; // 'ingest' | 'delete'
    public string $tenantId;
    public string $documentId;
    public string $content;
    public array $metadata;

    public function __construct(string $action, string $tenantId, string $documentId, string $content = '', array $metadata = [])
    {
        $this->action = $action;
        $this->tenantId = $tenantId;
        $this->documentId = $documentId;
        $this->content = $content;
        $this->metadata = $metadata;
    }

    public static function fromModel(CannedResponse $cannedResponse, string $action = 'ingest'): self
    {
        $docId = 'canned_response_' . $cannedResponse->id;
        $content = "Title: {$cannedResponse->title}\nShortcut: {$cannedResponse->shortcut_key}\nContent: {$cannedResponse->body}";
        
        $meta = [
            'shortcut_key' => $cannedResponse->shortcut_key,
            'title'        => $cannedResponse->title,
            'tags'         => $cannedResponse->tags ?? [],
        ];

        return new self($action, $cannedResponse->tenant_id, $docId, $content, $meta);
    }

    public function handle(): void
    {
        $ragServiceUrl = env('RAG_SERVICE_URL', 'http://rag:8080');

        try {
            if ($this->action === 'delete') {
                $response = Http::timeout(5)->delete("{$ragServiceUrl}/api/v1/ingest/{$this->tenantId}/{$this->documentId}");
            } else {
                $response = Http::timeout(5)->post("{$ragServiceUrl}/api/v1/ingest", [
                    'tenant_id'   => $this->tenantId,
                    'document_id' => $this->documentId,
                    'content'     => $this->content,
                    'metadata'    => $this->metadata,
                ]);
            }

            if ($response->failed()) {
                Log::error("RAG Service ingestion failed", [
                    'action' => $this->action,
                    'tenant_id' => $this->tenantId,
                    'document_id' => $this->documentId,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error("RAG Service connection error", [
                'error' => $e->getMessage(),
                'tenant_id' => $this->tenantId,
                'document_id' => $this->documentId,
            ]);
        }
    }
}
