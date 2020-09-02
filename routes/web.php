<?php
use App\Transaction;
use App\Account;
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

Route::get('/test', function () {
    $depreciation_accounts = [];
    $accounts = Account::whereBetween('account_code', [15011200, 15011550])
                ->get();
    foreach($accounts as $account){
        array_push($depreciation_accounts, 
        ['account_type' => $account->account_type,
        'sub_account_type' => $account->sub_account_type]);            
    }
    dd($depreciation_accounts);            
});

// firstOrNew where date = last day and depreciatiable->id = depreciatiable_id(column) if exists skip else insert entry.
// check if depreciatiable->id = depreciatiable_id(column) is also applicable to payment and collection.
Route::get('/test2', function () {
    $depreciatiables = Transaction::whereBetween('account_code', [15011200, 15011550])
    ->where('amount', '>', DB::raw('total_deduction + salvage_value'))
    //->where(DB::raw('total_deduction + salvage_value'),'<','amount')
    ->get();
    $depreciations = [];

    $current_month = date('m');
    $current_year = date('Y');
    $date = Carbon::create($current_year, $current_month, 1);
    $year_month = $date->subMonth()->format('Y-m'); 
    $daysInMonth = $date->daysInMonth; 
    $previous_month_last_date = $year_month.'-'.$daysInMonth;


    foreach($depreciatiables as $depreciatiable){
        //select where depreciated_id = $depreciatiable->id and transaction_type = 'DEPRECIATION' and depreciation_date = $last_day 
        $depreciation_entry = Transaction::firstOrNew([
            'depreciated_id' => $depreciatiable->id,
            'transaction_type' => 'DEPRECIATION',
            'depreciation_date' => $previous_month_last_date
        ]);
        
        if ($depreciation_entry->exists) {
            continue;
        }    


        $depreciation = $depreciatiable->amount/$depreciatiable->useful_life;
        $remainingBalance = $depreciatiable->amount - $depreciatiable->total_deduction - $depreciatiable->salvage_value;
        if($depreciation > $remainingBalance){
            $depreciation = $remainingBalance;
        }
        
        // *************************
        $depreciation_entry->account_type= $depreciatiable->account_type;
        $depreciation_entry->sub_account_type= $depreciatiable->sub_account_type;
        $depreciation_entry->main_code= $depreciatiable->main_code;
        $depreciation_entry->main_account= $depreciatiable->main_account;
        $depreciation_entry->type= $depreciatiable->type;
        $depreciation_entry->counterpart_code= $depreciatiable->counterpart_code;
        $depreciation_entry->counterpart_name= $depreciatiable->counterpart_name;
        // *************************

        $depreciation_entry->transaction_entry_id= $depreciatiable->transaction_entry_id;
        $depreciation_entry->payee_id= $depreciatiable->payee_id;
        $depreciation_entry->branch_id= $depreciatiable->current_branch_id;
        $depreciation_entry->account_code= $depreciatiable->account_code;
        $depreciation_entry->account_name= $depreciatiable->account_name;
        $depreciation_entry->reference_no= $depreciatiable->reference_no;
        $depreciation_entry->transaction_no= $depreciatiable->transaction_no;
        $depreciation_entry->transaction_type= $depreciatiable->transaction_type;
        $depreciation_entry->transaction_date= $depreciatiable->transaction_date;
        $depreciation_entry->amount= number_format($depreciation,2);
        $depreciation_entry->credit_amount= 0;
        $depreciation_entry->debit_amount= number_format($depreciation,2);
        $depreciation_entry->total_payment= 0;
        $depreciation_entry->amount_ex_tax= 0;
        $depreciation_entry->vat= 0;
        $depreciation_entry->wtax_code= 0;
        $depreciation_entry->wtax= 0;
        $depreciation_entry->user_id = 999;
        $depreciation_entry->useful_life= 0;
        $depreciation_entry->salvage_value= 0;
        $depreciation_entry->status= 'CONFIRMED';


        // Check code counter part account_code 
        // Debit depreciation expenss Credit accu. Dep.
        // make sure to add counter part account code column in transactions table and fill it one journal entry.
        $depreciations[$depreciatiable->id] = number_format($depreciation,2);

        //array_push($depreciations,number_format($depreciation,2));
    }
    return $depreciations;
});


Route::get('/lastdate/{year}/{month}', function ($current_year,$current_month) {
    $current_month = date('m');
    $current_year = date('Y');
    $date = Carbon::create($current_year, $current_month, 1);
    $year_month = $date->subMonth()->format('Y-m'); 
    $daysInMonth = $date->daysInMonth; 
    return $year_month.'-'.$daysInMonth;

    //return $month.' '.$year;
    //$last_day = Carbon::create(2018, $month - 1, 1)->daysInMonth;
    //return $last_day;
});

Auth::routes();


Route::get('/home', 'HomeController@index')->name('home');

Route::get('{path}','HomeController@index')->where( 'path', '([A-z\d-/_.]+)?' );

