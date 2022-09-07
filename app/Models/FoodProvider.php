<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodProvider extends Model 
{

    protected $table = 'food_provider_database';
    protected $guarded = ['id'];
    protected $hidden = ['id'];


    /*************************************************
     
                    MODEL RELATION 

    **************************************************/
         
    
    # Relation to access food
    public function food()
    {
        return $this->hasMany('App\Models\FoodDB','food_provider_id','id');
    }



   
}
