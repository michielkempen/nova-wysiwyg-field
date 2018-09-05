<?php

namespace Michielkempen\NovaWysiwygField\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class WysiwygFile extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Purge the file.
     *
     * @throws Exception
     */
    public function purge()
    {
        Storage::disk($this->disk)->delete($this->file);

        $this->delete();
    }
}
