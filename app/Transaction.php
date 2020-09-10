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
			$company_id = '1';
		}
		
	    return 'transactions_'.$company_id;
	}
    public function payee()
	{
	    return $this->belongsTo('App\Payee', 'payee_id');
	} 
	   
    protected $fillable = [

						'account_type',
						'sub_account_type',
						'main_code',
						'main_account',
						'type',
						'payee_id',
						'account_code',
						'account_name',
						'reference_no',
						'transaction_no',
						'transaction_type', 
						'transaction_date',
						'transaction_entry_id',
						'amount',
						'credit_amount',
						'debit_amount',
						'amount_ex_tax',
						'vat',
						'wtax_code',
						'wtax',
						'canceled',
						'user_id',
						'branch_id',
						'status',
						'useful_life',
						'salvage_value',
						'depreciation_date',
						'depreciated_id',
						'counterpart_code',
						'counterpart_name',
						'description',
						'taxed',
						'tax_of_id',
						'tax_of_account',
						'entity_type'
    					];
}
