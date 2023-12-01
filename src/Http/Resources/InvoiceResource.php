<?php

namespace Zsolt148\Szamlazzhu\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Zsolt148\Szamlazzhu\Models\Invoice;

/** @mixin Invoice */
class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'invoice_number' => $this->invoice_number,
            'invoice_file' => $this->invoice_file,
            'is_cancel' => $this->is_cancel,
            'invoice_file_path' => $this->invoice_file_path,
            'invoice_file_url' => $this->invoice_file_url,
        ]);
    }
}
