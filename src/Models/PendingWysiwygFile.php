<?php

namespace Michielkempen\NovaWysiwygField\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Michielkempen\NovaWysiwygField\WysiwygField;

class PendingWysiwygFile extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Persist the given draft's pending attachments.
     *
     * @param  string  $draftId
     * @param  WysiwygField  $field
     * @param  mixed  $model
     * @return void
     */
    public static function persistDraft($draftId, WysiwygField $field, $model)
    {
        static::where('draft_id', $draftId)->get()->each->persist($field, $model);
    }

    /**
     * Persist the pending attachment.
     *
     * @param WysiwygField $field
     * @param  mixed $model
     * @return void
     * @throws \Exception
     */
    public function persist(WysiwygField $field, $model)
    {
        WysiwygFile::create([
            'model_type' => get_class($model),
            'model_id' => $model->getKey(),
            'file' => $this->file,
            'disk' => $field->disk,
            'url' => Storage::disk($field->disk)->url($this->file),
        ]);

        $this->delete();
    }

    /**
     * Purge the attachment.
     *
     * @return void
     * @throws \Exception
     */
    public function purge()
    {
        Storage::disk($this->disk)->delete($this->file);

        $this->delete();
    }
}
