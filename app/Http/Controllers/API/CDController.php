<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transaction;

class CDController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
        //
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
}
