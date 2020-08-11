<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Ledger extends Model
{
    public function getTable()
	{
		if(isset(Auth::user()->company_id)){
			$company_id = Auth::user()->company_id;
		} else {
			$company_id = '99';
		}
	    return 'ledgers_'.$company_id;
    }
    
    protected $fillable = [
        'transaction_id', 
        'transaction_no', 
        'transaction_type', 
        'account_code', 
        'account_name',
        'transaction_date',
        'credit_amount',
        'debit_amount'
    ];
}
