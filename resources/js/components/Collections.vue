<template>

    <div class="container">
        <div class="row mt-1" v-if="$gate.isAdminOrUser()">
        <div class="col-md-12">
          <div class="box mt-4">
            <!-- general form elements -->
          <div class="box box-warning">


            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" @submit.prevent="createTransaction()">
              <div class="box-header with-border">
                <h3 class="box-title box-title-transaction">Collection</h3>
                <div class="box-tools">
                  <button type="submit" v-show="!transaction_created" class="btn btn-success">Create <i class="fas fa-plus-circle fa-fw"></i></button>
                  <!-- @click="createTransaction()"  -->
                  <button  type="button" class="btn btn-danger"  v-show="transaction_created" @click="cancelTransaction">Cancel <i class="fas fa-window-close fa-fw"></i></button>
                  <button type="button"  class="btn btn-success"  v-show="transaction_created" @click="saveTransaction">Save <i class="fas fa-save fa-fw"></i></button>

                </div>
              </div>
              <div class="box-body row">
                <!--Left Col-->
                <div class="col-8">


                  <div class="input-group mb-2">

                    <div class="input-group-prepend">
                      <span class="input-group-text inputGroup-sizing-default"  v-bind:style="[readabilityObject]">Payor</span>
                    </div>  
                    <input v-model="current_payee_name" v-bind:readonly="transaction_created" type="text" class="form-control col-12" id="inputPayeeName" placeholder="Payor Name" v-bind:style="[readabilityObject]">
                      
                    <span class="input-group-btn col-1">
                        <button type="button" v-show="!transaction_created" class="btn btn-success" @click="searchPayeeModal"><i class="fas fa-search fa-fw"></i></button>
                    </span>  

                    <p v-show="no_payee" class="empty-field-message">** Please select payor!</p>  
                  

                  </div>
                  

                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="inputGroup-sizing-default">Address</span>
                    </div>
                    <input v-bind:readonly="transaction_created" type="text" class="form-control col-12" id="inputPayeesAddress" placeholder="Address" v-model="current_payee_address">
                  </div>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="inputGroup-sizing-default">TIN</span>
                    </div>
                      <input v-bind:readonly="transaction_created" type="text" class="form-control" id="inputPayeesTIN" placeholder="TIN"  v-model="current_payee_tin">
                  </div>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text inputGroup-sizing-default">Account</span>
                    </div>
                    
                   
                    <input v-bind:readonly="transaction_created" type="text" class="form-control col-2" id="inputAccountCode" placeholder="Code"  v-model="form.account_code">

                    <input readonly="true" type="text" class="form-control col-9" id="inputAccountName" placeholder="Account Name" v-model="form.account_name">

                    <span class="input-group-btn col-1">
                        <button type="button" v-show="!transaction_created" class="btn btn-success" @click="searchAccountModal('header')"><i class="fas fa-search fa-fw"></i></button>

                    </span>
                    
                    
                  </div>
                  <div class="input-group mb-2">
                    <p v-show="no_account_code" class="empty-field-message">** Please select account!</p>
                  </div>  
                </div>
                <!--Right Col-->
                <div class="col-4">
                  <div class="input-group mb-2">
                    
                    <div class="input-group-prepend">
                      <span class="input-group-text inputGroup-sizing-default">Ref. #</span>
                    </div>

                    <input v-bind:readonly="transaction_created" type="text"  v-model="form.reference_no" class="form-control col-12" id="inputReferenceNo" placeholder="Reference No">
                    <p v-show="no_reference_no" class="empty-field-message">** Please enter reference number!</p>
                  </div>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text inputGroup-sizing-default">Date</span>
                    </div>
                    <input v-bind:readonly="transaction_created" type="date" v-model="form.transaction_date" class="form-control col-12" id="inputDate" placeholder="Date">
                  </div>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text inputGroup-sizing-default">Transaction #</span>
                    </div>
                    <input type="text" v-model="form.transaction_no" readonly class="form-control col-12" id="inputDCNo" placeholder="Transaction Number">
                  </div>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text inputGroup-sizing-default">Amount</span>
                    </div>
                    
                   <currency-input v-model="form.amount" v-bind:isReadonly="true" v-bind:fc="true" v-bind:col="12" id="inputAmount" placeholder="Amount"></currency-input>
                  </div>

                </div>
              </div>
              
            </form>
          </div>


      <!-- Purchase List     
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      -->
      <div  v-show="transaction_created" class="box box-warning mt-2">
        <div class="col-md-12">
          <div class="">
            <div class="box-header">
              <h3 class="box-title box-title-transaction">Sales</h3>
              
            </div>
            <!-- /.box-header -->
            <div id="debits-list" class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody>
                  <tr>
                    <th>Sales#</th>
                    <th>Account No.</th>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>Payment</th>
                    <th>Balance</th>
                    <th>Option</th>
                    
                  </tr>
                  <tr v-for="sale in sales.data" :key="sale.id" @click="selectPaymentRow(sale.id,sale.account_code)" :class="{ 'table-warning' : active_debit_row == sale.id }" >
                    <td>{{ sale.transaction_no }}</td>
                    <td>{{ sale.account_code }}</td>
                    <td>{{ sale.account_name }}</td>
                    <td>{{ sale.amount }}</td>
                    <td>{{ sale.total_collection }}</td>
                    <td>{{ currentBalance(sale.amount,sale.total_collection) }}</td> 
                    <td>
                      <a href="#" @click="pay(sale.id,sale.account_code,sale.account_name,currentBalance(sale.amount,sale.total_collection),
                    sale.account_type,
                    sale.sub_account_type,
                    sale.main_code,
                    sale.main_account,
                    sale.type)">
                        <i class="fas fa-money-bill"></i>
                        Pay
                        <!-- i class="fa fa-money-bill"></i -->
                      </a>
                    </td>
                  </tr>
                  
              </tbody>
            </table>
            </div>
            <!-- /.box-body -->
            
          </div>
          <!-- /.box -->
        </div>

      </div>  
      <!-- /.box -->



      <!-- Payment List     
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      -->

          <!-- -->
          <div  v-show="transaction_created"  class="box box-warning mt-2">
            <div class="col-md-12">
              <div class="">
                <div class="box-header">
                  <h3 class="box-title box-title-transaction">New Collections</h3>
                  <!-- div class="box-tools">
                    <button class="btn btn-success" @click="newEntry">Add Items <i class="fas fa-plus-circle fa-fw"></i></button>
                  </div -->
                </div>


                <!-- /.box-header -->
                <div id="debits-list" class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tbody>
                      <tr>
                        <th>Collection#</th>
                        <th>Account No.</th>
                        <th>Name</th>
                        <th>Amount</th>
                      </tr>
                      <tr v-for="entry in transactions" :key="entry.transaction_entry_id" :class="{ 'table-warning' : active_debit_row == entry.transaction_entry_id }" >
                        <td>{{ entry.transaction_no }}</td>
                        <td>{{ entry.account_code }}</td>
                        <td>{{ entry.account_name }}</td>
                        <td>{{ entry.amount }}</td>
                      </tr>
                      
                  </tbody>
                </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                  
                </div> 
              </div>
              <!-- /.box -->
            </div>

          </div>  
          <!-- /.box -->

      <!-- Payment History List     
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      -->

      <!-- -->
      <div  v-show="transaction_created"  class="box box-warning mt-2">
        <div class="col-md-12">
          <div class="">
            <div class="box-header">
              <h3 class="box-title box-title-transaction">Collection History</h3>
              <!-- div class="box-tools">
                <button class="btn btn-success" @click="newEntry">Add Items <i class="fas fa-plus-circle fa-fw"></i></button>
              </div -->
            </div>


            <!-- /.box-header -->
            <div id="debits-list" class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody>
                  <tr>
                    <th>Collection#</th>
                    <th>Account No.</th>
                    <th>Name</th>
                    <th>Amount</th>
                    
                    
                  </tr>
                  <tr v-for="payment in payment_history.data" :key="payment.id">
                    <td>{{ payment.transaction_no }}</td>
                    <td>{{ payment.account_code }}</td>
                    <td>{{ payment.account_name }}</td>
                    <td>{{ payment.amount }}</td>
                  </tr>
                  
              </tbody>
            </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <!-- getPaymentHistotyPage(payment.account_code) -->
              <pagination :data="payment_history"  @pagination-change-page="getPaymentHistotyPage"></pagination>
            </div> 
          </div>
          <!-- /.box -->
        </div>

      </div>  
      <!-- /.box -->

      
      

            <div v-show="transaction_created" class="box box-warning mt-2">
              
              
              <div class="form-group col-12 float-right">
                <div class="row">
                
                <label for="inputTotalAmount" class="col-sm-9 col-form-label" style="text-align: right;">Total Amount</label>
                <div class="col-sm-3">
                  <input readonly v-model="Number(form.amount).toLocaleString()" type="text" class="form-control" id="inputTotalAmount" placeholder="Total Amount">
                </div>
              </div>
              </div>
            
            </div>
          
          </div>
          <!-- /.box -->
        </div>
        </div>

        <div class="row mt-1" v-if="!$gate.isAdminOrUser()">
          <not-found></not-found>
        </div>  

      <!-- Payments Modal
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      -->


      <div class="modal fade" id="entry-payment" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content" style="width: 800px;">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" @click="cancelPayment(form_entry.account_code)" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <!-- form onsubmit="return false;" -->
            <div class="modal-body">
              
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text inputGroup-sizing-default">Code</span>
                </div>
                <input v-model="form_entry.account_code" type="text" name="account_code" 
                  readonly
                  class="form-control col-4" :class="{ 'is-invalid': form_entry.errors.has('account_code') }" aria-describedby="inputGroup-sizing-default">
                <has-error :form="form_entry" field="account_code"></has-error>
                <!-- span class="input-group-btn col-1">
                    <button type="button" class="btn btn-success" @click="searchAccountModal('detail')"><i class="fas fa-search fa-fw"></i></button>
                </span -->
              </div>

              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text inputGroup-sizing-default">Account</span>
                </div>
                
                <input v-model="form_entry.account_name" type="text" name="account_name"
                  class="form-control" :class="{ 'is-invalid': form_entry.errors.has('account_name') }" readonly aria-describedby="inputGroup-sizing-default">
                
              </div>
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text inputGroup-sizing-default">Amount</span>
                </div>

                  <input v-model="form_entry.amount" name="amount" id="amount"
                  
                  class="form-control" :class="{ 'is-invalid': form_entry.errors.has('amount') }" aria-describedby="inputGroup-sizing-default" onfocus="this.select()">
                  <has-error :form="form_entry" field="amount"></has-error>
              </div>

              

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" @click="cancelPayment(form_entry.account_code)">Cancel</button>
              <button type="button" :disabled="!save_button_entry_enabled" class="btn btn-success" @click="savePayment(form_entry.account_code,form_entry.amount)">Save</button>
            </div>

            <!-- /form -->
          </div>
        </div>
      </div>
      <!-- Entry Modal -->


      


      <!-- Search Account Modal 
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      -->

      <div class="modal fade" id="select-account" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <!--h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New</h5>
              <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Entry</h5 -->
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form onsubmit="return false;">
            <div class="modal-body">
              
              <div class="form-group">
                <label>Search</label>
                <input type="text" name="search" v-model="searchText" @change="SearchIt" class="float-right col-6">
              </div>
              
              <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody><tr>
                  <th>Code</th>
                  <th>Name</th>
                  <th>Option</th>
                </tr>
                <tr v-for="chart_of_account in chart_of_accounts.data" :key="chart_of_account.id">
                  <td>{{ chart_of_account.account_code }}</td>
                  <td>{{ chart_of_account.account_name }}</td>
                  <td>
                    <a href="#" @click="selectAccount(
                    chart_of_account.account_code,
                    chart_of_account.account_name,
                    chart_of_account.account_type,
                    chart_of_account.sub_account_type,
                    chart_of_account.main_code,
                    chart_of_account.main_account,
                    chart_of_account.type
                    )">Select
                      <i class="fa fa-edit"></i>
                    </a>
                  </td>
                </tr>
              </tbody></table>
            </div>
            <!-- /.box-body -->


            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              <!--button v-show="editmode" type="submit" class="btn btn-success">Update</button -->
              <!--button v-show="!editmode" type="submit" class="btn btn-primary">Create</button -->
            </div>

            </form>
          </div>
        </div>
      </div>
      <!-- Search Account Modal -->

    
      <!-- Search Payee Modal 
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      -->

      <div class="modal fade" id="select-payee" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <!--h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New</h5>
              <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Entry</h5 -->
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form onsubmit="return false;">
            <div class="modal-body">
              
              <div class="form-group">
                <label>Search</label>
                <input type="text" name="search" v-model="searchPayee" @change="SearchPayee" class="float-right col-6">
              </div>
              
              <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody><tr>
                  <th>Code</th>
                  <th>Name</th>
                  <th>Option</th>
                </tr>
                <tr v-for="payee in payees.data" :key="payee.id">
                  <td>{{ payee.id }}</td>
                  <td>{{ payee.name }}</td> 
                  <td>
                    <a href="#" @click="selectPayee(payee.id,payee.name,payee.address,payee.tin)">Select
                      <i class="fa fa-edit"></i>
                    </a>
                  </td>
                </tr>
              </tbody></table>
            </div>
            <!-- /.box-body -->


            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

            </form>
          </div>
        </div>
      </div>
      <!-- Search Payee Modal -->      

      <!--search-account
      v-show="isModalVisible"
      @close="closeSearchAccount"
      / -->

      

    </div>
