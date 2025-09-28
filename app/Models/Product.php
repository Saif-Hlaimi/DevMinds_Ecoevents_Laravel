<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

    /**
     * Accesseur pour obtenir les caractéristiques sous forme de tableau
     */
    public function getCaracteristiquesArrayAttribute()
    {
        if (empty($this->caracteristiques)) {
            return [];
        }
        
        return array_map('trim', explode(',', $this->caracteristiques));
    }

    /**
     * Vérifie si le produit est en stock
     */
    public function getInStockAttribute()
    {
        return $this->quantity > 0;
    }

    /**
     * Formate le prix avec le symbole dollar
     */
    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 2);
    }
}