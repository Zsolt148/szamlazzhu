<?php

namespace Zsolt148\Szamlazzhu\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $model_type
 * @property int $model_id
 * @property string $receipt_number
 * @property string $receipt_file
 * @property bool $is_cancel
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $model
 * @property-read string $receipt_file_path
 * @property-read string $receipt_file_url
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

    public function model(): MorphTo
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
