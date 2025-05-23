<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        // Sección 1
        'name',
        'model',
        'cb_key',
        'serial_number',
        'batch',
        'group',
        // Sección 2
        'brand_id',
        'specialty_area',
        'supplier_id',
        'brand_reference',
        // Sección 3
        'location',
        'manufacturer_unit',
        'freight_company',
        'freight_cost',
        'expiration_date',
        'quantity',
        'description',
        'image_path',
        'created_by'
    ];

    protected $casts = [
        'expiration_date' => 'date',
        'freight_cost' => 'decimal:2',
        'quantity' => 'integer',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Agregar la relación de batches al modelo Product
    public function batches()
    {
        return $this->hasMany(ProductBatch::class);
    }

    // Método para obtener la cantidad total del producto
    public function getTotalQuantityAttribute()
    {
        return $this->batches()->sum('quantity');
    }

    // Método para obtener lotes próximos a caducar (30 días)
    public function getExpiringBatchesAttribute()
    {
        $thirtyDaysFromNow = now()->addDays(30);
        return $this->batches()
            ->whereNotNull('expiration_date')
            ->where('expiration_date', '<=', $thirtyDaysFromNow)
            ->where('expiration_date', '>=', now())
            ->where('quantity', '>', 0)
            ->get();
    }
}
