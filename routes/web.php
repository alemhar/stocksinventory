<?php
use App\Transaction;
use App\Account;
use Carbon\Carbon;
use App\RunningAccount;
use App\DailyAccount;
use App\User;

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


/*
Route::get('/genapi', function () {

    $users = User::get();
    $token = '';
    foreach ($users as $user) {
        $token = Str::random(80);
        $user->api_token = hash('sha256', $token); 
        $user->save();
    }
    return 'Completed';
});
*/

Route::get('/adduser', function () {
    User::create([
        'name' => 'Company1',
        'email' => 'gracious2480@gmail.com',
        'password' => Hash::make('Az4fgh21!'),
    ]);
});

Route::get('/test', function () {
    $from_transaction_date = Carbon::create(2020, 9, 1);
    $to_transaction_date = Carbon::create(2020, 9, $from_transaction_date->daysInMonth);

    $start = 62010000;
    $end = 62210099;

    $transactions = DailyAccount::where(function($query) use ($start,$end){
        $query->whereBetween('account_code', [$start, $end]);
    })
    ->where(function($query) use ($from_transaction_date, $to_transaction_date){
        $query->whereBetween('transaction_date', [$from_transaction_date, $to_transaction_date]);
    })
    ->groupBy('account_code')
    ->orderBy('account_code')
    ->selectRaw('sum(debit_amount) as debit,sum(credit_amount) as credit, account_name, id')
    ->get();
    foreach($transactions as $transaction){

        $totalCredit +=  $transaction->debit - $transaction->credit;
    }

    return $totalCredit;
    //return $transactions;
});

Route::get('/test2', function () {
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
                'type' => $account->type, // GOODS|SERVICE|CAPITAL GOODS
                'counterpart_code' => $account->counterpart_code,
                'counterpart_name' => $account->counterpart_name
                ];
            }
        }
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
    
    //dd($depreciation_accounts);
    $depreciations = [];
    $depreciatiables = Transaction::whereBetween('account_code', [15011200, 15011550])
    ->where('amount', '>', DB::raw('total_deduction + salvage_value'))
    ->get();
    //dd($depreciatiables);

    $current_month = date('m');
    $current_year = date('Y');
    $date = Carbon::create($current_year, $current_month, 1);
    $year_month = $date->subMonth()->format('Y-m'); 
    $daysInMonth = $date->daysInMonth; 
    $previous_month_last_date = $year_month.'-'.$daysInMonth;

    
    foreach($depreciatiables as $depreciatiable){
        sleep(1);
        $transaction_no = time().'999';
        
        $depreciation = 0;
        // This script will run every weekend.
        // Check if entry has already been depreciated last month, if yes then skip.    
        $depreciation_entry = Transaction::where([
            'depreciated_id' => $depreciatiable->id,
            'transaction_type' => 'DEPRECIATION',
            'depreciation_date' => $previous_month_last_date
        ])->get();
        if (count($depreciation_entry) > 0) {
            continue;
        }    
        $depreciated_id = $depreciatiable->id;
        
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
        $temp = [];
        array_push($temp,$account_code);  
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

            array_push($temp,$account_code); 
            
            // Get the counter part of the initiating account title
            $account_name = $depreciation_accounts[$account_code]['account_name'];
            $account_code = $depreciation_accounts[$account_code]['counterpart_code'];
              
            try {    
            //return $depreciation_accounts;
            $account_type = $depreciation_accounts[$account_code]['account_type'];
            $sub_account_type = $depreciation_accounts[$account_code]['sub_account_type'];
            $main_code = $depreciation_accounts[$account_code]['main_code'];
            $main_account = $depreciation_accounts[$account_code]['main_account'];
            $type = $depreciation_accounts[$account_code]['type'];

            $counterpart_code = $depreciation_accounts[$account_code]['counterpart_code'];
            $counterpart_name = $depreciation_accounts[$account_code]['counterpart_name'];
            
            $transaction = new \stdClass();
            $transaction->account_type = $account_type;
            $transaction->sub_account_type = $sub_account_type;
            $transaction->main_code = $main_code;
            $transaction->main_account = $main_account;
            $transaction->type = $type;
            $transaction->transaction_entry_id = 0;
            $transaction->payee_id = 0;
            $transaction->branch_id = 0;
            $transaction->account_code = $account_code;
            $transaction->account_name = $account_name;
            $transaction->reference_no = $transaction_no;
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
            $transaction->depreciated_id = $depreciated_id;
            $transaction->useful_life = 0;
            $transaction->salvage_value = 0;
            $transaction->taxed = 'NA';
            $transaction->tax_of_id = 0;
            $transaction->tax_of_account = 0;
            $transaction->entity_type = 'NA';
            $transaction->description = 'Depreciation';
            
            array_push($transactions,$transaction);
            
            //$account_code = $counterpart_code;
            $transaction = null;
            } catch(Exception $e) {
                dd($temp);
            }
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
              
// with sub domain
Route::get('{path}','HomeController@index')->where( 'path', '([A-z\d\-\/_.]+)?' );

// root domain
//Route::get('{path}','HomeController@index')->where( 'path', '([A-z\d-/_.]+)?' );

