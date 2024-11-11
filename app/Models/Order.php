<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $client_id
 * @property int $product_type_id
 * @property mixed $need_by_date
 * @property mixed $completion_date
 */

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'product_type_id',
        'need_by_date'
    ];

    /**
     * @return HasMany<OrderItem>
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    /**
     * @return BelongsTo<ProductType>
     */
    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }

    /**
     * @return BelongsTo<Client>
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * @return int
     * returns combined production time of all items in the order, in seconds, rounded up
     */
    public function getProductionTime(): int
    {
        $orderItems = $this->orderItems;
        $productionTime = 0;
        foreach ($orderItems as $orderItem) {
            $productionTime += $orderItem->getProductionTime();
        }

        return $productionTime;
    }
}
