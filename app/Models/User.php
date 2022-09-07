<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable 
{

    protected $guarded = ['id'];
    protected $hidden = ['password', 'id'];
    





    /*************************************************
     
                    MODEL RELATIONSHIP 

    **************************************************/
     
    # Function to access company 
    public function company()
    {
        return $this->hasOne('App\Models\Company','id','company_id');
    }

     
    # Function to access order
    public function order()
    {
        return $this->hasMany('App\Models\Order','order_id','id');
    }


}
