<template>

    <div class="container">
        <div class="row mt-1" v-if="$gate.isAdminOrUser()">
            <div class="col-md-12">
                <!-- div class="row mt-3">
                    <div class="col-4">
                        <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" for="inputGroupSelect01">Options</span>
                        </div>
                        <select v-model="report_type" class="custom-select" id="inputGroupSelect01">
                            <option value="IS" selected>Income Statement</option>
                            <option value="BS">Balance Sheet</option>
                        </select>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text inputGroup-sizing-default">Date</span>
                            </div>
                            <input  type="date" v-model="transaction_date" class="form-control col-12" id="inputDate" placeholder="Date">
                        </div>
                        <button @click="generateReport" class="btn btn-primary float-right">Generate</button>
                    </div>
                </div -->
                    
                <div class="box mt-4">
                    <div class="box-header">
                        <h3 class="box-title">Reports</h3>
                    </div>  
                    <div  v-show="true" class="box box-warning mt-2">
                        <div class="col-md-12">
                        <div class="">

                            <!-- /.box-header -->
                            <div id="card-list" class="box-body no-padding row">
                                <div class="col-4">
                                    <div class="card">
                                    <div class="card-header">
                                        Income Statement
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Generate Income Statement</h5>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text inputGroup-sizing-default">From:</span>
                                            </div>
                                            <input  type="date" v-model="from_transaction_date" class="form-control col-12" id="inputFromDate" placeholder="Date">
                                        </div>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text inputGroup-sizing-default">To:</span>
                                            </div>
                                            <input  type="date" v-model="to_transaction_date" class="form-control col-12" id="inputToDate" placeholder="Date">
                                        </div>
                                        <a href="#" class="btn btn-primary float-right" @click="generateReportIS">Generate</a>
                                    </div>
                                    </div>
                                </div>
                                <div class="col-4">


                                    <div class="card">
                                    <div class="card-header">
                                        Balance Sheet
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Generate Balance Sheet</h5>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text inputGroup-sizing-default">As of:</span>
                                            </div>
                                            <input  type="date" v-model="transaction_date" class="form-control col-12" id="inputFromDate" placeholder="Date">
                                        </div>
                                        <div style="visibility: hidden;" class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text inputGroup-sizing-default">To:</span>
                                            </div>
                                            <input  type="date" class="form-control col-12" id="inputToDate" placeholder="Date">
                                        </div>
                                        <a href="#" class="btn btn-primary float-right" @click="generateReportBS">Generate</a>
                                    </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                
                                    <div class="card">
                                    <div class="card-header">
                                        Featured
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Special title treatment</h5>
                                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                                        <a href="#" class="btn btn-primary">Generate</a>
                                    </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                
                                    <div class="card">
                                    <div class="card-header">
                                        Featured
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Special title treatment</h5>
                                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                                        <a href="#" class="btn btn-primary">Generate</a>
                                    </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                
                                    <div class="card">
                                    <div class="card-header">
                                        Featured
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">Special title treatment</h5>
                                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                                        <a href="#" class="btn btn-primary">Generate</a>
                                    </div>
                                    </div>
                                </div>
                                
                            </div>
                            

                        </div>
                        <!-- /.box -->
                        </div>

                    </div>  
                    <!-- /.box -->
                </div>
        
            </div>
        </div>
    </div>
    
