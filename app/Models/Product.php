<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'caracteristiques',
        'image_path',
        'quantity',
        'price',
        'discount_price',
    ];

    /**
     * Get the characteristics as an array.
     */
    public function getCaracteristiquesArrayAttribute()
    {
        if (empty($this->caracteristiques)) {
            return [];
        }
        return array_map('trim', explode(',', $this->caracteristiques));
    }

    /**
     * Check if the product is in stock.
     */
    public function getInStockAttribute()
    {
        return $this->quantity > 0;
    }

    /**
     * Format the price with a euro sign.
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2, ',', ' ') . ' €';
    }

    /**
     * Format the discount price with a euro sign.
     */
    public function getFormattedDiscountPriceAttribute()
    {
        return $this->discount_price ? number_format($this->discount_price, 2, ',', ' ') . ' €' : null;
    }

    /**
     * Get the full URL of the product image.
     */
    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }
        return asset('assets/images/product/product1.png');
    }

    /**
     * Define the relationship with comments.
     */
    public function commentProds(): HasMany
    {
        return $this->hasMany(CommentProd::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get the number of comments for this product.
     */
    public function getCommentsCountAttribute()
    {
        return $this->commentProds()->count();
    }

    /**
     * Define the relationship with product views.
     */
    public function views(): HasMany
    {
        return $this->hasMany(ProductView::class);
    }

    /**
     * Define the relationship with order items.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the total quantity sold for this product.
     */
    public function getTotalSoldAttribute()
    {
        return $this->orderItems()
            ->whereHas('order', function($query) {
                $query->whereIn('payment_status', ['succeeded', 'shipped', 'delivered']);
            })
            ->sum('quantity');
    }

    /**
     * Scope a query to include total sold.
     */
    public function scopeWithTotalSold($query)
    {
        return $query->withSum([
            'orderItems as total_sold' => function($query) {
                $query->whereHas('order', function($q) {
                    $q->whereIn('payment_status', ['succeeded', 'shipped', 'delivered']);
                });
            }
        ], 'quantity');
    }
}