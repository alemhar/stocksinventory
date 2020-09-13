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
Route::get('searchBranch', 'API\BranchController@search');

Route::get('searchCD', 'API\CDController@search');
Route::put('account', 'API\UserController@updateAccountInfo');

Route::delete('cd/cancel/{transaction_no}', 'API\CDController@cancelTransaction');
Route::post('cd/confirm/{transaction_no}', 'API\CDController@confirmTransaction');
Route::get('cd/items/list', 'API\CDItemController@list');
Route::get('cd/entries/list', 'API\CDController@paymentList');
Route::get('cd/entries/collectionList', 'API\CDController@collectionList');
Route::get('cd/purchase/list', 'API\CDController@list');
Route::get('cd/sales/list', 'API\CDController@listSales');

Route::post('ledgers', 'API\AccountController@store_ledger');
Route::post('transactions', 'API\CDController@store_transactions');
Route::get('transactions', 'API\CDController@load_transactions');
Route::get('transaction', 'API\CDController@get_transaction');
Route::get('reverse_transaction', 'API\CDController@reverse_transaction');


Route::post('items', 'API\CDItemController@store_items');
Route::post('check', 'API\CheckController@store_checks');

Route::post('record_payment', 'API\CDController@record_payment');
Route::post('record_collection', 'API\CDController@record_collection');
Route::post('update_payee_account', 'API\PayeeController@updateAccount');

Route::get('ledgers/{account_code}', 'API\CDController@load_ledger');
