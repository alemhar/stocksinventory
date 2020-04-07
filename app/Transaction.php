<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Auth;

class Transaction extends Model
{	

	$user = Auth::user();
	public function getTable()
	{

		if(isset($user->company_id)){
			$company_id = $user->company_id;
		} else {
			$company_id = '99';
		}
	    return 'transactions_'.$company_id;
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
