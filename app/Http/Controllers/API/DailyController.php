<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DailyAccount;
use Carbon\Carbon;

class DailyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Request::get('report_type')) {
            $report_type = \Request::get('report_type');
        }
        if(\Request::get('from_transaction_date')) {
            $from_transaction_date = \Request::get('from_transaction_date');
        } else {
            $from_transaction_date = '';
        }
        if(\Request::get('to_transaction_date')) {
            $to_transaction_date = \Request::get('to_transaction_date');
        } else {
            $to_transaction_date = '';
        }

        if(\Request::get('transaction_date')) {
            $transaction_date = \Request::get('transaction_date');
        } else {
            $transaction_date = '';
        }

        if(\Request::get('sub_account_type')) {
            $sub_account_type = \Request::get('sub_account_type');
            $sub_account_type = str_replace('_', ' ', $sub_account_type);
        } else {
            $sub_account_type = '';
        }

        $transaction = DailyAccount::where(function($query) use ($from_transaction_date,$to_transaction_date){
            $query->whereBetween('transaction_date', [$from_transaction_date, $to_transaction_date]);
            //$query->where('transaction_date','=', $transaction_date);
        })
        ->where('sub_account_type',$sub_account_type)
        ->groupBy('account_code')
        ->orderBy('account_code')
        ->selectRaw('sum(debit_amount) as debit,sum(credit_amount) as credit, account_name, id')
        ->get();


        

        //->pluck('debit','credit','account_name');
        

        /* Working for PrintTest.vue
        $transaction = DailyAccount::
        groupBy('account_code')
        ->selectRaw('sum(debit_amount) as debit,sum(credit_amount) as credit, account_name, id')
        ->get();
        */

        /*
        $transaction = DailyAccount::where(function($query) use ($transaction_date){
            $query->where('transaction_date','=', $transaction_date);
        })
        ->groupBy('account_code')
        ->selectRaw('sum(debit_amount) as debit,sum(credit_amount) as credit, account_name, id')
        ->get();

        */

        //->pluck('debit','credit','account_name');
        

        /*
        $transaction = DailyAccount::where(function($query) use ($transaction_date){
            $query->where('transaction_date','=',$transaction_date);
        })->paginate(10);
        */    
        return $transaction;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function runninng()
    {

        if(\Request::get('start')) {
            $start = \Request::get('start');
        }
        if(\Request::get('end')) {
            $end = \Request::get('end');
        }

        if(\Request::get('transaction_date')) {
            $transaction_date = \Request::get('transaction_date');
        } else {
            $transaction_date = '';
        }

        $transaction = DailyAccount::where(function($query) use ($start,$end){
            $query->whereBetween('account_code', [$start, $end]);
            //$query->where('transaction_date','=', $transaction_date);
        })
        ->where('transaction_date','<=', $transaction_date)
        //->groupBy('account_code')
        ->groupBy('account_code')
        ->orderBy('account_code')
        ->selectRaw('sum(debit_amount) as debit,sum(credit_amount) as credit, account_name, id')
        ->get();

        return $transaction;
    }

    public function monthlyTaxDeclaration()
    {
        $totalSalesAndRevenues = 0;
        $totalOutputTax = 0;
        $totalInputTax = 0;
        $salesAndRevenuesPrivate = 0;
        $salesAndRevenuesGov = 0;
        $salesAndRevenuesExempt = 0;

        $inputTaxServices = 0;
        $inputTaxGoods = 0;
        $outputTaxPrivate = 0;
        $outputTaxGov = 0;

        if(\Request::get('transaction_date')) {
            $transaction_date = \Request::get('transaction_date');
        } else {
            $transaction_date = '';
        }

        $to_transaction_date = Carbon::create($transaction_date);
        
        $from_transaction_date = Carbon::create($to_transaction_date->year, $to_transaction_date->month, 1);

        // Sales and Revenues
        $start = 41010000;
        $end = 41010099;
        
        $totalSalesAndRevenues = $this->totalCredit($start, $end, $from_transaction_date, $to_transaction_date);
        
        /*
        $transactions = DailyAccount::where(function($query) use ($start,$end){
            $query->whereBetween('account_code', [$start, $end]);
        })
        ->where(function($query) use ($from_transaction_date, $to_transaction_date){
            $query->whereBetween('transaction_date', [$from_transaction_date, $to_transaction_date]);
        })
        ->groupBy('account_code')
        ->orderBy('account_code')
        ->selectRaw('sum(debit_amount) as debit,sum(credit_amount) as credit, account_name, id')
        ->get();
        foreach($transactions as $transaction){
            $totalSalesAndRevenues =  $transaction->credit - $transaction->debit;
        }
        */

        // Output Tax Private
        $start = 21051100;
        $end = 21051199;
        
        $transactions = DailyAccount::where(function($query) use ($start,$end){
            $query->whereBetween('account_code', [$start, $end]);
        })
        ->where(function($query) use ($from_transaction_date, $to_transaction_date){
            $query->whereBetween('transaction_date', [$from_transaction_date, $to_transaction_date]);
        })
        ->where('entity_type','=','PRIVATE')
        ->groupBy('account_code')
        ->orderBy('account_code')
        ->selectRaw('sum(debit_amount) as debit,sum(credit_amount) as credit, account_name, id')
        ->get();
        foreach($transactions as $transaction){
            $outputTaxPrivate =  $transaction->credit - $transaction->debit;
        }

        // Output Tax Gov
        $start = 21051100;
        $end = 21051199;
        
        $transactions = DailyAccount::where(function($query) use ($start,$end){
            $query->whereBetween('account_code', [$start, $end]);
        })
        ->where(function($query) use ($from_transaction_date, $to_transaction_date){
            $query->whereBetween('transaction_date', [$from_transaction_date, $to_transaction_date]);
        })
        ->where('entity_type','<>','PRIVATE')
        ->groupBy('account_code')
        ->orderBy('account_code')
        ->selectRaw('sum(debit_amount) as debit,sum(credit_amount) as credit, account_name, id')
        ->get();
        foreach($transactions as $transaction){
            $outputTaxGov =  $transaction->credit - $transaction->debit;
        }

        if($outputTaxPrivate > 0){
            $salesAndRevenuesPrivate = round(($outputTaxPrivate / 0.12 ) + 0.01, 2) ;
            //$salesAndRevenuesPrivate = $outputTaxPrivate / 0.12;
        }

        if($outputTaxGov > 0){
            $salesAndRevenuesGov = round($outputTaxGov / 0.12, 2);
        }


        // Input Tax Capital Goods and Goods
        $start = 11051100;
        $end = 11051199;
        
        $transactions = DailyAccount::where(function($query) use ($start,$end){
            $query->whereBetween('account_code', [$start, $end]);
        })
        ->where(function($query) use ($from_transaction_date, $to_transaction_date){
            $query->whereBetween('transaction_date', [$from_transaction_date, $to_transaction_date]);
        })
        ->where('type','<>','SERVICE')
        ->where('type','<>','NA')
        ->groupBy('account_code')
        ->orderBy('account_code')
        ->selectRaw('sum(debit_amount) as debit,sum(credit_amount) as credit, account_name, id')
        ->get();
        foreach($transactions as $transaction){
            $inputTaxGoods =  $transaction->credit - $transaction->debit;
        }

        

        // Input Tax Services
        $start = 11051100;
        $end = 11051199;
        
        $transactions = DailyAccount::where(function($query) use ($start,$end){
            $query->whereBetween('account_code', [$start, $end]);
        })
        ->where(function($query) use ($from_transaction_date, $to_transaction_date){
            $query->whereBetween('transaction_date', [$from_transaction_date, $to_transaction_date]);
        })
        ->where('type','=','SERVICE')
        ->where('type','<>','NA')
        ->groupBy('account_code')
        ->orderBy('account_code')
        ->selectRaw('sum(debit_amount) as debit,sum(credit_amount) as credit, account_name, id')
        ->get();
        foreach($transactions as $transaction){
            $inputTaxServices =  $transaction->credit - $transaction->debit;
        }


        // Total Petty Cash Fund, Cash In Bank - Bank, Inventory

 
        $start = 11011200;
        $end = 11011499;
        
        $transactions = DailyAccount::where(function($query) use ($start,$end){
            $query->whereBetween('account_code', [$start, $end]);
        })
        ->where(function($query) use ($from_transaction_date, $to_transaction_date){
            $query->whereBetween('transaction_date', [$from_transaction_date, $to_transaction_date]);
        })
        ->groupBy('account_code')
        ->orderBy('account_code')
        ->selectRaw('sum(debit_amount) as debit,sum(credit_amount) as credit, account_name, id')
        ->get();
        foreach($transactions as $transaction){
            $totalPettyBankInventory =  $transaction->credit - $transaction->debit;
        }

        // Total Petty Cash Fund, Cash In Bank - Bank, Inventory
        $start = 11031300;
        $end = 11011499;
        
        $transactions = DailyAccount::where(function($query) use ($start,$end){
            $query->whereBetween('account_code', [$start, $end]);
        })
        ->where(function($query){
            $query->where('transaction_date', [$from_transaction_date, $to_transaction_date]);
        })
        ->where(function($query) use ($from_transaction_date, $to_transaction_date){
            $query->whereBetween('transaction_date', [$from_transaction_date, $to_transaction_date]);
        })
        ->groupBy('account_code')
        ->orderBy('account_code')
        ->selectRaw('sum(debit_amount) as debit,sum(credit_amount) as credit, account_name, id')
        ->get();
        foreach($transactions as $transaction){
            $totalPettyBankInventory =  $transaction->credit - $transaction->debit;
        }


        


        $totalPettyBankInventory = 0;
        
        $salesAndRevenuesExempt = round($totalSalesAndRevenues - $salesAndRevenuesPrivate - $salesAndRevenuesGov,2);
        $totalOutputTax = $outputTaxPrivate + $outputTaxGov;

        return json_encode(['total_sales_revenue' => $totalSalesAndRevenues,
                            'output_tax_private' => $outputTaxPrivate,
                            'output_tax_gov' => $outputTaxGov,
                            'sales_revenues_private' => $salesAndRevenuesPrivate,
                            'sales_revenues_gov' => $salesAndRevenuesGov,
                            'sales_revenues_exempt' => $salesAndRevenuesExempt,
                            'totalOutputTax' => $totalOutputTax,
                            'inputTaxGoods' => $inputTaxGoods,
                            'inputTaxServices' => $inputTaxServices
                            ]);

    }
    
    public function totalCredit($start, $end, $from_transaction_date, $to_transaction_date){
        
        $totalCredit = 0;

        $transactions = DailyAccount::where(function($query) use ($start,$end){
            $query->whereBetween('account_code', [$start, $end]);
        })
        ->where(function($query) use ($from_transaction_date, $to_transaction_date){
            $query->whereBetween('transaction_date', [$from_transaction_date, $to_transaction_date]);
        })
        ->groupBy('account_code')
        ->orderBy('account_code')
        ->selectRaw('sum(debit_amount) as debit,sum(credit_amount) as credit, account_name, id')
        ->get();
        foreach($transactions as $transaction){
            $totalCredit =  $transaction->credit - $transaction->debit;
        }

        return $totalCredit;
    }
    

}
