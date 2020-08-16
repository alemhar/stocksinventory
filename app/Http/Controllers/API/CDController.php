<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transaction;
use App\TransactionEntry;
use App\TransactionItem;

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
        $transaction = Transaction::where(function($query){
            $query->where('amount','>','total_payment')
            ->where('transaction_type','PURCHASE')
            ->where('status','CONFIRMED');
        })->paginate(10);
        return $transaction;
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

}
