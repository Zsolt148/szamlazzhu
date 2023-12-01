<?php

namespace Zsolt148\Szamlazzhu\Http\Controllers;

use Illuminate\Http\Request;
use Zsolt148\Szamlazzhu\Facades\Szamlazzhu;

class ReceiptController extends Controller
{
    public function createReceipt(Request $request)
    {
        $model = $this->getModel($request);

        $this->authorize('update', $model);

        if (! $model->isReceiptable()) {
            return response()->json([
                'error' => 'Nincs meg minden számlázási adat a számla kiállításához',
            ]);
        }

        Szamlazzhu::receipt()->create($model);

        return response()->json([
            'success' => 'Számla készítés sikeresen elkezdődött',
        ]);
    }
}
