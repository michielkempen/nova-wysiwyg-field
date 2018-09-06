<?php

namespace Michielkempen\NovaWysiwygField\Jobs;

use Michielkempen\NovaWysiwygField\Models\PendingWysiwygFile;

class PruneStaleWysiwygFiles
{
    /**
     * Prune the stale attachments from the system.
     *
     * @return void
     */
    public function __invoke()
    {
        PendingWysiwygFile::where('created_at', '<=', now()->subDays(1))
            ->orderBy('id', 'desc')
            ->chunk(100, function ($files) {
                $files->each->purge();
            });
    }
}
