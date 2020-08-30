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
                'salvage_value' => $transaction->salvage_value
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

        return ['message' => 'Transactions posted.'];
    }

    public function load_ledger($account_code)
    {
        
        $ledger = Transaction::where('account_code',$account_code)->paginate(10);
        return $ledger;
    }

}

              
               
                
                
                
                
                