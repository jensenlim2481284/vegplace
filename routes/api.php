<?php

use Illuminate\Support\Facades\Route;


# API route 
Route::group(['middleware' => 'api.authentication'], function () {

    Route::post('/get/food', ['uses' => 'APIController@getFood']);
    Route::post('/get/company', ['uses' => 'APIController@getCompany']);
    Route::post('/get/order', ['uses' => 'APIController@getOrder']);
    Route::post('/update/company', ['uses' => 'APIController@updateCompany']);
    Route::post('/update/setting', ['uses' => 'APIController@updateSetting']);
    Route::post('/update/user', ['uses' => 'APIController@updateUser']);
    Route::post('/create/order', ['uses' => 'APIController@createOrder']);
    Route::post('/retrieve/statistic', ['uses' => 'APIController@retrieveStatistic']);
    
});

Route::post('/webhook', ['uses' => 'APIController@webhook']);