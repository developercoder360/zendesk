<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Knowledge Base</h1>
            <p class="text-sm text-muted-foreground">Manage documentation, upload reference articles, and feed context into the 24/7 AI Assistant.</p>
        </div>
        <x-ui.button wire:click="openCreateModal">
            <x-lucide-plus class="size-4 mr-1.5" />
            Add Document
        </x-ui.button>
    </div>

    <!-- Search Bar -->
    <div class="flex items-center space-x-3 mb-6">
        <div class="relative flex-1 max-w-sm">
            <x-ui.input 
                type="search" 
                wire:model.live.debounce.300ms="search" 
                placeholder="Search documents by title..." 
                class="pl-9"
            />
            <x-lucide-search class="size-4 absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground pointer-events-none" />
        </div>
    </div>

    <!-- Documents Table -->
    <x-ui.card>
        <x-ui.card-content class="p-0">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-muted/50 text-xs font-semibold uppercase tracking-wider text-muted-foreground border-b border-border">
                        <tr>
                            <th class="px-4 py-3">Document Title</th>
                            <th class="px-4 py-3">Source Type</th>
                            <th class="px-4 py-3">Vector Chunks</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Created Date</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($documents as $doc)
                            <tr class="hover:bg-muted/40 transition-colors">
                                <td class="px-4 py-3 font-semibold text-foreground">
                                    <div class="flex items-center space-x-2">
                                        <x-lucide-file-text class="size-4 text-primary shrink-0" />
                                        <span>{{ $doc->title }}</span>
                                    </div>
                                    @if($doc->source_reference)
                                        <span class="text-[11px] text-muted-foreground font-mono block mt-0.5">{{ Str::limit($doc->source_reference, 45) }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-muted text-foreground border border-input uppercase">
                                        {{ $doc->source_type }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-xs">
                                    <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-primary/10 text-primary border border-primary/20">
                                        <x-lucide-layers class="size-3 mr-1" />
                                        {{ $doc->embeddings_count }} {{ Str::plural('chunk', $doc->embeddings_count) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-xs">
                                    @if($doc->status === 'ready')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                            <x-lucide-check-circle class="size-3 mr-1" />
                                            Ready
                                        </span>
                                    @elseif($doc->status === 'processing')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 animate-pulse">
                                            <x-lucide-loader-2 class="size-3 mr-1 animate-spin" />
                                            Processing
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-destructive/10 text-destructive border border-destructive/20">
                                            <x-lucide-alert-circle class="size-3 mr-1" />
                                            Failed
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs text-muted-foreground">
                                    {{ $doc->created_at->format('M j, Y g:i A') }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <x-ui.button 
                                        variant="ghost" 
                                        size="xs" 
                                        wire:click="deleteDocument({{ $doc->id }})" 
                                        wire:confirm="Are you sure you want to delete this document and all its vector embeddings?"
                                        class="text-destructive hover:text-destructive"
                                    >
                                        <x-lucide-trash-2 class="size-3.5" />
                                    </x-ui.button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-xs text-muted-foreground">
                                    No knowledge base documents created yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-ui.card-content>
    </x-ui.card>

    <div class="mt-4">
        {{ $documents->links() }}
    </div>

    <!-- Create Document Modal -->
    @if ($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 animate-fade-in">
            <x-ui.card class="w-full max-w-lg bg-card border border-border shadow-2xl">
                <x-ui.card-header class="pb-3 border-b border-border">
                    <div class="flex items-center justify-between">
                        <x-ui.card-title class="text-base">Add Knowledge Base Document</x-ui.card-title>
                        <button type="button" wire:click="$set('showCreateModal', false)" class="text-muted-foreground hover:text-foreground">
                            <x-lucide-x class="size-4" />
                        </button>
                    </div>
                </x-ui.card-header>

                <form wire:submit="saveDocument">
                    <x-ui.card-content class="space-y-4 pt-4 text-xs">
                        <div>
                            <x-ui.label for="doc_title">Document Title</x-ui.label>
                            <x-ui.input id="doc_title" wire:model="title" placeholder="e.g. Return & Refund Policy" required />
                            @error('title') <span class="text-destructive text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-ui.label for="source_type">Source Type</x-ui.label>
                            <select 
                                id="source_type" 
                                wire:model.live="source_type" 
                                class="w-full h-9 rounded-md border border-input bg-background px-3 py-1 text-xs shadow-sm focus:outline-none focus:ring-1 focus:ring-ring"
                            >
                                <option value="manual">Manual Text Entry</option>
                                <option value="url">Web Page URL (Fetch & Scrape)</option>
                                <option value="upload">File Upload (.txt, .md)</option>
                            </select>
                            @error('source_type') <span class="text-destructive text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                        </div>

                        @if ($source_type === 'manual')
                            <div>
                                <x-ui.label for="manual_content">Article Content</x-ui.label>
                                <x-ui.textarea id="manual_content" wire:model="manual_content" rows="6" placeholder="Paste or type the full knowledge base article content here..." required />
                                @error('manual_content') <span class="text-destructive text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                            </div>
                        @elseif ($source_type === 'url')
                            <div>
                                <x-ui.label for="url_reference">Web Page URL</x-ui.label>
                                <x-ui.input id="url_reference" type="url" wire:model="url_reference" placeholder="https://example.com/faq" required />
                                <span class="text-[11px] text-muted-foreground mt-1 block">Content will be fetched and converted into vector embeddings.</span>
                                @error('url_reference') <span class="text-destructive text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                            </div>
                        @elseif ($source_type === 'upload')
                            <div>
                                <x-ui.label for="file_upload">Upload Plaintext Document (.txt, .md)</x-ui.label>
                                <x-ui.input id="file_upload" type="file" wire:model="file_upload" accept=".txt,.md" required />
                                <span class="text-[11px] text-muted-foreground mt-1 block">Max file size: 5MB.</span>
                                @error('file_upload') <span class="text-destructive text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                            </div>
                        @endif
                    </x-ui.card-content>

                    <div class="p-4 border-t border-border flex justify-end space-x-2 bg-muted/20">
                        <x-ui.button type="button" variant="outline" wire:click="$set('showCreateModal', false)">Cancel</x-ui.button>
                        <x-ui.button type="submit">Ingest Document</x-ui.button>
                    </div>
                </form>
            </x-ui.card>
        </div>
    @endif
</div>
