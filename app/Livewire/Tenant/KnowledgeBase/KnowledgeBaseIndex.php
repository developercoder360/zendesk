<?php

namespace App\Livewire\Tenant\KnowledgeBase;

use App\Jobs\GenerateKnowledgeBaseEmbeddings;
use App\Models\KnowledgeBaseDocument;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.tenant')]
#[Title('Knowledge Base')]
class KnowledgeBaseIndex extends Component
{
    use WithPagination, WithFileUploads;

    public bool $showCreateModal = false;

    #[Validate('required|string|max:255')]
    public string $title = '';

    #[Validate('required|in:manual,url,upload')]
    public string $source_type = 'manual';

    public string $manual_content = '';
    public string $url_reference = '';
    public $file_upload = null;

    public string $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function saveDocument()
    {
        $this->validate();

        $tenantId = tenant('id');
        $rawText = '';
        $sourceReference = null;

        if ($this->source_type === 'manual') {
            $this->validate(['manual_content' => 'required|string']);
            $rawText = $this->manual_content;
        } elseif ($this->source_type === 'url') {
            $this->validate(['url_reference' => 'required|url']);
            $sourceReference = $this->url_reference;
            
            try {
                $response = Http::timeout(10)->get($this->url_reference);
                if ($response->successful()) {
                    $rawText = strip_tags($response->body());
                    // Clean extra spaces
                    $rawText = preg_replace('/\s+/', ' ', $rawText);
                } else {
                    $this->addError('url_reference', 'Could not fetch content from the provided URL.');
                    return;
                }
            } catch (\Throwable $e) {
                $this->addError('url_reference', 'Error connecting to URL: ' . $e->getMessage());
                return;
            }
        } elseif ($this->source_type === 'upload') {
            $this->validate(['file_upload' => 'required|file|mimes:txt,md|max:5120']);
            $sourceReference = $this->file_upload->getClientOriginalName();
            $rawText = file_get_contents($this->file_upload->getRealPath());
        }

        if (empty(trim($rawText))) {
            $this->addError('manual_content', 'Extracted content is empty.');
            return;
        }

        $doc = KnowledgeBaseDocument::create([
            'tenant_id' => $tenantId,
            'title' => $this->title,
            'source_type' => $this->source_type,
            'source_reference' => $sourceReference,
            'status' => 'processing',
        ]);

        // Dispatch background embedding job
        GenerateKnowledgeBaseEmbeddings::dispatch($doc->id, $rawText);

        $this->dispatch('toast', message: 'Knowledge base document created. Embedding generation started.', type: 'success');
        $this->resetForm();
        $this->showCreateModal = false;
    }

    public function deleteDocument(int $id)
    {
        $doc = KnowledgeBaseDocument::where('tenant_id', tenant('id'))->findOrFail($id);

        // Call RAG microservice to remove embeddings
        try {
            $ragUrl = env('RAG_SERVICE_URL', 'http://rag:8080');
            Http::timeout(5)->delete("{$ragUrl}/api/v1/ingest/" . tenant('id') . "/kb-doc-{$doc->id}");
        } catch (\Throwable $e) {
            Log::warning("Could not delete RAG document embeddings: " . $e->getMessage());
        }

        // Delete DB record (cascades to embeddings table)
        $doc->delete();

        $this->dispatch('toast', message: 'Document and embeddings deleted successfully.', type: 'warning');
    }

    public function resetForm()
    {
        $this->title = '';
        $this->source_type = 'manual';
        $this->manual_content = '';
        $this->url_reference = '';
        $this->file_upload = null;
        $this->resetValidation();
    }

    public function render()
    {
        $documents = KnowledgeBaseDocument::where('tenant_id', tenant('id'))
            ->when($this->search !== '', fn($q) => $q->where('title', 'ilike', '%' . $this->search . '%'))
            ->withCount('embeddings')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.tenant.knowledge-base.knowledge-base-index', [
            'documents' => $documents,
        ]);
    }
}
