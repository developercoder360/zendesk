<?php

namespace App\Observers;

use App\Jobs\SyncCannedResponseToVectorStore;
use App\Models\CannedResponse;

class CannedResponseObserver
{
    public function created(CannedResponse $cannedResponse): void
    {
        SyncCannedResponseToVectorStore::fromModel($cannedResponse, 'ingest')->handle();
    }

    public function updated(CannedResponse $cannedResponse): void
    {
        SyncCannedResponseToVectorStore::fromModel($cannedResponse, 'ingest')->handle();
    }

    public function deleted(CannedResponse $cannedResponse): void
    {
        SyncCannedResponseToVectorStore::fromModel($cannedResponse, 'delete')->handle();
    }
}
