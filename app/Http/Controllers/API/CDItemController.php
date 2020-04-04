<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TransactionItem;

class CDItemController extends Controller
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
        return TransactionItem::create([
            'transaction_entry_id' => $request['transaction_entry_id'],
            'transaction_type' => $request['transaction_type'],
            'transaction_no' => $request['transaction_no'],
            'account_code' => $request['account_code'],

            'item' => '',
            'quantity' => 0,
            'price' => 0,
            'sub_total' => 0,
            'tax_type' => '',
            'tax_excluded' => 0,
            'vat' => 0
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
        
        $transactionItem = TransactionItem::findOrFail($id);

        $transactionItem->update($request->all());

        return ['message' => 'Item updated!'];
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
        if ($entry_id = \Request::get('entry_id')) {
            $transactionItems = TransactionItem::where(function($query) use ($entry_id){
                $query->where('transaction_entry_id',$entry_id);
            })->paginate(10);

        }else{
            $transactionItems = TransactionItem::latest()->paginate(10);
        }
        return $transactionItems;
    }
}
