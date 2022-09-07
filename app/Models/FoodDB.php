<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodDB extends Model 
{

    protected $table = 'food_database';
    protected $guarded = ['id'];


    /*************************************************
     
                    MODEL RELATION 

    **************************************************/
         
    
    # Relation to access provider
    public function provider()
    {
        return $this->hasOne('App\Models\FoodProvider','id','food_provider_id');
    }



   
}
