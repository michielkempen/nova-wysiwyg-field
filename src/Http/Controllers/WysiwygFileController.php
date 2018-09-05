<?php

namespace Michielkempen\NovaWysiwygField\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;

class WysiwygFileController extends Controller
{
    /**
     * Store a file.
     *
     * @param  NovaRequest  $request
     * @return Response
     */
    public function store(NovaRequest $request)
    {
        $field = $request->newResource()
            ->availableFields($request)
            ->findFieldByAttribute($request->field, function () {
                abort(404);
            });

        return response()->json(['link' => call_user_func(
            $field->attachCallback, $request
        )]);
    }

    /**
     * Delete a single, persisted file by URL.
     *
     * @param NovaRequest  $request
     */
    public function delete(NovaRequest $request)
    {
        $field = $request->newResource()
            ->availableFields($request)
            ->findFieldByAttribute($request->field, function () {
                abort(404);
            });

        call_user_func(
            $field->detachCallback, $request
        );
    }
}