</template>
<script>
    //import { ModelSelect } from 'vue-search-select'
    //import { DynamicSelect } from 'vue-dynamic-select'
    //import { BasicSelect } from 'vue-search-select'
  
    export default {
        data() {
          return {
              transaction_type: 'COLLECTION',
              transactions: [],
              transaction_entry_id: 0,
              current_transaction_entry_id: 0,

              current_balance: 0,
              current_sale_id: 0,
              user_id: '',
              transaction_created: false,
              no_payee: false,
              no_reference_no: false,
              no_account_code: false,
              save_button_item_enabled: true,
              save_button_entry_enabled: true,
              searchText: '',
              searchPayee: '',
              headerOrDetail: 'header',
              current_payee_id: '',
              current_payee_name: '',
              current_payee_address: '',
              current_payee_tin: '',
              active_debit_row: 0,
              form: new Form({
                  account_type:'',
                  sub_account_type:'',
                  main_code:0,
                  main_account:'',
                  type: '',
                  id:'',
                  payee_id: '',
                  reference_no: '',
                  transaction_no: '',
                  transaction_type: this.transaction_type, // default for Cash Disbursement
                  transaction_date: this.getDate(),
                  account_code: '',
                  account_name:'',
                  amount: 0,
                  credit_amount: 0,
                  debit_amount: 0,
                  amount_ex_tax: 0,
                  vat: 0,
                  canceled: 0,
                  user_id: document.querySelector('meta[name="user-id"]').getAttribute('content')
                  
                  
              }),
              form_entry: new Form({
                  account_type:'',
                  sub_account_type:'',
                  main_code:0,
                  main_account:'',
                  type: '',
                  id:'',
                  transaction_id:'',
                  transaction_no:'',
                  transaction_type:'',
                  account_code : 0,
                  account_name: 'NA',

                  branch_id: '',
                  branch_name: '',
                  amount: 0,
                  amount_ex_tax: 0,
                  
                  vat: 0,
                  credit_amount: 0,
                  debit_amount: 0,
                  transaction_date: this.getDate(),
                  
                  
              }),
              payees: {},
              branches: {},
              payment_history: {},
              sales:{},
              chart_of_accounts: {},
              chart_of_accounts_header: {},
              chart_of_accounts_detail: {},
              readabilityObject: {
                fontSize: user.font_size
              },

          }
        },
        methods: {
          
          currentBalance(total_amount,payments){
            return (total_amount - payments).toFixed(2);
          },
          loadSale() {
              axios.get('api/cd/sales/list?payee_id='+this.current_payee_id)
                .then((data)=>{
                  this.sales = data.data;
                  //console.log(this.sales);
                })
                .catch(()=>{
                  //
                });
          },

          loadBranches(){

            if(this.$gate.isAdminOrUser()){
                axios.get("api/branch").then(({data}) => (this.branches = data ));
                //axios.get("api/user").then(({ data }) => (this.users = data.data));
            } 
             
          },
          loadPayees(){
            if(this.$gate.isAdminOrUser()){
                axios.get("api/payee").then(({data}) => (this.payees = data ));
                //axios.get("api/user").then(({ data }) => (this.users = data.data));
            } 
          },
          loadChartAccounts(headerOrDetail = null){
              if(headerOrDetail == 'header'){
                  this.chart_of_accounts = this.chart_of_accounts_header; 
              } else {
                  this.chart_of_accounts = this.chart_of_accounts_detail;
              }
              
          },
          initChartAccounts(){

              axios.get('api/chartaccount?headerordetail=header&transaction_type=CD')
                .then((data)=>{
                  this.chart_of_accounts_header = data.data;
                })
                .catch(()=>{
                  //
                });
                
              axios.get('api/chartaccount?headerordetail=detail&transaction_type=CD')
                .then((data)=>{
                  this.chart_of_accounts_detail = data.data;
                })
                .catch(()=>{
                  //
                });  
          },
          
          getDate() {
            const toTwoDigits = num => num < 10 ? '0' + num : num;
            let today = new Date();
            let year = today.getFullYear();
            let month = toTwoDigits(today.getMonth() + 1);
            let day = toTwoDigits(today.getDate());
            return `${year}-${month}-${day}`;
          },
          createTransaction(){
            if(this.form.payee_id.length == 0) {
              this.no_payee = true;
            } else {
              this.no_payee = false;
            }

            if(this.form.account_code.length == 0) {
              this.no_account_code = true;
            } else {
              this.no_account_code = false;
            }
            
            if(this.form.reference_no.length == 0) {
              this.no_reference_no = true;
            } else {
              this.no_reference_no = false;
            }

            if (this.no_account_code || this.no_reference_no || this.no_payee){
              this.transaction_created = false;
            } else {
              this.transaction_created = true;
            }


            this.form.transaction_no = this.createSerialNumber();
            this.form.transaction_type = 'COLLECTION';
            this.loadSale();
            /*
            this.form.post('api/cd')
                .then((data)=>{
                  //console.log(data.data.id);
                  this.form.id = data.data.id;
                })
                .catch(()=>{
                  //
                });
            */
            
          },
          saveTransaction(){
            if(this.form.amount == 0) {
              return false;
            }

            ++this.transaction_entry_id;
            this.transactions.push({ 
                // *************************
                account_type: this.form.account_type,
                sub_account_type: this.form.sub_account_type,
                main_code: this.form.main_code,
                main_account: this.form.main_account,
                type: this.form.type,
                counterpart_code: 0,
                counterpart_name: 'NA',
                // *************************

                transaction_entry_id: this.transaction_entry_id,
                payee_id: this.form.payee_id,
                branch_id: 0,
                account_code: this.form.account_code,
                account_name: this.form.account_name,
                reference_no: this.form.reference_no,
                transaction_no: this.form.transaction_no,
                transaction_type: this.form.transaction_type,
                transaction_date: this.form.transaction_date,
                amount: this.form.amount,
                credit_amount: this.form.amount,
                debit_amount: 0,
                total_payment: 0,
                total_collection: 0,
                amount_ex_tax: 0,
                vat: 0,
                wtax_code: 0,
                wtax: 0,
                user_id: this.form.user_id,
                status: 'CONFIRMED',
                depreciation_date: this.form.transaction_date,
                depreciated_id: 0,
                useful_life: 0,
                salvage_value: 0
            });

                // Save Transactions START
                let rawData = {
                    transactions: this.transactions
                }
                rawData = JSON.stringify(rawData);
                let formData = new FormData();
                    formData.append('transactions', rawData);
                axios.post('api/transactions', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then((response)=>{
                    
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });
                // Save Transactions END


                // Save Payment START
                let saleData = {
                    sales: this.sales.data
                }

                saleData = JSON.stringify(saleData);
                let saleFormData = new FormData();
                saleFormData.append('sales', saleData);
                axios.post('api/record_collection', saleFormData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then((response)=>{
                    
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });  
                // Save Payment END
            swal.fire({
                  title: 'Saved!',
                  text: "Journal posted",
                  type: 'info',
                  showCancelButton: false,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Ok'
                }).then((result) => {
                  if (result.value) {
                    //Reload Current Page
                    this.dataReset();        
                  }
                });
           

          },
          dataReset(){
                this.form.reset();
                this.form_entry.reset();
                this.transactions = [];
                this.transaction_entry_id = 0;
                this.current_transaction_entry_id = 0;

                this.current_balance = 0;
                this.current_sale_id = 0;
                this.transaction_created = false;
                this.no_payee = false;
                this.no_reference_no = false;
                this.no_account_code = false;
                this.save_button_item_enabled = true;
                this.save_button_entry_enabled = true;
                this.searchText = '';
                this.searchPayee = '';
                this.headerOrDetail = 'header';
                this.current_payee_id = '';
                this.current_payee_name = '';
                this.current_payee_address = '';
                this.current_payee_tin = '';
                this.active_debit_row = 0;
                this.payment_history = {};
                this.sales = {};
          },
          cancelTransaction(){
            this.transaction_created = false;
            this.dataReset();
            //this.form.delete('api/cd/cancel/'+this.form.transaction_no);
          },
          searchAccountModal(headerOrDetail = 'header'){
              this.headerOrDetail = headerOrDetail;
              // = this.form.account_code;
              this.searchText = '';
              this.loadChartAccounts(headerOrDetail);
              $('#select-account').modal('show');
          },
          searchPayeeModal(){
              this.searchPayee = this.form.payee_id;
              this.loadPayees();
              $('#select-payee').modal('show');
          },

          // *************************************************
          selectAccount(
                        account_code  = null,
                        account_name = null, 
                        account_type,
                        sub_account_type,
                        main_code,
                        main_account,
                        type
                      ){
              if (account_code != null && account_name != null){
                  if(this.headerOrDetail == 'header'){
                      this.form.account_name = account_name;
                      this.form.account_code = account_code;
                      this.form.account_type = account_type;
                      this.form.sub_account_type = sub_account_type;
                      this.form.main_code = main_code;
                      this.form.main_account = main_account;
                      this.form.type = type;
                  } else {
                      this.form_entry.account_name = account_name;
                      this.form_entry.account_code = account_code;
                      this.form_entry.account_type = account_type;
                      this.form_entry.sub_account_type = sub_account_type;
                      this.form_entry.main_code = main_code;
                      this.form_entry.main_account = main_account;
                      this.form_entry.type = type;
                  }
              }
              $('#select-account').modal('hide');  

          },
          // *************************************************

          selectPayee(id = null,name = null,address = null,tin = null){
              if (id){
                      this.current_payee_id = id;
                      this.form.payee_id = id;
                      this.current_payee_name = name;
                      this.current_payee_address = address;
                      this.current_payee_tin = tin;
              }
              $('#select-payee').modal('hide');  

          },
          SearchIt() {
              let query = this.searchText;
              let headerOrDetail = this.headerOrDetail;
              axios.get('api/searchAccount?q='+query+'&transaction_type=CD&headerordetail='+headerOrDetail)
                .then((data)=>{
                  this.chart_of_accounts = data.data;
                })
                .catch(()=>{
                  //
                });
          },
          SearchPayee() {
              let query = this.searchPayee;
              axios.get('api/searchPayee?q='+query)
                .then((data)=>{
                  this.payees = data.data;
                })
                .catch(()=>{
                  //
                });
          },
          
          
          createSerialNumber(){
              // Get current date
              var d = new Date();
              // Get pirmative value of date
              var n = d.valueOf();
              // Return concatenated primative value with the user id. 
              
              return ""+n+this.user_id;
          },
          
          
          
          
          pay(sale_id,account_code,account_name,current_balance, 
                        account_type,
                        sub_account_type,
                        main_code,
                        main_account,
                        type)
          {
                this.current_balance = current_balance;
                this.current_sale_id = sale_id;

                ++this.transaction_entry_id;

                this.form_entry.reset();
                
                //this.form_entry.transaction_id = this.form.id;
                this.form_entry.transaction_id = this.transaction_entry_id;
                this.form_entry.transaction_no = this.form.transaction_no;
                this.form_entry.transaction_type = this.transaction_type;
                this.form_entry.account_code = account_code;
                this.form_entry.account_name = account_name;

                
                this.form_entry.account_type = account_type;
                this.form_entry.sub_account_type = sub_account_type;
                this.form_entry.main_code = main_code;
                this.form_entry.main_account = main_account;
                this.form_entry.type = type;
                
                this.form_entry.payee_id = this.current_payee_id;
                //this.form_entry.branch_name = this.current_payee_name;
                this.save_button_entry_enabled = true;

                

                $('#entry-payment').modal('show');
              

          },
          savePayment(account_code,payment_amount)
          {
            
            if( parseFloat(this.form_entry.amount) == 0){
              
              swal.fire(
                      'Error!',
                      'Please enter amount!',
                      'error'
                    );
              return false;
            }

            if(parseFloat(this.current_balance) < parseFloat(this.form_entry.amount)){
              // *************** If this is triggered another duplicate entry/post is made on the transactions_1 table. s 
              
              
              swal.fire(
                      'Warning!',
                      'Current balance is only '+this.current_balance+'!',
                      'error'
                    );
              return true;
            }

            

            this.form.amount +=  parseFloat(this.form_entry.amount);
            
            this.save_button_entry_enabled = false;


            
            this.transactions.push({ 

                // *************************
                account_type: this.form_entry.account_type,
                sub_account_type: this.form_entry.sub_account_type,
                main_code: this.form_entry.main_code,
                main_account: this.form_entry.main_account,
                type: this.form_entry.type,
                counterpart_code: 0,
                counterpart_name: 'NA',
                // *************************

                transaction_entry_id: this.transaction_entry_id,
                payee_id: this.form.payee_id,
                branch_id: 0,
                account_code: this.form_entry.account_code,
                account_name: this.form_entry.account_name,
                reference_no: this.form.reference_no,
                transaction_no: this.form.transaction_no,
                transaction_type: this.form.transaction_type,
                transaction_date: this.form.transaction_date,
                amount: this.form_entry.amount,
                credit_amount: 0,
                debit_amount: this.form_entry.amount,
                total_collection: 0,
                total_payment: 0,
                amount_ex_tax: 0,
                vat: 0,
                wtax_code: 0,
                wtax: 0,
                user_id: this.form.user_id,
                status: 'CONFIRMED',
                depreciation_date: this.form.transaction_date,
                depreciated_id: 0,
                useful_life: 0,
                salvage_value: 0
            });




              $('#entry-payment').modal('hide');

              //this.loadPayments();
              this.loadPaymentHistory(account_code);
              
              this.updatePurchase(parseFloat(payment_amount));

                

            this.form_entry.reset();

            
          },



          cancelPayment(account_code){
            
            this.form_entry.reset();

            $('#entry-payment').modal('hide');
          },
          selectPaymentRow(active_debit_row_id,account_code){

              //this.active_debit_row = active_debit_row_id;
              //this.form_entry.id = active_debit_row_id;
              
              //this.loadPayments();
              this.loadPaymentHistory(account_code);
          },
          loadPaymentHistory(account_code) {
                axios.get('api/cd/entries/collectionList?payee_id='+this.form.payee_id+'&account_code='+account_code)
                .then((data)=>{
                  this.payment_history = data.data;
                })
                .catch(()=>{
                  //
                });
                

          },
          getPaymentHistotyPage(page){
              
              
              axios.get('api/cd/entries/collectionList?payee_id='+this.current_payee_id+'&page='+ page)
                .then((data)=>{
                  this.payment_history = data.data;
                })
                .catch(()=>{
                });
              
          },
          updatePurchase(payment_amount){
            

            this.sales.data.map((sale) => {
              if(!sale.total_collection){
                sale.total_collection = 0;
              }
              if(sale.id == this.current_sale_id){
                return sale.total_collection = parseFloat(sale.total_collection) + parseFloat(payment_amount);
              } else {
                return sale.total_collection = sale.total_collection;
              }
            })
            
          }
          },

        created() {
            
            
            
            this.loadPayees();
            this.loadBranches();
            this.initChartAccounts();


            
            



            


            this.user_id = document.querySelector('meta[name="user-id"]').getAttribute('content');
            //console.log(document.querySelector('meta[name="user-id"]').getAttribute('content'));
            //console.log(this.payees);

            //setInterval(() => this.loadUsers(),3000);


            /* Scrollbar fix
               If you have a modal on your page that exceeds the browser height, then you can't scroll in it when closing an second modal. To fix this add: */
            $(document).on('hidden.bs.modal', '.modal', function () {
                $('.modal:visible').length && $(document.body).addClass('modal-open');
            });

            /*Backdrop z-index fix
              This solution uses a setTimeout because the .modal-backdrop isn't created when the event show.bs.modal is triggered.

            $(document).on('show.bs.modal', '.modal', function () {
                var zIndex = 1040 + (10 * $('.modal:visible').length);
                $(this).css('z-index', zIndex);
                setTimeout(function() {
                    $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
                }, 0);
            });
            */
        },
        computed: {
            currentTransctions(){
                return this.transactions.filter(transaction  => 
                    {
                    return parseInt(transaction.transaction_no)==parseInt(this.form.transaction_no);
                  }  
                )
            }
          

        },
        components: {
          
          
        }
    }
</script>
<style type="text/css">
  
</style>