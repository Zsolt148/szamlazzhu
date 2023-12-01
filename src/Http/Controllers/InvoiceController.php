<?php

namespace Zsolt148\Szamlazzhu\Http\Controllers;

use Illuminate\Http\Request;
use Zsolt148\Szamlazzhu\Facades\Szamlazzhu;
use Zsolt148\Szamlazzhu\Http\Resources\InvoiceResource;
use Zsolt148\Szamlazzhu\Models\Invoice;

class InvoiceController extends Controller
{
    public function createInvoice(Request $request)
    {
        $model = $this->getModel($request);

        $this->authorize('update', $model);

        if (! $model->isInvoiceable()) {
            return response()->json([
                'error' => 'Nincs meg minden számlázási adat a számla kiállításához',
            ]);
        }

        Szamlazzhu::invoice()->dispatchCreate($model);

        return response()->json([
            'success' => 'Számla készítés sikeresen elkezdődött',
        ]);
    }

    public function cancelInvoice(Request $request, Invoice $invoice)
    {
        $model = $this->getModel($request);

        $this->authorize('update', $model);

        if (! $model->hasInvoice()) {
            return response()->json(['error' => 'Sikertelen sztórnózás']);
        }

        $invoice = Szamlazzhu::invoice()->cancelNow($invoice);

        if (! $invoice) {
            return response()->json(['error' => 'Sikertelen sztórnózás']);
        }

        return response()->json([
            'success' => 'Sikeresen sztórnózva',
            'invoice' => InvoiceResource::make($invoice),
        ]);
    }
}
