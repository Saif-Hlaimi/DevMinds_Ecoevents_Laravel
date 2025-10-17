<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'customer_city',
        'customer_postal_code',
        'payment_method',
        'notes',
        'total',
        'payment_intent_id',
        'payment_status', // Use 'payment_status' instead of 'status' to match the database
        'admin_notes',
        'tracking_number',
        'shipping_notes',
        'delivery_notes',
        'qr_code_data',
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Accessors
    public function getFormattedTotalAttribute()
    {
        return number_format($this->total, 2, ',', ' ') . ' €';
    }

    public function getTotalItemsAttribute()
    {
        return $this->items->sum('quantity');
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->payment_status) {
            'pending' => 'En attente',
            'succeeded' => 'Payée',
            'unpaid' => 'Non payée',
            'cancelled' => 'Annulée',
            default => ucfirst($this->payment_status),
        };
    }

    public function getStatusClassAttribute()
    {
        return match ($this->payment_status) {
            'pending' => 'bg-warning',
            'succeeded' => 'bg-success',
            'unpaid' => 'bg-info',
            'cancelled' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    /**
     * Check if the order can be cancelled based on payment status
     */
    public function canBeCancelled()
    {
        return in_array($this->payment_status, ['pending', 'unpaid']);
    }

    /**
     * Scope to filter out cancelled orders
     */
    public function scopeNotCancelled($query)
    {
        return $query->where('payment_status', '!=', 'cancelled');
    }
}