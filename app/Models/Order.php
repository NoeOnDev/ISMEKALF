<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'remission_number',
        'client_id',
        'status',
        'notes',
        'created_by'
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Método para generar un número de remisión único
    public static function generateRemissionNumber(): string
    {
        $date = now()->format('Ymd');
        $lastOrder = self::whereDate('created_at', now())->latest()->first();

        $sequence = 1;
        if ($lastOrder) {
            $parts = explode('-', $lastOrder->remission_number);
            if (count($parts) > 1 && $parts[1] === $date) {
                $sequence = (int)$parts[2] + 1;
            }
        }

        return 'REM-' . $date . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }
}