</template>
<script>
    import pdfobject from 'pdfobject';
    import jspdf from 'jspdf';
    export default {
        data() {
          return {
              user_id: null,
              sales: {},
              cost_of_sales: {},
              expenses: {},
              cashes: {},
              current_assets: {},
              non_current_assets: {},
              liabilities: {},
              non_current_liabilities: {},
              loans: {},
              other_non_current_liabilities: {},
              owners_equity: {},
              owners_withdrawal: {},
              lands: {},
              buildings: {},
              accu_buildings: {},
              furnitures: {},
              accu_furnitures: {},
                  
              //running_balance: 0,
              from_transaction_date: this.getDate(),
              to_transaction_date: this.getDate(),
              transaction_date: this.getDate(),
              company: null,
              company_id: document.querySelector('meta[name="company-id"]').getAttribute('content')
              //report_type: 'IS',
              //active_debit_row: 0
          }
        },
        methods: {
            getDate() {
                const toTwoDigits = num => num < 10 ? '0' + num : num;
                let today = new Date();
                let year = today.getFullYear();
                let month = toTwoDigits(today.getMonth() + 1);
                let day = toTwoDigits(today.getDate());
                return `${year}-${month}-${day}`;
            },
            getCompany(){
                //console.log('api/company/'+this.user_id);
                axios.get('api/company/'+this.company_id)
                    .then((response)=>{
                    //console.log(response.data);
                    this.company = response.data;
                    })
                    .catch(()=>{
                    //
                    });
                
            },
            generateReportBS(){
                let currencyOptions = { style: 'currency', currency: 'USD', currencyDisplay: 'code' };
                let result = null;
                var cash_amount = 0;
                var total_cash_amount = 0;
                var main_total_cash_amount = 0;    


                var current_asset_amount = 0;
                var total_current_asset_amount = 0;
                var main_total_current_asset_amount = 0;
                var grand_total_current_asset_amount = 0;
                
                var building_amount = 0;
                var total_building_amount = 0;
                var land_amount = 0;
                var total_land_amount = 0;        
                
                var accu_building_amount = 0;
                var accu_total_building_amount = 0;
                
                var furniture_amount = 0;
                var total_furniture_amount = 0;

                var accu_furniture_amount = 0;
                var accu_total_furniture_amount = 0;

                var docV = 15;
                var docH = 15;

                var doc = new jspdf();
                doc.setFont("Serif", 'normal');
                doc.setFontSize(16);
                doc.text(this.company.name,docH,docV);
                docV += 6;
                doc.setFontSize(12);
                doc.text(this.company.address +' '+  this.company.address2 +' '+ this.company.city,docH,docV);
                docV += 12;
                docH = 105;
                doc.setFontSize(16);
                doc.setFont("Serif", 'bold');
                doc.text('BALANCE SHEET',docH,docV, 'center');
                docV += 6;
                doc.setFontSize(10);
                doc.setFont("Serif", 'normal');
                doc.text('As of: '+this.transaction_date,docH,docV, 'center');
                docV += 12;
                docH = 15;
                doc.setFontSize(16);
                doc.text('ASSETS',docH,docV);
                docV += 12;
                docH = 15;
                doc.setFontSize(16);
                doc.text('CURRENT ASSETS',docH,docV);
                docV += 12;
                docH = 15;
                doc.setFontSize(16);
                doc.text('Cash and Cash Equivalent',docH,docV);
                doc.setFontSize(12);

                
                
                (async () => {
                    this.cashes = await this.getCashAccount();
                })();    
                
                for (var cash in this.cashes) {
                    docV += 6;
                    docH = 25;
                    doc.text(this.cashes[cash].account_name,docH,docV);
                    docH = 130;
                    cash_amount = (this.cashes[cash].debit * 1) - (this.cashes[cash].credit * 1);
                    total_cash_amount += cash_amount;
                    main_total_cash_amount += cash_amount;
                    
                    cash_amount = Intl.NumberFormat('en-US',currencyOptions).format(cash_amount);
                    cash_amount = cash_amount.replace(/[a-z]{3}/i, "").trim();
                    doc.text(cash_amount,docH,docV,'right');
                }
                docH = 160;
                total_cash_amount = Intl.NumberFormat('en-US',currencyOptions).format(total_cash_amount);
                total_cash_amount = total_cash_amount.replace(/[a-z]{3}/i, "").trim();
                doc.text(total_cash_amount,docH,docV,'right');
                
                
                    
                    
                (async () => {
                    this.current_assets = await this.getCurrentAssets();
                })();     
                
                //console.log(this.current_assets);
                for (var current_asset in this.current_assets) {
                    docV += 6;
                    docH = 15;
                    doc.setFontSize(16);
                    doc.text(this.current_assets[current_asset].account_name,docH,docV);
                    docH = 160;
                    current_asset_amount = (this.current_assets[current_asset].debit * 1) - (this.current_assets[current_asset].credit * 1);
                    total_current_asset_amount += current_asset_amount;
                    main_total_current_asset_amount += current_asset_amount;
                    
                    current_asset_amount = Intl.NumberFormat('en-US',currencyOptions).format(current_asset_amount);
                    current_asset_amount = current_asset_amount.replace(/[a-z]{3}/i, "").trim();
                    doc.setFontSize(12);
                    doc.text(current_asset_amount,docH,docV,'right');
                }
                   

                    
                docV += 12;    
                docH = 15;
                doc.setFontSize(16);
                doc.text('TOTAL CURRENT ASSETS',docH,docV);
                docH = 160;
                doc.setFontSize(12);
                grand_total_current_asset_amount = +main_total_cash_amount + +main_total_current_asset_amount;

                grand_total_current_asset_amount = Intl.NumberFormat('en-US',currencyOptions).format(grand_total_current_asset_amount);
                grand_total_current_asset_amount = grand_total_current_asset_amount.replace(/[a-z]{3}/i, "").trim();
                doc.text(grand_total_current_asset_amount,docH,docV,'right');

                docV += 12;
                docH = 15;
                doc.setFontSize(16);
                doc.text('NON CURRENT ASSETS',docH,docV);
                docV += 6;
                docH = 15;
                doc.setFontSize(16);
                doc.text('Property, Plan & Equipment',docH,docV);
                docV += 6;
                docH = 15;
                doc.setFontSize(16);
                doc.text('Land',docH,docV);
                docH = 160;
                doc.setFontSize(12);
                        
                (async () => {
                    this.lands = await this.getLandAccount();
                })();           
                
                
                if(this.lands){
                    for (var land in this.lands) {
                        console.log('land: ',land);
                        land_amount = (this.lands[land].debit * 1) - (this.lands[land].credit * 1);
                        total_land_amount += land_amount;
                    }
                } else {
                    total_land_amount = 0;
                }
                doc.text(this.formatToCurrency(total_land_amount),docH,docV,'right');
                
                docV += 6;
                docH = 15;
                doc.setFontSize(16);
                doc.text('Building',docH,docV);
                docH = 130;
                doc.setFontSize(12);

                (async () => {
                    this.buildings = await this.getBuildingAccount();
                })(); 

                
                var building_amount = 0;
                if(this.buildings){
                    for (var building in this.buildings) {
                        //console.log('land: ',land);
                        building_amount = (this.buildings[building].debit * 1) - (this.buildings[building].credit * 1);
                        total_building_amount += building_amount;
                    }
                } else {
                    total_building_amount = 0;
                }
                doc.text(this.formatToCurrency(total_building_amount),docH,docV,'right');



                docV += 6;
                docH = 15;
                doc.setFontSize(16);
                doc.text('Accumulated Dep. - Building',docH,docV);
                docH = 130;
                doc.setFontSize(12);

                (async () => {
                    this.accu_buildings = await this.getAccuBuildingAccount();
                
                })(); 

                if(this.accu_buildings){
                    for (var accu_building in this.accu_buildings) {
                        //console.log('land: ',land);
                        accu_building_amount = (this.accu_buildings[accu_building].debit * 1) - (this.accu_buildings[accu_building].credit * 1);
                        accu_total_building_amount += accu_building_amount;
                    }
                } else {
                    accu_total_building_amount = 0;
                }
                doc.text(this.formatToCurrency(accu_total_building_amount),docH,docV,'right');
                docH = 160;
                doc.setFontSize(12);
                doc.text(this.formatToCurrency(total_building_amount - accu_total_building_amount),docH,docV,'right');



                docV += 6;
                docH = 15;
                doc.setFontSize(16);
                doc.text('Furnitures & Fixtures',docH,docV);
                docH = 130;
                doc.setFontSize(12);
                
                
                (async () => {
                    this.furnitures = await this.getFurnitureAccount();
                
                })(); 
                
                
                if(this.furnitures){
                    for (var furniture in this.furnitures) {
                        //console.log('land: ',land);
                        furniture_amount = (this.furnitures[furniture].debit * 1) - (this.furnitures[furniture].credit * 1);
                        total_furniture_amount += furniture_amount;
                    }
                } else {
                    total_furniture_amount = 0;
                }
                doc.text(this.formatToCurrency(total_furniture_amount),docH,docV,'right');

                


                docV += 6;
                docH = 15;
                doc.setFontSize(16);
                doc.text('Accumulated Dep. - Furnitures & Fixtures',docH,docV);
                docH = 130;
                doc.setFontSize(12);

                (async () => {
                    this.accu_furnitures = await this.getAccuFurnitureAccount();
                
                })(); 

                
                
                if(this.accu_furnitures){
                    for (var accu_furniture in this.accu_furnitures) {
                        //console.log('land: ',land);
                        accu_furniture_amount = (this.accu_furnitures[accu_furniture].debit * 1) - (this.accu_furnitures[accu_furniture].credit * 1);
                        accu_total_furniture_amount += accu_furniture_amount;
                    }
                } else {
                    accu_total_furniture_amount = 0;
                }
                doc.text(this.formatToCurrency(accu_total_furniture_amount),docH,docV,'right');
                docH = 160;
                doc.setFontSize(12);
                doc.text(this.formatToCurrency(total_furniture_amount - accu_total_furniture_amount),docH,docV,'right');


                doc.save('test.pdf');

                        
                        
                        

            },
            async getFurnitureAccount(){
                const response = await axios.get('api/asof?start=15011300&end=15011399&transaction_date='+this.transaction_date);
                return response.data;
            },
            async getAccuFurnitureAccount(){
                const response = await axios.get('api/asof?start=15015200&end=15015299&transaction_date='+this.transaction_date);
                return response.data;
            },
            async getAccuBuildingAccount(){
                const response = await axios.get('api/asof?start=15011200&end=15011299&transaction_date='+this.transaction_date);
                return response.data;
            },
            async getBuildingAccount(){
                const response = await axios.get('api/asof?start=15011200&end=15011299&transaction_date='+this.transaction_date);
                return response.data;
            },
            async getCurrentAssets(){
                const response = await axios.get('api/asof?start=11011400&end=11051299&transaction_date='+this.transaction_date);
                return response.data;
            },
            async getCashAccount(){
                const response = await axios.get('api/asof?start=11011100&end=11011399&transaction_date='+this.transaction_date);
                return response.data;
            },
            async getLandAccount(){
                    const response = await axios.get('api/asof?start=15011100&end=15011199&transaction_date='+this.transaction_date);
                    return response.data;
            },
            formatToCurrency(amount){
                let currencyOptions = { style: 'currency', currency: 'USD', currencyDisplay: 'code' };
                amount = Intl.NumberFormat('en-US',currencyOptions).format(amount);
                amount = amount.replace(/[a-z]{3}/i, "").trim();
                return amount;
            },

            generateReportIS(){
                let currencyOptions = { style: 'currency', currency: 'USD', currencyDisplay: 'code' };
                
                
                let result = null;
                axios.get('api/daily?sub_account_type=SALES_AND_REVENUES&from_transaction_date='+this.from_transaction_date+'&to_transaction_date='+this.to_transaction_date)
                .then((response)=>{
                    this.sales = response.data;
                    

                    var sales_amount = 0;
                    var cost_of_sales_amount = 0;
                    var expense_amount = 0;

                    var total_sales_amount = 0;
                    var total_cost_of_sales_amount = 0;
                    var total_expense_amount = 0;

                    var main_total_sales_amount = 0;
                    var main_total_cost_of_sales_amount = 0;
                    var main_total_expense_amount = 0;
                    var net_profit = 0;

                    var gross_profit = 0;
                    var docV = 15;
                    var docH = 15;

                    var doc = new jspdf();
                    doc.setFont("Serif", 'normal');
                    doc.setFontSize(16);
                    doc.text(this.company.name,docH,docV);
                    docV += 6;
                    doc.setFontSize(12);
                    doc.text(this.company.address +' '+  this.company.address2 +' '+ this.company.city,docH,docV);
                    docV += 12;
                    docH = 105;
                    doc.setFontSize(16);
                    doc.setFont("Serif", 'bold');
                    doc.text('INCOME STATEMENT',docH,docV, 'center');
                    docV += 6;
                    doc.setFontSize(10);
                    doc.setFont("Serif", 'normal');
                    doc.text('From: '+this.from_transaction_date +' To: '+  this.to_transaction_date,docH,docV, 'center');
                    docV += 12;
                    docH = 15;
                    doc.setFontSize(16);
                    doc.text('Sales',docH,docV);
                    doc.setFontSize(12);
                    //console.log(this.sales);
                    for (var sale in this.sales) {
                        docV += 6;
                        docH = 25;
                        doc.text(this.sales[sale].account_name,docH,docV);
                        docH = 130;
                        sales_amount = (this.sales[sale].credit * 1) - (this.sales[sale].debit * 1);
                        total_sales_amount += sales_amount;
                        main_total_sales_amount += sales_amount;
                        //console.log(amount);
                        //amount.toFixed(2)
                        sales_amount = Intl.NumberFormat('en-US',currencyOptions).format(sales_amount);
                        sales_amount = sales_amount.replace(/[a-z]{3}/i, "").trim();
                        doc.text(sales_amount,docH,docV,'right');
                    }
                    docH = 160;
                        total_sales_amount = Intl.NumberFormat('en-US',currencyOptions).format(total_sales_amount);
                        total_sales_amount = total_sales_amount.replace(/[a-z]{3}/i, "").trim();
                        doc.text(total_sales_amount,docH,docV,'right');

                    docV += 12;
                    docH = 15;
                    doc.setFontSize(14);
                    doc.text('Cost of Sales',docH,docV);
                    doc.setFontSize(12);

                    axios.get('api/daily?sub_account_type=COST_OF_SALES&from_transaction_date='+this.from_transaction_date+'&to_transaction_date='+this.to_transaction_date)
                    .then((response)=>{
                        this.cost_of_sales = response.data;
                        for (var cost_of_sale in this.cost_of_sales) {
                            docV += 6;
                            docH = 25;
                            doc.text(this.cost_of_sales[cost_of_sale].account_name,docH,docV);
                            docH = 130;
                            cost_of_sales_amount = (this.cost_of_sales[cost_of_sale].debit * 1) - (this.cost_of_sales[cost_of_sale].credit * 1);
                            total_cost_of_sales_amount += cost_of_sales_amount;
                            main_total_cost_of_sales_amount += cost_of_sales_amount;
                            //console.log(amount);
                            //amount.toFixed(2)
                            cost_of_sales_amount = Intl.NumberFormat('en-US',currencyOptions).format(cost_of_sales_amount);

                            cost_of_sales_amount = cost_of_sales_amount.replace(/[a-z]{3}/i, "").trim();

                            //doc.text(Number(expense_amount.toFixed(2)).toLocaleString()+'' ,docH,docV);
                            doc.text(cost_of_sales_amount,docH,docV,'right');
                            
                            //doc.text(Number(cost_of_sales_amount.toFixed(2)).toLocaleString()+'' ,docH,docV);
                        }
                        docH = 160;
                        total_cost_of_sales_amount = Intl.NumberFormat('en-US',currencyOptions).format(total_cost_of_sales_amount);
                        total_cost_of_sales_amount = total_cost_of_sales_amount.replace(/[a-z]{3}/i, "").trim();
                        doc.text(total_cost_of_sales_amount,docH,docV,'right');
                        docV +=1;
                        doc.line(docH - 25,docV,docH,docV);

                        docV += 8;
                        docH = 15;
                        doc.setFontSize(14);
                        doc.text('Gross Profit',docH,docV);

                        doc.setFontSize(12);
                        docH = 160;
                        gross_profit = (main_total_sales_amount*1) - (main_total_cost_of_sales_amount*1);
                        gross_profit = Intl.NumberFormat('en-US',currencyOptions).format(gross_profit);
                        gross_profit = gross_profit.replace(/[a-z]{3}/i, "").trim();
                        doc.text(gross_profit,docH,docV,'right');

                        docV += 12;
                        docH = 15;
                        doc.setFontSize(14);
                        doc.text('Expenses',docH,docV);
                        doc.setFontSize(12);

                        axios.get('api/daily?sub_account_type=EXPENSES&from_transaction_date='+this.from_transaction_date+'&to_transaction_date='+this.to_transaction_date)
                        .then((response)=>{
                            this.expenses = response.data;
                            for (var expense in this.expenses) {
                                docV += 6;
                                docH = 25;
                                doc.text(this.expenses[expense].account_name,docH,docV);
                                docH = 130;
                                expense_amount = (this.expenses[expense].debit * 1) - (this.expenses[expense].credit * 1);
                                total_expense_amount += expense_amount;
                                main_total_expense_amount += expense_amount;
                                //console.log(amount);
                                //amount.toFixed(2)

                                expense_amount = Intl.NumberFormat('en-US',currencyOptions).format(expense_amount);
                                expense_amount = expense_amount.replace(/[a-z]{3}/i, "").trim();

                                //doc.text(Number(expense_amount.toFixed(2)).toLocaleString()+'' ,docH,docV);
                                doc.text(expense_amount,docH,docV,'right');
                            }
                            docH = 160;
                            total_expense_amount = Intl.NumberFormat('en-US',currencyOptions).format(total_expense_amount);
                            total_expense_amount = total_expense_amount.replace(/[a-z]{3}/i, "").trim();
                            doc.text(total_expense_amount,docH,docV,'right');
                            docV +=1;
                            doc.line(docH - 25,docV,docH,docV);
                            
                            docV += 8;
                            docH = 15;
                            doc.setFontSize(14);
                            doc.text('Net Profit',docH,docV);

                            doc.setFontSize(12);
                            docH = 160;
                            net_profit = (main_total_sales_amount*1) - (main_total_cost_of_sales_amount*1) - (main_total_expense_amount*1);
                            net_profit = Intl.NumberFormat('en-US',currencyOptions).format(net_profit);
                            net_profit = net_profit.replace(/[a-z]{3}/i, "").trim();
                            doc.text(net_profit,docH,docV,'right');
                            docV +=1;
                            doc.line(docH - 25,docV,docH,docV);
                            doc.save('test.pdf');

                        })
                        .catch(()=>{
                        });

                    })
                    .catch(()=>{
                    });

                    
                })
                .catch(()=>{
                });

                

            }


        
        },

        created() {
            this.getCompany();
        },
        computed: {
    

        },
        components: {
          
          
        }
    }
</script>
