<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TransactionEntry;

class CDEntryController extends Controller
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
        return TransactionEntry::create([
            'transaction_id' => $request['transaction_id'],
            'transaction_no' => $request['transaction_no'],
            'transaction_type' => $request['transaction_type'],
            'account_code' => '',
            'account_name' => '',
            'entry_name' => '',
            'entry_description' => '',
            'branch_id' => 0,
            'branch_name' => '',
            'amount' => 0,
            'amount_ex_tax' => 0,
            'vat' => 0,
            'credit_amount' => '',
            'debit_amount' => '',
            'transaction_date' =>  $request['transaction_date']
            


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
        //
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
}
