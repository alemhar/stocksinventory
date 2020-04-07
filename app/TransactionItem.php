<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class TransactionItem extends Model
{
    public function getTable()
	{
		if(isset(Auth::user()->company_id)){
			$company_id = Auth::user()->company_id;
		} else {
			$company_id = '99';
		}
	    return 'transaction_entry_items_'.$company_id;
	}

	protected $fillable = [
						'transaction_entry_id',
						'transaction_no',
						'transaction_type',
						'account_code',
						'item',
						'quantity',
						'price', 
						'sub_total',
						'tax_type',
						'tax_excluded',
						'vat',
						'status'
					];
}

