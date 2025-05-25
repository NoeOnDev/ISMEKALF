<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItemBatch extends Model
{
    protected $fillable = [
        'order_item_id',
        'product_batch_id',
        'quantity'
    ];

    public function productBatch()
    {
        return $this->belongsTo(ProductBatch::class);
    }
}
