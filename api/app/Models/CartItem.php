<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    
    protected $cart_items = 'cart_items';

    /**
     * Get all of the owning commentable models.
     */
    public function item()
    {
        return $this->morphTo();
    }

}
