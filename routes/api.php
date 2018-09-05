<?php

use Illuminate\Support\Facades\Route;
use Michielkempen\NovaWysiwygField\Http\Controllers\WysiwygFileController;

Route::post('/wysiwyg-files/store', WysiwygFileController::class.'@store');
Route::post('/wysiwyg-files/delete', WysiwygFileController::class.'@delete');