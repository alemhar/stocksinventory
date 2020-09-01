<?php
use App\Transaction;
use Carbon\Carbon;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


// firstOrNew where date = last day and depreciatiable->id = depreciatiable_id(column) if exists skip else insert entry.
// check if depreciatiable->id = depreciatiable_id(column) is also applicable to payment and collection.
Route::get('/test', function () {
    $depreciatiables = Transaction::whereBetween('account_code', [15011200, 15011550])
    ->where('amount', '>', DB::raw('total_deduction + salvage_value'))
    //->where(DB::raw('total_deduction + salvage_value'),'<','amount')
    ->get();
    $depreciations = [];

    $month = date('m');
    $last_day = Carbon::create(2018, $month - 1, 1)->daysInMonth;

    foreach($depreciatiables as $depreciatiable){
        //select where depreciated_id = $depreciatiable->id and transaction_type = 'DEPRECIATION' and depreciation_date = $last_day
        $depreciation = $depreciatiable->amount/$depreciatiable->useful_life;
        $remainingBalance = $depreciatiable->amount - $depreciatiable->total_deduction - $depreciatiable->salvage_value;
        if($depreciation > $remainingBalance){
            $depreciation = $remainingBalance;
        }
        $depreciations[$depreciatiable->id] = number_format($depreciation,2);
        //array_push($depreciations,number_format($depreciation,2));
    }
    return $depreciations;
});


Route::get('/lastdate/{year}/{month}', function ($current_year,$current_month) {
    //$current_month = date('m');
    //$current_year = date('Y');
    
    $date = Carbon::create($current_year, $current_month, 1);

    //$date = \Carbon\Carbon::now();
    
    $year_month = $date->subMonth()->format('Y-m'); 
    $daysInMonth = $date->subMonth()->daysInMonth; 
    
    return $year_month.'-'.$daysInMonth;
    //return $month.' '.$year;
    //$last_day = Carbon::create(2018, $month - 1, 1)->daysInMonth;
    //return $last_day;
});

Auth::routes();


Route::get('/home', 'HomeController@index')->name('home');

Route::get('{path}','HomeController@index')->where( 'path', '([A-z\d-/_.]+)?' );

