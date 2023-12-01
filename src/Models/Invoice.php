<?php

namespace Zsolt148\Szamlazzhu\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

/**
 * App\Models\Invoice
 *
 * @property int $id
 * @property string $model_type
 * @property int $model_id
 * @property string $invoice_number
 * @property string $invoice_file
 * @property int $is_cancel
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $invoiceable
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice query()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereInvoiceFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereInvoiceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereIsCancel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'invoice_file',
        'is_cancel',
    ];

    protected $casts = [
        'is_cancel' => 'boolean',
    ];

    protected $appends = [
        'invoice_file_path',
        'invoice_file_url',
    ];

    public function invoiceable(): MorphTo
    {
        return $this->morphTo('model')->withTrashed();
    }

    public function hasInvoice(): bool
    {
        return $this->invoice_file !== null;
    }

    public function setInvoice(string $number, bool $isCancel = false): self
    {
        $this->invoice_number = $number;
        $this->invoice_file = $number.'.pdf';
        $this->is_cancel = $isCancel;

        return $this;
    }

    public function updateInvoice(string $number, bool $isCancel = false): self
    {
        $this
            ->setInvoice($number, $isCancel)
            ->save();

        return $this;
    }

    protected function invoiceFilePath(): Attribute
    {
        return Attribute::get(function () {
            return storage_path(
                'app/'.config('szamlazz-hu.client.storage.disk').'/'.config('szamlazz-hu.client.storage.path').'/'.$this->invoice_file
            );
        });
    }

    protected function invoiceFileUrl(): Attribute
    {
        return Attribute::get(function () {
            return Storage::url(config('szamlazz-hu.client.storage.path').'/'.$this->invoice_file);
        });
    }
}
