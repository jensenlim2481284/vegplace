<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model 
{

    protected $table = 'orders';
    protected $guarded = ['id'];
    protected $hidden = ['id'];


  
    # Function to access food 
    public function food()
    {
        return $this->hasOne('App\Models\FoodDB','id','food_id');
    }
  
    # Function to access user 
    public function user()
    {
        return $this->hasOne('App\Models\User','id','user_id');
    }
   
}
