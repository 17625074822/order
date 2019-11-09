<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sku extends Model
{
    //
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
