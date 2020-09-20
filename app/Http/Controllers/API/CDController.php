<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transaction;
use App\TransactionEntry;
use App\TransactionItem;
use App\DailyAccount;
use App\RunningAccount;
use DB;

class CDController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return Transaction::with('payee')->latest()->where('status','CONFIRMED')->paginate(3);

        if ($transaction_type = \Request::get('transaction_type')) {
            $transaction = Transaction::with('payee')->where(function($query) use ($transaction_type){
                $query->where('transaction_type',$transaction_type)->where('status','CONFIRMED');
            })->paginate(10);

        }else{
            $transaction = Transaction::latest()->paginate(10);
        }
        return $transaction;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        // Not is Use | Do not use this store method
        // With holding tax is from Sales Entry Form
        if(!isset($request['wtax_code'])){
            $wtax_code = 'NA';
            $wtax = 0;
        } else {
            $wtax_code = $request['wtax_code'];
            $wtax = $request['wtax'];
        }

        return Transaction::create([
            'payee_id' => $request['payee_id'],
            'account_code' => $request['account_code'],
            'account_name' => $request['account_name'],
            'reference_no' => $request['reference_no'],
            'transaction_no' => $request['transaction_no'],
            'transaction_type' => $request['transaction_type'], 
            'transaction_date' => $request['transaction_date'],
            'amount' => $request['amount'],
            'credit_amount' => $request['credit_amount'],
            'debit_amount' => $request['debit_amount'],
            'amount_ex_tax' => $request['amount_ex_tax'],
            'vat' => $request['vat'],
            'wtax_code' => $wtax_code,
            'wtax' => $wtax,
            'canceled' => $request['canceled'],
            'user_id' => $request['user_id']
        ]);
    }




    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        // $this->validate($request,[
        //     'name' => 'required|string|max:191',
        //     'address' => 'required|string|max:191',
        //     'city' => 'required|string|max:191',
        //     'phone' => 'required|string|max:191'

        // ]);


        $transaction->update($request->all());

        //return $request['wtax_code'];    
        return ['message' => 'Transaction updated!'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function cancelTransaction($transaction_no){

        Transaction::where('transaction_no', $transaction_no)
          ->update(['canceled' => 1]);

        return ['message' => 'Transaction # '.$transaction_no.' canceled'];
    }

    public function confirmTransaction($transaction_no){

        Transaction::where('transaction_no', $transaction_no)
          ->update(['status' => 'CONFIRMED']);
        TransactionEntry::where('transaction_no', $transaction_no)
          ->update(['status' => 'CONFIRMED']);
        TransactionItem::where('transaction_no', $transaction_no)
          ->update(['status' => 'CONFIRMED']);    

    }

    public function search(){
        // Add transaction type to filter (Header/Detail, CR/CD/SALES)
        if ($transaction_no = \Request::get('transaction_no')) {
            $transaction_type = \Request::get('transaction_type');
            $transaction = Transaction::where(function($query) use ($transaction_no,$transaction_type){
                $query->where('transaction_no','LIKE',"%$transaction_no%")->where('transaction_type','=',$transaction_type);
            })->paginate(10);

        }else{
            $transaction = Transaction::latest()->paginate(10);
        }
        return $transaction;
    }

    public function list(){
        if($payee_id = \Request::get('payee_id')) {
            $transaction = Transaction::where(function($query) use ($payee_id){
                $query->where('amount','>','total_payment')
                ->where('transaction_type','PURCHASE')
                ->where('payee_id',$payee_id)
                ->whereBetween('account_code', [21010000, 21051099])    
                ->where('status','CONFIRMED');
            })->paginate(10);
  
        } else{
            $transaction = null;
        }
        return $transaction;
    }

    public function listSales(){
        if($payee_id = \Request::get('payee_id')) {
            $transaction = Transaction::where(function($query) use ($payee_id){
                $query->where('amount','>','total_collection')
                ->where('transaction_type','SALES')
                ->where('payee_id',$payee_id)
                ->whereBetween('account_code', [11020000, 11039999])
                ->where('status','CONFIRMED');
            })->paginate(10);
  
        } else{
            $transaction = null;
        }
        return $transaction;
    }


    public function paymentList(){
        $account_code = \Request::get('account_code');
        $payee_id = \Request::get('payee_id');

        if($payee_id) {
            $transactions = Transaction::where(function($query) use ($payee_id, $account_code){
                $query->where('payee_id',$payee_id)
                ->where('status','CONFIRMED')
                ->where('account_code',$account_code)
                ->where('transaction_type','PAYMENT');
            })->paginate(10);
        } else{
            $transactions = null;
        }
        return $transactions;
    }


    public function collectionList(){
        $account_code = \Request::get('account_code');
        $payee_id = \Request::get('payee_id');

        if($payee_id) {
            $transactions = Transaction::where(function($query) use ($payee_id, $account_code){
                $query->where('payee_id',$payee_id)
                ->where('status','CONFIRMED')
                ->where('account_code',$account_code)
                ->where('transaction_type','COLLECTION');
            })->paginate(10);
        } else{
            $transactions = null;
        }
        return $transactions;
    }

    public function record_payment(Request $request)
    {
        
        $data = json_decode($request['payments']);
        
        foreach ($data->payments as $payment) {
            $transaction = Transaction::find($payment->id);
            $transaction->total_payment = $payment->total_payment;
            $transaction->save();
        }

        return ['message' => 'Payment posted.'];
    }

    public function record_collection(Request $request)
    {
        
        $data = json_decode($request['sales']);
        
        foreach ($data->sales as $sale) {
            $transaction = Transaction::find($sale->id);
            $transaction->total_collection = $sale->total_collection;
            $transaction->save();
        }

        return ['message' => 'Collection posted.'];
        //return $request;
    }

    public function store_transactions(Request $request)
    {
        $data = json_decode($request['transactions']);

        $this->insert_transactions($data->transactions);

        /*
        foreach ($data->transactions as $transaction) {
               
            Transaction::create([
                'payee_id' => $transaction->payee_id,
                'branch_id' => $transaction->branch_id,
                'account_type' => $transaction->account_type,
                'sub_account_type' => $transaction->sub_account_type,
                'main_code' => $transaction->main_code,
                'main_account' => $transaction->main_account,
                'account_code' => $transaction->account_code,
                'account_name' => $transaction->account_name,
                'reference_no' => $transaction->reference_no,
                'transaction_no' => $transaction->transaction_no,
                'transaction_type' => $transaction->transaction_type,
                'transaction_date' => $transaction->transaction_date,
                'transaction_entry_id' => $transaction->transaction_entry_id,
                'amount' => $transaction->amount,
                'credit_amount' => $transaction->credit_amount,
                'debit_amount' => $transaction->debit_amount,
                'total_payment' => $transaction->total_payment,
                'amount_ex_tax' => $transaction->amount_ex_tax,
                'vat' => $transaction->vat,
                'wtax_code' => $transaction->wtax_code,
                'wtax' => $transaction->wtax,
                'type' => $transaction->type,
                'user_id' => $transaction->user_id,
                'status' => $transaction->status,
                'useful_life' => $transaction->useful_life,
                'salvage_value' => $transaction->salvage_value,
                'depreciation_date' => $transaction->depreciation_date,
                'depreciated_id' => $transaction->depreciated_id
            ]);

            
            $dailyAccount = DailyAccount::firstOrNew([
                'account_code' => $transaction->account_code,
                'transaction_date' => $transaction->transaction_date
            ]); 
            
            if ($dailyAccount->exists) {
                //$dailyAccount->amount = $transaction->amount;
                $dailyAccount->credit_amount += $transaction->credit_amount;
                $dailyAccount->debit_amount +=  $transaction->debit_amount;
                
            } 
            else {
                $dailyAccount->account_type = $transaction->account_type;
                $dailyAccount->sub_account_type = $transaction->sub_account_type;
                $dailyAccount->main_code = $transaction->main_code;
                $dailyAccount->main_account = $transaction->main_account;
                //account_code
                $dailyAccount->account_name = $transaction->account_name;
                //transaction_date
                //$dailyAccount->amount = $transaction->credit_amount;
                $dailyAccount->credit_amount = $transaction->credit_amount;
                $dailyAccount->debit_amount =  $transaction->debit_amount;
                $dailyAccount->type = $transaction->type;
                $dailyAccount->user_id = $transaction->user_id;
                $dailyAccount->status = $transaction->status;
            }    
            $dailyAccount->save();

            
            $runningAccount = RunningAccount::firstOrNew([
                'account_code' => $transaction->account_code,
                'transaction_date' => $transaction->transaction_date
            ]); 
            
            if ($runningAccount->exists) {
                //$dailyAccount->amount = $transaction->amount;
                $runningAccount->credit_amount += $transaction->credit_amount;
                $runningAccount->debit_amount +=  $transaction->debit_amount;
                
            } 
            else {
                $runningAccount->account_type = $transaction->account_type;
                $runningAccount->sub_account_type = $transaction->sub_account_type;
                $runningAccount->main_code = $transaction->main_code;
                $runningAccount->main_account = $transaction->main_account;
                //account_code
                $runningAccount->account_name = $transaction->account_name;
                //transaction_date
                //$dailyAccount->amount = $transaction->credit_amount;
                $runningAccount->credit_amount = $transaction->credit_amount;
                $runningAccount->debit_amount =  $transaction->debit_amount;
                $runningAccount->type = $transaction->type;
                $runningAccount->user_id = $transaction->user_id;
                $runningAccount->status = $transaction->status;
            }    
            $runningAccount->save();

            RunningAccount::where('account_code', $transaction->account_code)
            ->where('transaction_date', '>' , $transaction->transaction_date)
            ->update([
                'credit_amount' => DB::raw( 'credit_amount +' . $transaction->credit_amount),
                'debit_amount' => DB::raw( 'debit_amount +' . $transaction->debit_amount)
            ]);
        }
        */
        return ['message' => 'Transactions posted.'];
    }

    public function load_ledger($account_code)
    {
        
        $ledger = Transaction::where('account_code',$account_code)->paginate(10);
        return $ledger;
    }

    public function insert_transactions($transactions)
    {
        
        foreach ($transactions as $transaction) {
               
            Transaction::create([
                'payee_id' => $transaction->payee_id,
                'entity_type' => $transaction->entity_type,
                'branch_id' => $transaction->branch_id,
                'account_type' => $transaction->account_type,
                'sub_account_type' => $transaction->sub_account_type,
                'main_code' => $transaction->main_code,
                'main_account' => $transaction->main_account,
                'account_code' => $transaction->account_code,
                'account_name' => $transaction->account_name,
                'reference_no' => $transaction->reference_no,
                'transaction_no' => $transaction->transaction_no,
                'transaction_type' => $transaction->transaction_type,
                'transaction_date' => $transaction->transaction_date,
                'transaction_entry_id' => $transaction->transaction_entry_id,
                'amount' => $transaction->amount,
                'credit_amount' => $transaction->credit_amount,
                'debit_amount' => $transaction->debit_amount,
                'total_payment' => $transaction->total_payment,
                'amount_ex_tax' => $transaction->amount_ex_tax,
                'vat' => $transaction->vat,
                'wtax_code' => $transaction->wtax_code,
                'wtax' => $transaction->wtax,
                'type' => $transaction->type, // GOODS|SERVICE|CAPITAL GOODS
                'user_id' => $transaction->user_id,
                'status' => $transaction->status,
                'useful_life' => $transaction->useful_life,
                'salvage_value' => $transaction->salvage_value,
                'depreciation_date' => $transaction->depreciation_date,
                'depreciated_id' => $transaction->depreciated_id,
                'taxed' => $transaction->taxed, // YES|NO (For main account)
                'tax_of_id' => $transaction->tax_of_id, // id of taxed account (For input tax)
                'tax_of_account' => $transaction->tax_of_account, // main account of tax (For input tax)
                'description' => $transaction->description
            ]);

            
            $dailyAccount = DailyAccount::firstOrNew([
                'account_code' => $transaction->account_code,
                'transaction_date' => $transaction->transaction_date,
                'transaction_type' => $transaction->transaction_type, // CR|CD|PURCHASE|SALES|PAYMENT|COLLECTION|DEPRECIATION
                'entity_type' => $transaction->entity_type, // Private|Goverment
                'type' => $transaction->type, // GOODS|SERVICE|CAPITAL GOODS|NA
                'taxed' => $transaction->taxed // YES|NO 
            ]); 
            
            if ($dailyAccount->exists) {
                //$dailyAccount->amount = $transaction->amount;
                $dailyAccount->credit_amount += $transaction->credit_amount;
                $dailyAccount->debit_amount +=  $transaction->debit_amount;
                
            } 
            else {
                $dailyAccount->account_type = $transaction->account_type;
                $dailyAccount->sub_account_type = $transaction->sub_account_type;
                $dailyAccount->main_code = $transaction->main_code;
                $dailyAccount->main_account = $transaction->main_account;
                //account_code
                $dailyAccount->account_name = $transaction->account_name;
                //transaction_date
                //$dailyAccount->amount = $transaction->credit_amount;
                $dailyAccount->credit_amount = $transaction->credit_amount;
                $dailyAccount->debit_amount =  $transaction->debit_amount;
                //$dailyAccount->type = $transaction->type; // GOODS|SERVICE|CAPITAL GOODS|NA
                $dailyAccount->user_id = $transaction->user_id;
                $dailyAccount->status = $transaction->status;
            }    
            $dailyAccount->save();

            
            // Update record equal transaction date.
            $runningAccount = RunningAccount::firstOrNew([
                'account_code' => $transaction->account_code,
                'transaction_date' => $transaction->transaction_date,
                'transaction_type' => $transaction->transaction_type, // CR|CD|PURCHASE|SALES|PAYMENT|COLLECTION|DEPRECIATION
                'entity_type' => $transaction->entity_type, // Private|Goverment
                'type' => $transaction->type, // GOODS|SERVICE|CAPITAL GOODS|NA
                'taxed' => $transaction->taxed // YES|NO 
            ]); 
            
            if ($runningAccount->exists) {
                $runningAccount->credit_amount += $transaction->credit_amount;
                $runningAccount->debit_amount +=  $transaction->debit_amount;
                
            } 
            else {
                $runningAccount->account_type = $transaction->account_type;
                $runningAccount->sub_account_type = $transaction->sub_account_type;
                $runningAccount->main_code = $transaction->main_code;
                $runningAccount->main_account = $transaction->main_account;
                $runningAccount->account_name = $transaction->account_name;
                $runningAccount->credit_amount = $transaction->credit_amount;
                $runningAccount->debit_amount =  $transaction->debit_amount;
                $runningAccount->type = $transaction->type; // GOODS|SERVICE|CAPITAL GOODS
                $runningAccount->user_id = $transaction->user_id;
                $runningAccount->status = $transaction->status;
                //$runningAccount->taxed = $transaction->taxed;
            }    
            $runningAccount->save();


            // Update all records later than transaction date.
            RunningAccount::where('account_code', $transaction->account_code)
            ->where('transaction_date', '>' , $transaction->transaction_date)
            ->where('transaction_type', '=' , $transaction->transaction_type)
            ->where('entity_type', '=' , $transaction->entity_type)
            ->where('type', '=' , $transaction->type) // GOODS|SERVICE|CAPITAL GOODS
            ->where('taxed', '=' , $transaction->taxed) // YES|NO
            ->update([
                'credit_amount' => DB::raw( 'credit_amount +' . $transaction->credit_amount),
                'debit_amount' => DB::raw( 'debit_amount +' . $transaction->debit_amount)
            ]);
        }
    }


    public function depreciate_transactions(){
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
    
            /* For future update (if count <> 2 sent notification) */    
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
            $depreciated_id = $depreciatiable->id;
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

                array_push($transactions,$transaction);
                $account_code = $counterpart_code;
                $transaction = null;
    
            } while(!$credit);

            $this->insert_transactions($transactions); 
            // total_deduction ******************* reflect deduction on the depreciatable account.. 

        }

    }

    public function load_transactions()
    {
        
        $transaction_type = \Request::get('transaction_type');
        $transaction_date = \Request::get('transaction_date');
        //return $transaction_type . ' ' .$transaction_date;

        // use when($transaction_type != 'ALL')

        
        if ($transaction_type != 'ALL') {
            $transactions = Transaction::where('transaction_date',$transaction_date)
            ->where('transaction_type',$transaction_type)
            ->groupBy('transaction_no')
            ->paginate(25);

        }else{
            $transactions = Transaction::where('transaction_date',$transaction_date)
            ->groupBy('transaction_no')
            ->paginate(25);
        }
        return $transactions;
    }


    public function get_transaction()
    {
        
        $transaction_no = \Request::get('transaction_no');
        //$transaction_date = \Request::get('transaction_date');
        //return $transaction_type . ' ' .$transaction_date;

        // use when($transaction_type != 'ALL')

        
            $transaction = Transaction::where('transaction_no',$transaction_no)
            ->paginate(25);

        
        return $transaction;
    }

    public function reverse_transaction()
    {
        
        $transaction_no = \Request::get('transaction_no');
        

        
            $transaction = Transaction::where('transaction_no',$transaction_no)
            ->update(['status' => 'REVERSE']);
            $transaction_items = TransactionItem::where('transaction_no',$transaction_no)
            ->update(['status' => 'REVERSE']);
        
            return ['message' => 'Transactions reversed.'];
    }


    
}

              
               
                
                
                
                
                