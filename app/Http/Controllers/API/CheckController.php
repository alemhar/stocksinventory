<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Check;

class CheckController extends Controller
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

    public function store_checks(Request $request)
    {
        $data = json_decode($request['checks']);

        $this->insert_checks($data->checks);

        return ['message' => 'Checkss posted.'];
    }

    public function insert_checks($checks)
    {
        
        foreach ($checks as $check) {
               
            Check::create([
                'check_no' => $check->check_no,
                'check_bank' => $check->check_bank,
                'check_bank_branch' => $check->check_bank_branch,
                'check_date' => $check->check_date,
                'check_amount' => $check->check_amount,
                'reference_no' => $check->reference_no,
                'transaction_no' => $check->transaction_no,
                'transaction_type' => $check->transaction_type,
                'transaction_date' => $check->transaction_date,
                'deposit_reference_no' => $check->deposit_reference_no,
                'deposit_date' => $check->deposit_date,
                'deposit_amount' => $check->deposit_amount,
                'status' => $check->status
            ]);

        }
    }


}
