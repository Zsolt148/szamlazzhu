<?php

namespace Zsolt148\Szamlazzhu\Http\Controllers;

use Illuminate\Http\Request;
use Zsolt148\Szamlazzhu\Facades\Szamlazzhu;
use Zsolt148\Szamlazzhu\Http\Resources\ReceiptResource;
use Zsolt148\Szamlazzhu\Models\Receipt;

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

    public function cancelReceipt(Request $request, Receipt $receipt)
    {
        $model = $this->getModel($request);

        $this->authorize('update', $model);

        if (! $model->hasReceipt()) {
            return response()->json(['error' => 'Sikertelen sztórnózás']);
        }

        $receipt = Szamlazzhu::receipt()->cancelNow($receipt);

        if (! $receipt) {
            return response()->json(['error' => 'Sikertelen sztórnózás']);
        }

        return response()->json([
            'success' => 'Sikeresen sztórnózva',
            'invoice' => ReceiptResource::make($receipt),
        ]);
    }
}
