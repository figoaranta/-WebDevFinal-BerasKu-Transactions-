<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $table = "orders";
    public function account()
    {
    	return $this->belongsTo('App\Account');
    }
    protected $fillable = [
        'cart', 	
		'address', 	
		'name', 
        'paymentId',		
		'account_id',		
    ];
}
