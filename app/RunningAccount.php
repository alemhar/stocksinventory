<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class RunningAccount extends Model
{
    protected $fillable = [
        'account_type',
        'sub_account_type',
        'main_code',
        'main_account',
        'account_code',
        'account_name',
        'transaction_date',
        //'amount',
        'credit_amount',
        'debit_amount',
        'type',
        'user_id',
        'status',
        'taxed',
        'entity_type',
        'transaction_type'
        ];

    public function getTable()
	{

		if(isset(Auth::user()->company_id)){
			$company_id = Auth::user()->company_id;
		} else {
			$company_id = '1';
		}
		
	    return 'running_accounts_'.$company_id;
    }
}
