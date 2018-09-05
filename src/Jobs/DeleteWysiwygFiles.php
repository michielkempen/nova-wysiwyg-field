<?php

namespace Michielkempen\NovaWysiwygField\Jobs;

use Illuminate\Http\Request;
use Michielkempen\NovaWysiwygField\Models\WysiwygFile;
use Michielkempen\NovaWysiwygField\WysiwygField;

class DeleteWysiwygFiles
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
     * Delete the attachments associated with the field.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $model
     * @return void
     */
    public function __invoke(Request $request, $model)
    {
        WysiwygFile::where('attachable_type', get_class($model))
                ->where('attachable_id', $model->getKey())
                ->get()
                ->each
                ->purge();
    }
}
