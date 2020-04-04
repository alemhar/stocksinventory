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
            'account_code' => 0,
            'account_name' => '',
            //'entry_name' => '',
            //'entry_description' => '',
            'branch_id' => 0,
            'branch_name' => '',
            'amount' => 0,
            'amount_ex_tax' => 0,
            'vat' => 0,
            'credit_amount' => 0,
            'debit_amount' => 0,
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
        $transactionEntry = TransactionEntry::findOrFail($id);

        // $this->validate($request,[
        //     'name' => 'required|string|max:191',
        //     'address' => 'required|string|max:191',
        //     'city' => 'required|string|max:191',
        //     'phone' => 'required|string|max:191'

        // ]);


        $transactionEntry->update($request->all());

        return ['message' => 'Entry updated!'];
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

    public function list(){

        if ($transaction_no = \Request::get('transaction_no')) {
            $transactionEntries = TransactionEntry::where(function($query) use ($entry_id){
                $query->where('transaction_no',$transaction_no);
            })->paginate(10);

        }else{
            $transactionEntries = null;//TransactionEntry::latest()->paginate(10);
        }
        return $transactionEntries;
    }
}
