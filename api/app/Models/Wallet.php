<?php

namespace App\Models;
use Hashids;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $table = "wallet_transactions";

     protected $visible = [
        "id",
        "description",
        "type",
        "created_at",
       
    ];


   public function toArray()
    {
        
        $attributes = parent::toArray();
        $attributes['id'] = Hashids::encode($this->id);
        $attributes['transaction_amount'] = $this->transact_amt;
        $attributes['balance'] = $this->amt_left;
        
        return $attributes;

    }



}
