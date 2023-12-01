<?php

namespace Zsolt148\Szamlazzhu\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Zsolt148\Szamlazzhu\Interfaces\Invoiceable;
use Zsolt148\Szamlazzhu\Interfaces\Receiptable;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function getModel(Request $request): Receiptable|Invoiceable
    {
        $validated = $request->validate([
            'class' => ['required', 'string'],
            'id' => ['required', 'integer'],
        ]);

        $class = app($validated['class']);
        $model = $class::findOrFail($validated['id']);

        if (
            ! $model instanceof Receiptable &&
            ! $model instanceof Invoiceable
        ) {
            throw new \RuntimeException($model::class.' is not instanceof ['.Receiptable::class.'] or ['.Receiptable::class.']');
        }

        return $model;
    }
}
