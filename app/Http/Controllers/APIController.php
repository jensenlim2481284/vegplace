<?php

namespace App\Http\Controllers;

use Storage;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Order;
use App\Models\FoodDB;
use App\Models\Company;
use App\Models\FoodProvider;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class APIController extends Controller
{

    # Get Food data
    public function getFood(Request $request)
    {
        # Check company setting 
        $company = Company::where('uid', $request->companyID)->first();
        if(!$company) abort(404);
        $companySetting = json_decode($company->setting);
        
        # Filter food with company setting 
        $min = isset($companySetting->minPrice)?$companySetting->minPrice:0;
        $max = isset($companySetting->maxPrice)?$companySetting->maxPrice:999;
        $foodList = FoodProvider::with(['food' => function ($query) use ($min, $max){
            $query->where('price', '>', $min)->where('price', '<', $max);
        }]);

        return $foodList->get();
    }


    # Get placed order
    public function getOrder(Request $request)
    {

        # Get specific user order
        if($request->userID)
            $order = Order::where('user_id', $request->userID)->whereDate('created_at', Carbon::today())->with('food')->get();
        else if($request->companyID)
            $order = Order::where('company_id', $request->companyID)->whereDate('created_at', Carbon::today())->with('food', 'user')->get();
       
        return $order;
    }


    # Get Company data
    public function getCompany(Request $request)
    {
        $company = Company::where('uid', $request->companyID)->first();
        if(!$company) abort(404);
        return $company;
    }


    # Update company
    public function updateCompany(Request $request)
    {
        # Check if company create before 
        $company = Company::updateOrCreate(
            ['uid'=>$request->id],
            ['name'=>$request->name]
        );
        return $company;
    }

    # Update company setting
    public function updateSetting(Request $request)
    {

        # Get setting 
        $setting = json_encode($request->except('companyID'));
        
        # Update company setting
        $company = Company::where('uid', $request->companyID)->first();
        if(!$company) abort(404);
        $company->update(['setting' => $setting]);
        return response()->json(['message' => 'success'], 200); 
    }



    # Update user
    public function updateUser(Request $request)
    {
        # Retrieve company 
        $company = Company::where('uid', $request->companyID)->first();
        if(!$company) abort(404);

        # Check if user create before 
        User::updateOrCreate(
            ['uid'=>$request->id, 'company_id' => $company->id],
            ['name'=>$request->name]
        );
        return response()->json(['message' => 'success'], 200); 
    }


    # Create order
    public function createOrder(Request $request)
    {

        # Get user 
        $user = User::where('uid', $request->userID)->first();
        $company = $user->company;

        # Check if user order exceed limit
        $companySetting = json_decode($company->setting);
        $userOrder = Order::where('user_id', $request->userID)->whereDate('created_at', \Carbon\Carbon::today())->count();
        $maxOrderPerDay = isset($companySetting->orderPerDay)?$companySetting->orderPerDay:1;
        if($userOrder >= $maxOrderPerDay) 
            return response()->json(['message' => 'Exceed max order per day'], 400); 

        # Get food 
        $food = FoodDB::where('id', $request->foodID)->first();

        # Validate data 
        if(!$food || !$user) abort(404);

        # Create new order 
        Order::create([
            'user_id' => $request->userID,
            'company_id' => $company->id,
            'food_id' => $food->id,
            'order_id' => uniqid('ord_'),
            'amount' => $food->price,
            'tx_status' => config('system.transaction.status.placed')
        ]);

        # Deduct company balance 
        $company->decrement('credit', $food->price);

        return response()->json(['message' => 'success'], 200); 

    }


    # Retrieve statistic
    public function retrieveStatistic(Request $request)
    {


        # If retrieve statistic data for overall 
        if($request->companyID){
    
            # Calculate how much CO2 saved in total 
            $company = Company::where('uid', $request->companyID)->first();
            if(!$company) abort(404);
            $totalOrder = Order::where('company_id', $request->companyID)->count();
            $totalCO2Saved = $totalOrder * 2.75;
    
            # Calculate how much CO2 saved by every user 
            $userOrder = User::where('company_id', $request->companyID)->with('order')->get();
            foreach($userOrder as $user){
                $user->CO2saved = count($user->order) * 2.75;
    
                # Calculate total vegan intake for each user 
                $user->totalVegIntake = count($user->order);
            }
    
            # Calculate overall health score percentage ( user vs vegan intake )
            $days = Carbon::parse($company->created_at)->diffInDays(Carbon::now());
            $overallScore = $days * count($company->user) / $totalOrder * 100 ;
        }

        # If retrieve self statistic data 
        else {

            # Calculate how much CO2 saved in total 
            $user = User::where('uid', $request->userID)->first();
            if(!$user) abort(404);
            $totalOrder = Order::where('user_id', $request->userID)->count();
            $totalCO2Saved = $totalOrder * 2.75;
            $company = $user->company;
    
            # Calculate overall health score percentage ( user vs vegan intake )
            $days = Carbon::parse($company->created_at)->diffInDays(Carbon::now());
            $overallScore = $days / $totalOrder * 100 ;


        }
        

        return 1;
    }
    

    
    # Process payment webhook
    public function webhook(Request $request)
    {
        Storage::put('test.txt', $request);
    }

}
 