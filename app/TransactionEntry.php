<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TransactionEntry extends Model
{
    public function getTable()
	{
		if(isset(Auth::user()->company_id)){
			$company_id = Auth::user()->company_id;
		} else {
			$company_id = '99';
		}
	    return 'transaction_entries_'.$company_id;
	}

	protected $fillable = [
						'transaction_id',
						'transaction_no',
						'transaction_type',
						'account_code',
						'account_name',
						'entry_name', 
						'entry_description',
						'branch_id',
						'branch_name',
						'amount',
						'amount_ex_tax',
						'vat',
						'credit_amount',
						'debit_amount',
						'transaction_date',
						'canceled'
					];

	

}
