<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Account;
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
                $transaction = \Request::get('transaction');

                if($transaction == 'CD'){
                    if($headerordetail == 'header'){
                        $accounts = Account::where('filter', '=', 1)
                        ->paginate(8);
                    } else {
                        $accounts = Account::where('filter', '>', 1)
                        ->where('filter', '<', 99)
                        ->paginate(8);
                    }
                }
                elseif($transaction == 'CR'){
                    if($headerordetail == 'header'){
                        $accounts = Account::where('filter', '<=', 2)
                        ->paginate(8);
                    } else {
                        $accounts = Account::where('filter', '>', 2)
                        ->where('filter', '<', 99)
                        ->paginate(8);
                    }
                } else {
                    $accounts = Account::latest()->paginate(10);
                }



            }else{
                $accounts = Account::latest()->paginate(10);
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

    public function search(){
        if ($search = \Request::get('q')) {
            $accounts = Account::where(function($query) use ($search){
                $query->where('account_code','LIKE',"%$search%")
                        ->orWhere('account_name','LIKE',"%$search%");
            })->paginate(10);

        }else{
            $accounts = Account::latest()->paginate(10);
        }
        return $accounts;
    }
}
