<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\RunningAccount;

class RunningController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Request::get('start')) {
            $start = \Request::get('start');
        }
        if(\Request::get('end')) {
            $end = \Request::get('end');
        }

        if(\Request::get('transaction_date')) {
            $transaction_date = \Request::get('transaction_date');
        } else {
            $transaction_date = '';
        }

        $transaction = RunningAccount::where(function($query) use ($start,$end){
            $query->whereBetween('account_code', [$start, $end]);
            //$query->where('transaction_date','=', $transaction_date);
        })
        ->where('transaction_date','=', $transaction_date)
        ->groupBy('account_code')
        ->orderBy('account_code')
        ->selectRaw('sum(debit_amount) as debit,sum(credit_amount) as credit, account_name, id')
        ->get();

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
        //
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
