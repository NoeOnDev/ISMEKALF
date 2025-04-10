<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'contact_name',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'postal_code',
        'notes',
        'active'
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Obtener los productos asociados a este proveedor.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
