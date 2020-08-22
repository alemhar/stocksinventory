<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Account;
use App\Ledger;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Gate::allows('isAdmin') || \Gate::allows('isUser')) {
            
            if ($headerordetail = \Request::get('headerordetail')) {
                $transaction_type = \Request::get('transaction_type');

                if($transaction_type == 'CD'){
                    if($headerordetail == 'header'){
                        $accounts = Account::where('filter', '=', 1)
                        ->paginate(8);
                    } else {
                        $accounts = Account::where('filter', '>', 1)
                        ->where('filter', '<', 99)
                        ->paginate(8);
                    }
                }
                elseif($transaction_type == 'CR'){
                    if($headerordetail == 'header'){
                        $accounts = Account::where('filter', '<=', 2)
                        ->paginate(8);
                    } else {
                        $accounts = Account::where('filter', '>', 2)
                        ->where('filter', '<', 99)
                        ->paginate(8);
                    }
                }
                elseif($transaction_type == 'SALES'){
                    if($headerordetail == 'header'){
                        $accounts = Account::where('filter', '=', 4)
                        ->paginate(8);
                    } else {
                        $accounts = Account::where('filter', '<>', 4)
                        ->where('filter', '<', 99)
                        ->paginate(8);
                    }
                }
                elseif($transaction_type == 'PURCHASE'){
                    if($headerordetail == 'header'){
                        $accounts = Account::where('filter', '=', 5)
                        ->paginate(8);
                    } else {
                        $accounts = Account::where('filter', '<>', 5)
                        ->where('filter', '<', 99)
                        ->paginate(8);
                    }
                } else {
                    // 'LEDGER'
                    $accounts = Account::latest()->where('filter', '<', 99)->paginate(10);
                }

            }else{
                $accounts = Account::latest()->where('filter', '<', 99)->paginate(10);
            }
            return $accounts;

            
        }    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

       
    }


    public function updateAccountInfo(Request $request){

        

       
    }

    public function account()
    {
        //return auth('api')->user();
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
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }

    //'api/searchAccount?q='+query+'&transaction_type=SALES&headerOrDetail='+headerOrDetail
    public function search(){
        if ($search = \Request::get('q')) {
            $transaction_type = \Request::get('transaction_type');
            $headerordetail = \Request::get('headerordetail');
            $accounts = Account::where(function($query) use ($search){
                $query->where('account_code','LIKE',"%$search%")
                        ->orWhere('account_name','LIKE',"%$search%");
            })->where(function($query) use ($transaction_type,$headerordetail){
            
                if($transaction_type == 'CD'){
                    if($headerordetail == 'header'){
                        $query->where('filter', '=', 1);
                    } else {
                        $query->where('filter', '>', 1)
                        ->where('filter', '<', 99);
                    }
                }
                elseif($transaction_type == 'CR'){
                    if($headerordetail == 'header'){
                        $query->where('filter', '<=', 2);
                    } else {
                        $query->where('filter', '>', 2)
                        ->where('filter', '<', 99);
                    }
                }elseif($transaction_type == 'SALES'){
                    if($headerordetail == 'header'){
                        $query->where('filter', '=', 4);
                    } else {
                        $query->where('filter', '<>', 4)
                        ->where('filter', '<', 99);
                    }
                } else {
                    $query->where('filter', '<', 99);
                }
            })->paginate(10);




        }else{
            $accounts = null;
        }
        return $accounts;
    }

    public function store_ledger(Request $request)
    {
        $data = json_decode($request['ledgers']);
        foreach ($data->ledgers as $ledger) {
            Ledger::create([
                'transaction_id' => $ledger->transaction_id,
                'transaction_no' => $ledger->transaction_no,
                'transaction_type' => $ledger->transaction_type,
                'account_code' => $ledger->account_code,
                'account_name' => $ledger->account_name,
                'transaction_date' => $ledger->transaction_date,
                'credit_amount' => $ledger->credit_amount,
                'debit_amount' => $ledger->debit_amount
            ]);
        }

        return ['message' => 'Ledger posted.'];
    }

    // Not in use
    public function load_ledger($account_code)
    {
        
        $ledger = Ledger::latest('updated_at')->where('account_code',$account_code)->paginate(10);
        return $ledger;
    }

}


