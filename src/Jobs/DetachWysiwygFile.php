<?php

namespace Michielkempen\NovaWysiwygField\Jobs;

use Illuminate\Http\Request;
use Michielkempen\NovaWysiwygField\Models\WysiwygFile;
use Michielkempen\NovaWysiwygField\WysiwygField;

class DetachWysiwygFile
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
     * Delete an attachment from the field.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __invoke(Request $request)
    {
        WysiwygFile::where('url', $request->fileUrl)->get()->each->purge();
    }
}
