<?php

namespace Zsolt148\Szamlazzhu\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Zsolt148\Szamlazzhu\Models\Receipt;

/** @mixin Receipt */
class ReceiptResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'receipt_number' => $this->receipt_number,
            'receipt_file' => $this->receipt_file,
            'is_cancel' => $this->is_cancel,
            'receipt_file_path' => $this->receipt_file_path,
            'receipt_file_url' => $this->receipt_file_url,
        ]);
    }
}
