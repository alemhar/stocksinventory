<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Transaction extends Model
{	
	public function getTable()
	{

		if(isset(Auth::user()->company_id)){
			$company_id = Auth::user()->company_id;
		} else {
			$company_id = '99';
		}
		
	    return 'transactions_'.$company_id;
	}

    public function payee()
	{
	    return $this->belongsTo('App\Payee', 'payee_id');
	}    

    protected $fillable = [
						'payee_id',
						'account_code',
						'account_name',
						'reference_no',
						'transaction_no',
						'transaction_type', 
						'transaction_date',
						'amount',
						'credit_amount',
						'debit_amount',
						'amount_ex_tax',
						'vat',
						'canceled',
						'user_id'
    					];
}
