<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Check extends Model
{
    protected $fillable = [
       'check_no', 
       'check_bank', 
       'check_bank_branch', 
       'check_date', 
       'check_amount',
       'reference_no',
       'transaction_no',
       'transaction_type',
       'transaction_date',
       'deposit_reference_no',
       'deposit_date',
       'deposit_amount'
    ];

    public function getTable()
	{
		if(isset(Auth::user()->company_id)){
			$company_id = Auth::user()->company_id;
		} else {
			$company_id = '99';
		}
	    return 'checks_'.$company_id;
	}
}



