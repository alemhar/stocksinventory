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
    $current_month = date('m');
    $current_year = date('Y');
    $date = Carbon::create($current_year, $current_month, 1);
    $year_month = $date->subMonth()->format('Y-m'); 
    $daysInMonth = $date->daysInMonth; 
    $previous_month_last_date = $year_month.'-'.$daysInMonth;

    $depreciation_entry = Transaction::where([
        'depreciated_id' => 36,
        'transaction_type' => 'DEPRECIATION',
        'depreciation_date' => $previous_month_last_date
    ])->get();

    return count($depreciation_entry);
});

Route::get('/test1', function () {
    $depreciatiables = Transaction::whereBetween('account_code', [15011200, 15011550])
        ->where('amount', '>', DB::raw('total_deduction + salvage_value'))
        ->get();
    dd($depreciatiables);   
});

// firstOrNew where date = last day and depreciatiable->id = depreciatiable_id(column) if exists skip else insert entry.
// check if depreciatiable->id = depreciatiable_id(column) is also applicable to payment and collection.
Route::get('/test2', function () {

    $depreciation_accounts = [];
    $ranges = [];
    array_push($ranges, [15011200, 15011550]);
    array_push($ranges, [15015000, 15020000]);
    array_push($ranges, [62200000, 62201400]);
    
    foreach($ranges as $range){
        $accounts = Account::whereBetween('account_code', $range)
                ->get();
        foreach($accounts as $account){
            $depreciation_accounts[$account->account_code] = 
            ['account_type' => $account->account_type,
            'sub_account_type' => $account->sub_account_type,
            'main_code' => $account->main_code,
            'main_account' => $account->main_account,
            'account_code' => $account->account_code,
            'account_name' => $account->account_name,
            'type' => $account->type,
            'counterpart_code' => $account->counterpart_code,
            'counterpart_name' => $account->counterpart_name
            ];
        }
    }    
    

    $depreciations = [];
    $depreciatiables = Transaction::whereBetween('account_code', [15011200, 15011550])
    ->where('amount', '>', DB::raw('total_deduction + salvage_value'))
    ->get();
    

    $current_month = date('m');
    $current_year = date('Y');
    $date = Carbon::create($current_year, $current_month, 1);
    $year_month = $date->subMonth()->format('Y-m'); 
    $daysInMonth = $date->daysInMonth; 
    $previous_month_last_date = $year_month.'-'.$daysInMonth;


    foreach($depreciatiables as $depreciatiable){
        sleep(1);
        $transaction_no = time().'999';
        
        $depreciation_entry = Transaction::where([
            'depreciated_id' => $depreciatiable->id,
            'transaction_type' => 'DEPRECIATION',
            'depreciation_date' => $previous_month_last_date
        ])->get();


        if (count($depreciation_entry) > 0) {
            continue;
        }    

        
        $depreciation = $depreciatiable->amount/$depreciatiable->useful_life;
        $remainingBalance = $depreciatiable->amount - $depreciatiable->total_deduction - $depreciatiable->salvage_value;
        if($depreciation > $remainingBalance){
            $depreciation = $remainingBalance;
        }
        
        
        


        $transactions = [];
        $account_code = $depreciatiable->account_code;
        $credit = true;
        $amount = 0;
        $credit_amount = 0;
        $debit_amount = 0;

        do{
                $amount = number_format($depreciation,2);
            if($credit){
                $credit = false;
                $credit_amount = number_format($depreciation,2);
                $debit_amount = 0;
            } else {
                $credit = true;
                $credit_amount = 0;
                $debit_amount = number_format($depreciation,2);
            }
            $account_code = $depreciation_accounts[$account_code]['counterpart_code'];
            $account_name = $depreciation_accounts[$account_code]['account_name'];
            $account_type = $depreciation_accounts[$account_code]['account_type'];
            $sub_account_type = $depreciation_accounts[$account_code]['sub_account_type'];
            $main_code = $depreciation_accounts[$account_code]['main_code'];
            $main_account = $depreciation_accounts[$account_code]['main_account'];
            $type = $depreciation_accounts[$account_code]['type'];
            $counterpart_code = $depreciation_accounts[$account_code]['counterpart_code'];
            $counterpart_name = $depreciation_accounts[$account_code]['counterpart_name'];
            

            $transaction = new stdClass();
            $transaction->account_type = $account_type;
            $transaction->sub_account_type = $sub_account_type;
            $transaction->main_code = $main_code;
            $transaction->main_account = $main_account;
            $transaction->type = $type;
            $transaction->counterpart_code = $counterpart_code;
            $transaction->counterpart_name = $counterpart_name;
            $transaction->transaction_entry_id = 0;
            $transaction->payee_id = 0;
            $transaction->branch_id = 0;
            $transaction->account_code = $account_code;
            $transaction->account_name = $account_name;
            $transaction->reference_no = 0;
            $transaction->transaction_no = $transaction_no;
            $transaction->transaction_type = 'DEPRECIATION';
            $transaction->transaction_date = $previous_month_last_date;
            $transaction->amount = $amount;
            $transaction->credit_amount = $credit_amount;
            $transaction->debit_amount = $debit_amount;
            $transaction->total_payment = 0;
            $transaction->amount_ex_tax = 0;
            $transaction->vat = 0;
            $transaction->wtax_code = 0;
            $transaction->wtax = 0;
            $transaction->user_id = 999;
            $transaction->status = 'CONFIRMED';
            $transaction->depreciation_date = $previous_month_last_date;
            $transaction->depreciated_id = 0;
            $transaction->useful_life = 0;
            $transaction->salvage_value = 0;
            array_push($transactions,$transaction);

            $account_code = $counterpart_code;

        } while(!$credit);     
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

