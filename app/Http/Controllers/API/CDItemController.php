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
        //$this->authorize('isAdmin');

        $transactionItem = TransactionItem::findOrFail($id);

        // Send Request
        $transactionItem->delete();
        
        return ['message' => 'Item Deleted'];
    }

    public function list(){
        if ($entry_id = \Request::get('entry_id')) {
            $transactionItems = TransactionItem::where(function($query) use ($entry_id){
                $query->where('transaction_entry_id',$entry_id);
                //$query->where('transaction_entry_id',$entry_id)->where('status','CONFIRMED');
            })->paginate(10);

        }else{
            $transactionItems = null;
        }
        return $transactionItems;
    }


    public function store_items(Request $request)
    {
        $data = json_decode($request['items']);
        foreach ($data->items as $item) {
            TransactionItem::create([
                'transaction_entry_id' => $item->transaction_entry_id,
                'transaction_no' => $item->transaction_no,
                'transaction_type' => $item->transaction_type,
                'account_code' => $item->account_code,
                'item' => $item->item,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'sub_total' => $item->sub_total,
                'tax_type' => $item->tax_type,
                'tax_excluded' => $item->tax_excluded,
                'vat' => $item->vat,
                'status' => $item->status
            ]);
        }

        return ['message' => 'Items saved.'];
    }

}
