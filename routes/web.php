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


Route::get('/test', function () {
    $depreciatiables = Transaction::whereBetween('account_code', [15011200, 15011550])
    ->where('amount', '>', DB::raw('total_deduction + salvage_value'))
    //->where(DB::raw('total_deduction + salvage_value'),'<','amount')
    ->get();
    $depreciations = [];
    foreach($depreciatiables as $depreciatiable){
        $depreciation = round($depreciatiable->amount/$depreciatiable->useful_life,2);

        $remainingBalance = $depreciatiable->amount - ($depreciatiable->total_collection + $depreciatiable->salvage_value);
        /*
        if($depreciation > $remainingBalance){
            $depreciation = $remainingBalance;
        }
        */
        array_push($depreciations,$remainingBalance);
    }
    return $depreciations;
});

Auth::routes();


Route::get('/home', 'HomeController@index')->name('home');

Route::get('{path}','HomeController@index')->where( 'path', '([A-z\d-/_.]+)?' );

