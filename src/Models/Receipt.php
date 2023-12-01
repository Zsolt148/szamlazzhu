<?php

namespace Zsolt148\Szamlazzhu\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'receipt_number',
        'receipt_file',
        'is_cancel',
    ];

    protected $casts = [
        'is_cancel' => 'boolean',
    ];

    protected $appends = [
        'receipt_file_path',
        'receipt_file_url',
    ];

    public function receiptable(): MorphTo
    {
        return $this->morphTo('model')->withTrashed();
    }

    public function hasReceipt(): bool
    {
        return $this->receipt_file !== null;
    }

    public function setReceipt(string $number, bool $isCancel = false): self
    {
        $this->receipt_number = $number;
        $this->receipt_file = $number.'.pdf';
        $this->is_cancel = $isCancel;

        return $this;
    }

    public function updateReceipt(string $number, bool $isCancel = false): self
    {
        $this
            ->setReceipt($number, $isCancel)
            ->save();

        return $this;
    }

    protected function receiptFilePath(): Attribute
    {
        return Attribute::get(function () {
            return storage_path(
                'app/'.config('szamlazz-hu.client.storage.disk').'/'.config('szamlazz-hu.client.storage.path').'/'.$this->receipt_file
            );
        });
    }

    protected function receiptFileUrl(): Attribute
    {
        return Attribute::get(function () {
            return Storage::url(config('szamlazz-hu.client.storage.path').'/'.$this->receipt_file);
        });
    }
}
