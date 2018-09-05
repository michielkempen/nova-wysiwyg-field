<?php

namespace Michielkempen\NovaWysiwygField\Jobs;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Michielkempen\NovaWysiwygField\Models\PendingWysiwygFile;
use Michielkempen\NovaWysiwygField\WysiwygField;

class StorePendingWysiwygFile
{
    /**
     * @var WysiwygField
     */
    public $field;

    /**
     * @param WysiwygField $field
     */
    public function __construct(WysiwygField $field)
    {
        $this->field = $field;
    }

    /**
     * Attach a pending attachment to the field.
     *
     * @param Request  $request
     * @return void
     */
    public function __invoke(Request $request)
    {
        $pendingFile = PendingWysiwygFile::create([
            'draft_id' => $request->draftId,
            'file' => $request->file->store('/', $this->field->disk),
            'disk' => $this->field->disk,
        ]);

        return Storage::disk($this->field->disk)->url($pendingFile->file);
    }
}
