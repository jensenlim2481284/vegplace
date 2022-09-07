<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model 
{

    protected $table = 'company';
    protected $guarded = ['id'];
    protected $hidden = ['id'];


    /*************************************************
     
                    MODEL RELATION 

    **************************************************/
         
    
    # Relation to access company users
    public function user()
    {
        return $this->hasMany('App\Models\User','company_id','id');
    }

    
    # Relation to access order
    public function order()
    {
        return $this->hasMany('App\Models\Order','company_id','id');
    }



   
}
