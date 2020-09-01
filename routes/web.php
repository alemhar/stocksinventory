<?php
use App\Transaction;
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
    foreach($depreciatiables as $depreciatiable){
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


Route::get('/lastdate/{month}', function ($month) {
    
    $last_day = Carbon::create(2018, $month, 1);
    return $last_day;
});

Auth::routes();


Route::get('/home', 'HomeController@index')->name('home');

Route::get('{path}','HomeController@index')->where( 'path', '([A-z\d-/_.]+)?' );

