<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'quantity',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtenir le total du panier pour un utilisateur ou une session
     */
    public static function getTotal($userId = null, $sessionId = null)
    {
        $query = static::query()->with('product');
        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($sessionId) {
            $query->where('session_id', $sessionId);
        }
        $items = $query->get();
        $total = 0;
        foreach ($items as $item) {
            $total += $item->quantity * ($item->product->price ?? 0);
        }
        return $total;
    }

    /**
     * Obtenir le nombre total d'articles dans le panier
     */
    public static function getTotalItems($userId = null, $sessionId = null)
    {
        $query = static::query();
        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($sessionId) {
            $query->where('session_id', $sessionId);
        }
        return $query->sum('quantity');
    }

    /**
     * Obtenir tous les articles du panier
     */
    public static function getItems($userId = null, $sessionId = null)
    {
        $query = static::query()->with('product');
        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($sessionId) {
            $query->where('session_id', $sessionId);
        }
        return $query->get();
    }

    /**
     * Vider le panier
     */
    public static function clear($userId = null, $sessionId = null)
    {
        $query = static::query();
        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($sessionId) {
            $query->where('session_id', $sessionId);
        }
        return $query->delete();
    }
}