<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResources([
    'user' => 'API\UserController'
]);

Route::apiResources([
    'payee' => 'API\PayeeController'
]);

Route::apiResources([
    'chartaccount' => 'API\AccountController'
]);

Route::apiResources([
    'branch' => 'API\BranchController'
]);


Route::apiResources([
    'cd' => 'API\CDController'
]);


Route::apiResources([
    'cd/entry' => 'API\CDEntryController'
]);


Route::apiResources([
    'cd/item' => 'API\CDItemController'
]);

Route::apiResources([
    'taxrate' => 'API\TaxRateController'
]);


Route::get('account', 'API\UserController@account');
Route::get('findUser', 'API\UserController@search');
Route::get('searchAccount', 'API\AccountController@search');
Route::get('searchPayee', 'API\PayeeController@search');

Route::get('searchCD', 'API\CDController@search');
Route::put('account', 'API\UserController@updateAccountInfo');

Route::delete('cd/cancel/{transaction_no}', 'API\CDController@cancelTransaction');
Route::post('cd/confirm/{transaction_no}', 'API\CDController@confirmTransaction');
Route::get('cd/items/list', 'API\CDItemController@list');
Route::get('cd/entries/list', 'API\CDEntryController@list');
Route::get('cd/purchase/list', 'API\CDController@list');
Route::post('ledgers', 'API\AccountController@store_ledger');
Route::post('record_payment', 'API\CDController@record_payment');
Route::post('update_payee_account', 'API\PayeeController@updateAccount');

Route::get('ledgers/{account_code}', 'API\AccountController@load_ledger');
