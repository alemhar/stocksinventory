<template>

    <div class="container">
        <div class="row mt-1" v-if="$gate.isAdminOrUser()">
        <div class="col-md-12">
          <div class="box mt-4">
            <!-- general form elements -->
          <div class="box box-warning">


            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" @submit.prevent="createCD()">
              <div class="box-header with-border">
                <h3 class="box-title box-title-transaction">Payment</h3>
                <div class="box-tools">
                  <button type="submit" v-show="!cd_created" class="btn btn-success">Create <i class="fas fa-plus-circle fa-fw"></i></button>
                  <!-- @click="createCD()"  -->
                  <button  type="button" class="btn btn-danger"  v-show="cd_created" @click="cancelCD">Cancel <i class="fas fa-window-close fa-fw"></i></button>
                  <button type="button"  class="btn btn-success"  v-show="cd_created" @click="saveCD">Save <i class="fas fa-save fa-fw"></i></button>

                </div>
              </div>
              <div class="box-body row">
                <!--Left Col-->
                <div class="col-8">


                  <div class="input-group mb-2">
                    <!-- label for="inputPayeesName">Payee</label -->
                    <!--dynamic-select 
                      :options="payees.data"
                      option-value="id"
                      option-text="name"
                      placeholder="Type to search"
                      v-on:input="eventChild"
                      v-model="current_payee"
                      v-show="!cd_created"
                      / -->
                    <div class="input-group-prepend">
                      <span class="input-group-text inputGroup-sizing-default"  v-bind:style="[readabilityObject]">Payee</span>
                    </div>  
                    <input v-model="current_payee_name" v-bind:readonly="cd_created" type="text" class="form-control col-12" id="inputPayeeName" placeholder="Payees Name" v-bind:style="[readabilityObject]">
                      
                    <span class="input-group-btn col-1">
                        <button type="button" v-show="!cd_created" class="btn btn-success" @click="searchPayeeModal"><i class="fas fa-search fa-fw"></i></button>
                    </span>  

                    <p v-show="no_payee" class="empty-field-message">** Please select payee!</p>  
                  

                  </div>
                  

                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="inputGroup-sizing-default">Address</span>
                    </div>
                    <input v-bind:readonly="cd_created" type="text" class="form-control col-12" id="inputPayeesAddress" placeholder="Address" v-model="current_payee_address">
                  </div>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="inputGroup-sizing-default">TIN</span>
                    </div>
                      <input v-bind:readonly="cd_created" type="text" class="form-control" id="inputPayeesTIN" placeholder="TIN"  v-model="current_payee_tin">
                  </div>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text inputGroup-sizing-default">Account</span>
                    </div>
                    
                   
                    <input v-bind:readonly="cd_created" type="text" class="form-control col-2" id="inputAccountCode" placeholder="Code"  v-model="form.account_code">

                    <input readonly="true" type="text" class="form-control col-9" id="inputAccountName" placeholder="Account Name" v-model="form.account_name">

                    <span class="input-group-btn col-1">
                        <button type="button" v-show="!cd_created" class="btn btn-success" @click="searchAccountModal('header')"><i class="fas fa-search fa-fw"></i></button>

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

                    <input v-bind:readonly="cd_created" type="text"  v-model="form.reference_no" class="form-control col-12" id="inputReferenceNo" placeholder="Reference No">
                    <p v-show="no_reference_no" class="empty-field-message">** Please enter reference number!</p>
                  </div>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text inputGroup-sizing-default">Date</span>
                    </div>
                    <input v-bind:readonly="cd_created" type="date" v-model="form.transaction_date" class="form-control col-12" id="inputDate" placeholder="Date">
                  </div>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text inputGroup-sizing-default">Payment #</span>
                    </div>
                    <input type="text" v-model="form.transaction_no" readonly class="form-control col-12" id="inputDCNo" placeholder="Payment Number">
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
      <div  v-show="cd_created" class="box box-warning mt-2">
        <div class="col-md-12">
          <div class="">
            <div class="box-header">
              <h3 class="box-title box-title-transaction">Purchases</h3>
              
            </div>
            <!-- /.box-header -->
            <div id="debits-list" class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody>
                  <tr>
                    <th>Purchase#</th>
                    <th>Account No.</th>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>Payment</th>
                    <th>Balance</th>
                    <th>Option</th>
                    
                  </tr>
                  <tr v-for="purchase in purchases.data" :key="purchase.id" @click="selectPaymentRow(purchase.id,purchase.account_code)" :class="{ 'table-warning' : active_debit_row == purchase.id }" >
                    <td>{{ purchase.transaction_no }}</td>
                    <td>{{ purchase.account_code }}</td>
                    <td>{{ purchase.account_name }}</td>
                    <td>{{ purchase.amount }}</td>
                    <td>{{ purchase.total_payment }}</td>
                    <td>{{ currentBalance(purchase.amount,purchase.total_payment) }}</td> 
                    <td>
                      <a href="#" @click="payEntry(purchase.id,purchase.account_code,purchase.account_name,currentBalance(purchase.amount,purchase.total_payment))">
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
          <div  v-show="cd_created"  class="box box-warning mt-2">
            <div class="col-md-12">
              <div class="">
                <div class="box-header">
                  <h3 class="box-title box-title-transaction">Payments</h3>
                  <!-- div class="box-tools">
                    <button class="btn btn-success" @click="newEntry">Add Items <i class="fas fa-plus-circle fa-fw"></i></button>
                  </div -->
                </div>


                <!-- /.box-header -->
                <div id="debits-list" class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tbody>
                      <tr>
                        <th>Payment#</th>
                        <th>Account No.</th>
                        <th>Name</th>
                        <th>Amount</th>
                        
                        
                      </tr>
                      <tr v-for="entry in entries.data" :key="entry.id" :class="{ 'table-warning' : active_debit_row == entry.id }" >
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
      <div  v-show="cd_created"  class="box box-warning mt-2">
        <div class="col-md-12">
          <div class="">
            <div class="box-header">
              <h3 class="box-title box-title-transaction">Payment History</h3>
              <!-- div class="box-tools">
                <button class="btn btn-success" @click="newEntry">Add Items <i class="fas fa-plus-circle fa-fw"></i></button>
              </div -->
            </div>


            <!-- /.box-header -->
            <div id="debits-list" class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody>
                  <tr>
                    <th>Payment#</th>
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

      
      

            <div v-show="cd_created" class="box box-warning mt-2">
              
              
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
                    <a href="#" @click="selectAccount(chart_of_account.account_code,chart_of_account.account_name)">Select
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
              ledgers: [],
              current_balance: 0,
              current_purchase_id: 0,
              user_id: '',
              editmode: false,
              cd_created: false,
              no_payee: false,
              no_reference_no: false,
              no_account_code: false,
              no_item: false,
              no_price: false,
              no_quantity: false,
              no_entry_account_code: false,
              no_entry_branch_id: false,
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
              selected_branch: {},
              cd : {},
              form: new Form({
                  id:'',
                  payee_id: '',
                  reference_no: '',
                  transaction_no: '',
                  transaction_type: 'PAYMENT', // default for Cash Disbursement
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

                  id:'',
                  transaction_id:'',
                  transaction_no:'',
                  transaction_type:'',
                  account_code : 0,
                  account_name: 'NA',
                  //entry_name: '',
                  //entry_description: '',
                  branch_id: '',
                  branch_name: '',
                  amount: 0,
                  amount_ex_tax: 0,
                  
                  vat: 0,
                  credit_amount: 0,
                  debit_amount: 0,
                  transaction_date: this.getDate(),
                  
                  
              }),
              form_item: new Form({

                  id:'',
                  transaction_entry_id:'',
                  transaction_no:'',
                  transaction_type:'',
                  account_code : '',
                  item: '',
                  quantity: 0,
                  price: 0,
                  sub_total: 0,
                  tax_type: 'VAT',
                  tax_excluded: 0,
                  vat: 0
                  
                  
              }),
              payees: {},
              branches: {},
              items: {},
              entries: {},
              payment_history: {},
              purchases:{},
              chart_of_accounts: {},
              chart_of_accounts_header: {},
              chart_of_accounts_detail: {},
              readabilityObject: {
                fontSize: user.font_size
              },
              account_code: ''

          }
        },
        methods: {
          
          currentBalance(total_amount,payments){
            return (total_amount - payments).toFixed(2);
          },
          loadPurchase() {
              axios.get('api/cd/purchase/list')
                .then((data)=>{
                  this.purchases = data.data;
                  //console.log(this.purchases);
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
              /*
              if(headerOrDetail == null){
                axios.get("api/chartaccount").then(({ data }) => (this.chart_of_accounts = data));
              } else {
                //axios.get("api/chartaccount").then(({ data }) => (this.chart_of_accounts = data));

                axios.get('api/chartaccount?headerordetail='+headerOrDetail+'&transaction=CR')
                .then((data)=>{
                  this.chart_of_accounts = data.data;
                })
                .catch(()=>{
                  //
                });

              }
              */
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
          
          /* Check if this is still being used.*/
          eventChild(Obj){
            //console.log(Obj.id);
            this.form.payee_id = Obj.id;
          },

          getDate() {
            const toTwoDigits = num => num < 10 ? '0' + num : num;
            let today = new Date();
            let year = today.getFullYear();
            let month = toTwoDigits(today.getMonth() + 1);
            let day = toTwoDigits(today.getDate());
            return `${year}-${month}-${day}`;
          },
          createCD(){
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
              this.cd_created = false;
            } else {
              this.cd_created = true;
            }


            this.form.transaction_no = this.createSerialNumber();
            this.form.transaction_type = 'PAYMENT';
            this.form.post('api/cd')
                .then((data)=>{
                  //console.log(data.data.id);
                  this.form.id = data.data.id;
                })
                .catch(()=>{
                  //
                });
            
          },
          saveCD(){
            if(this.form.amount == 0) {
              return false;
            }
            this.$Progress.start();
            this.form.put('api/cd/'+this.form.id)
            .then(() => {
                  this.cd_created = false;
                  this.form.post('api/cd/confirm/'+this.form.transaction_no);
                  this.ledgers.push({ 
                      id: this.form.id,
                      transaction_id: this.form.id, 
                      transaction_no: this.form.transaction_no,
                      transaction_type: this.form.transaction_type,
                      account_code: this.form.account_code,
                      account_name: this.form.account_name,
                      transaction_date: this.form.transaction_date,
                      credit_amount: this.form.amount,
                      debit_amount: 0
                    });

                  /*
                  this.ledgers.push({ 
                      id: 1,
                      transaction_id: this.form.id, 
                      transaction_no: this.form.transaction_no,
                      transaction_type: this.form.transaction_type,
                      account_code: '1105110',
                      account_name: 'Input Tax',
                      transaction_date: this.form.transaction_date,
                      credit_amount: 0,
                      debit_amount: this.form.vat
                    });  
                  */
                      let rawData = {
                          ledgers: this.ledgers
                      }
                      rawData = JSON.stringify(rawData);
                      let formData = new FormData();
                          formData.append('ledgers', rawData);
                      axios.post('api/ledgers', formData, {
                          headers: {
                              'Content-Type': 'multipart/form-data'
                          }
                      })
                      .then((response)=>{
                          
                          //console.log(response);
                      })
                      .catch(function (error) {
                          console.log(error);
                      });


                      let paymentData = {
                          payments: this.purchases.data
                      }

                      paymentData = JSON.stringify(paymentData);
                      let paymentFormData = new FormData();
                          paymentFormData.append('payments', paymentData);
                      axios.post('api/record_payment', paymentFormData, {
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
                  this.$Progress.finish();
                                    
            })
            .catch(() => {
                this.$Progress.fail();
            });

            swal.fire({
                  title: 'Saved!',
                  text: "Journal posted",
                  icon: 'info',
                  showCancelButton: false,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Ok'
                }).then((result) => {
                  if (result.value) {
                    //Reload Current Page
                    this.$router.go();        
                  }
                });
             

          },
          cancelCD(){
            this.cd_created = false;
            this.form.delete('api/cd/cancel/'+this.form.transaction_no);
          },
          searchAccountModal(headerOrDetail = 'header'){
              this.headerOrDetail = headerOrDetail;
              this.searchText = this.form.account_code;
              //this.searchText = '';
              this.loadChartAccounts(headerOrDetail);
              $('#select-account').modal('show');
          },
          searchPayeeModal(){
              this.searchPayee = this.form.payee_id;
              //this.searchPayee = '';
              this.loadPayees();
              $('#select-payee').modal('show');
          },

          selectAccount(account_code  = null,account_name = null){
              if (account_code != null && account_name != null){
                  if(this.headerOrDetail == 'header'){
                      this.form.account_name = account_name;
                      this.form.account_code = account_code;
                  } else {
                      this.form_entry.account_name = account_name;
                      this.form_entry.account_code = account_code;
                  }
              }

              $('#select-account').modal('hide');  

          },

          selectPayee(id = null,name = null,address = null,tin = null){
              if (id){
                    //console.log(name);
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
          loadEntryItems() {
              /*
              let entry_id = this.form_entry.id;
              axios.get('api/cd/items/list?entry_id='+entry_id)
                .then((data)=>{
                  this.items = data.data;
                })
                .catch(()=>{
                  //
                });
                */
          },
          loadEntries() {
              let transaction_no = this.form.transaction_no;
              axios.get('api/cd/entries/list?transaction_no='+transaction_no)
                .then((data)=>{
                  this.entries = data.data;
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
          computeTaxChange(event){
              /*
              if(this.form_item.price && this.form_item.quantity){
                this.form_item.sub_total = this.form_item.price * this.form_item.quantity;
                if(this.form_item.tax_type == 'VAT'){
                  //this.form_item.amount = event.target.value;

                  

                  this.form_item.tax_excluded = (this.form_item.sub_total/1.12).toFixed(2) * 1;

                  this.form_item.vat = (this.form_item.tax_excluded * 0.12).toFixed(2)  * 1;

                } else {
                  //this.form_entry.amount = event.target.value;
                  this.form_item.vat = 0;
                  this.form_item.tax_excluded = this.form_item.sub_total  * 1;
                }
              }
              */
          },
          selectDebitRow(active_debit_row_id){
              /*
              this.active_debit_row = active_debit_row_id;
              this.form_entry.id = active_debit_row_id;
              VueListen.$emit('RefreshItemTable');
              //console.log(active_debit_row);
              */
          },
          
          newEntry(){
              /*
              this.editmode = false;
              this.form_entry.reset();
              this.form_entry.transaction_id = this.form.id;
              this.form_entry.transaction_no = this.form.transaction_no;
              this.form_entry.transaction_type = 'CD';
              this.save_button_entry_enabled = true;
              this.form_entry.post('api/cd/entry')
                .then((data)=>{
                  this.form_entry.id = data.data.id;
                  //console.log(data.data.id);
                  VueListen.$emit('RefreshItemTable');    
                })
                .catch(()=>{
                  //
                });
                
              $('#entry-details').modal('show');

            */
          },
          newItem(){
              /*
              if(this.form_entry.account_code == 0) {
                this.no_entry_account_code = true;
              } else {
                this.no_entry_account_code = false;
              }
              if(this.form_entry.branch_id == 0) {
                this.no_entry_branch_id = true;
              } else {
                this.no_entry_branch_id = false;
              }

              if (this.no_entry_account_code || this.no_entry_branch_id){
                return false;
              }

              this.save_button_item_enabled = true;

              this.editmode = false;
              this.form_item.reset();

              this.no_item = false;
              this.no_price = false;
              this.no_quantity = false;
              this.form_item.transaction_entry_id = this.form_entry.id;
              this.form_item.transaction_no = this.form.transaction_no;
              this.form_item.transaction_type = 'CD';
              this.form_item.account_code = this.form_entry.account_code;

              this.form_item.post('api/cd/item')
                .then((data)=>{
                  this.form_item.id = data.data.id;
                  //console.log(data.data.id);
                })
                .catch(()=>{
                  //
                });
              this.loadEntryItems();
              $('#entry-items').modal('show');
              */
          },
          cancelDebitEntry(){
            /*
            //this.$Progress.start();
            this.form_item.delete('api/cd/entry/'+this.form_entry.id)
            .then(() => {
                $('#entry-details').modal('hide');
               
                  //this.$Progress.finish();
                  VueListen.$emit('RefreshEntryTable');
            })
            .catch(() => {
                this.$Progress.fail();
            });
            */
          },
          saveDebitEntry(){
            
            /*
            this.form_entry.credit_amount = this.form_entry.amount
            this.save_button_entry_enabled = false;
            this.$Progress.start();
            this.form_entry.put('api/cd/entry/'+this.form_entry.id)
            .then(() => {
                $('#entry-details').modal('hide');
               
                  this.form.amount += this.form_entry.amount;
                  this.form.amount_ex_tax += this.form_entry.amount_ex_tax;
                  this.form.vat += this.form_entry.vat;

                  
                  //console.log(this.ledgers);
                  this.$Progress.finish();
                  VueListen.$emit('RefreshEntryTable');
            })
            .catch(() => {
                this.$Progress.fail();
            });
            */
          },
          deleteEntry(entry_id,entry_amount,entry_amount_ex_tax,entry_vat){
            /*
            this.form_item.delete('api/cd/entry/'+entry_id)
              .then(() => {
                  
                    this.form.amount = parseFloat(this.form.amount - entry_amount).toFixed(2) * 1;
                    this.form.amount_ex_tax = (this.form.amount_ex_tax - entry_amount_ex_tax).toFixed(2) * 1;
                    this.form.vat = (this.form.vat - entry_vat).toFixed(2) * 1;
                    VueListen.$emit('RefreshItemTable');
                    VueListen.$emit('RefreshEntryTable');

              })
              .catch(() => {
                  this.$Progress.fail();
              });
              */
          },
          cancelItem(){
            /*
            //this.$Progress.start();
            this.form_item.delete('api/cd/item/'+this.form_item.id)
            .then(() => {
                $('#entry-items').modal('hide');
                
                  //this.$Progress.finish();

                  VueListen.$emit('RefreshItemTable');
            })
            .catch(() => {
                this.$Progress.fail();
            });
            */
          },
          saveItem(){
            /*
            if(this.form_item.item.length == 0) {
              this.no_item = true;
            } else {
              this.no_item = false;
            }

            if(this.form_item.price == 0) {
              this.no_price = true;
            } else {
              this.no_price = false;
            }
            
            if(this.form_item.quantity == 0) {
              this.no_quantity = true;
            } else {
              this.no_quantity = false;
            }
            
            if (this.no_item || this.no_price || this.no_quantity){
              return false;
            } 

            this.save_button_item_enabled = false;
            

            this.$Progress.start();
            this.form_item.put('api/cd/item/'+this.form_item.id)
            .then(() => {
                $('#entry-items').modal('hide');
                
                  this.form_entry.amount = parseFloat(this.form_entry.amount + this.form_item.sub_total).toFixed(2) * 1;
                  this.form_entry.amount_ex_tax = (this.form_entry.amount_ex_tax + this.form_item.tax_excluded).toFixed(2) * 1;
                  this.form_entry.vat += (this.form_item.vat * 1);
                  
                  this.$Progress.finish();
                  VueListen.$emit('RefreshItemTable');
            })
            .catch(() => {
                this.$Progress.fail();
            });
            */
          },
          deleteItem(item_id,item_sub_total,item_tax_excluded,item_vat){
              /*
              this.form_item.delete('api/cd/item/'+item_id)
              .then(() => {
                  //$('#entry-items').modal('hide');
                  
                    this.form_entry.amount = parseFloat(this.form_entry.amount - item_sub_total).toFixed(2) * 1;
                    this.form_entry.amount_ex_tax = (this.form_entry.amount_ex_tax - item_tax_excluded).toFixed(2) * 1;
                    this.form_entry.vat = (this.form_entry.vat - item_vat).toFixed(2) * 1;
                    
                    VueListen.$emit('RefreshItemTable');
              })
              .catch(() => {
                  this.$Progress.fail();
              });
              */
          },
          branchChange(){
            this.form_entry.branch_id = this.selected_branch.id ;
            this.form_entry.branch_name = this.selected_branch.name;
          },
          payEntry(purchase_id,account_code,account_name,current_balance)
          {
                this.current_balance = current_balance;
                this.current_purchase_id = purchase_id;
                

                this.form_entry.reset();
                
                this.form_entry.transaction_id = this.form.id;
                this.form_entry.transaction_no = this.form.transaction_no;
                this.form_entry.transaction_type = 'PAYMENT';
                this.form_entry.account_code = account_code;
                this.form_entry.account_name = account_name;
                this.form_entry.branch_id = 0;
                this.form_entry.branch_name = 'NA';
                this.save_button_entry_enabled = true;
                this.form_entry.post('api/cd/entry')
                  .then((data)=>{
                    this.form_entry.id = data.data.id;
                    
                    //VueListen.$emit('RefreshItemTable');    
                  })
                  .catch(()=>{
                    //
                  });


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

            this.ledgers.push({ 
                      id: this.form_entry.id,
                      transaction_id: this.form.id, 
                      transaction_no: this.form.transaction_no,
                      transaction_type: this.form.transaction_type,
                      account_code: this.form_entry.account_code,
                      account_name: this.form_entry.account_name,
                      transaction_date: this.form.transaction_date,
                      credit_amount: 0,
                      debit_amount: this.form_entry.amount
                    });

            this.form_entry.put('api/cd/entry/'+this.form_entry.id)
            .then(() => {
              

              $('#entry-payment').modal('hide');

              this.loadPayments();
              this.loadPaymentHistory(account_code);
              
              this.updatePurchase(parseFloat(payment_amount));

              


              //this.addPaymentToPurchseRecord();
              

            })
            .catch(() => {
                this.$Progress.fail();
            });


            this.form_entry.reset();

            
          },



          cancelPayment(account_code){

            this.form_entry.delete('api/cd/entry/'+this.form_entry.id)
            .then(() => {
              $('#entry-payment').modal('hide');
              this.loadPayments();
              this.loadPaymentHistory(account_code);
            })
            .catch(() => {
                this.$Progress.fail();
            });

            this.form_entry.reset();

            $('#entry-payment').modal('hide');
          },
          selectPaymentRow(active_debit_row_id,account_code){
              this.active_debit_row = active_debit_row_id;
              this.form_entry.id = active_debit_row_id;
              this.account_code = account_code;
              this.loadPayments();
              this.loadPaymentHistory(account_code);
          },
          loadPayments() {
              axios.get('api/cd/entries/list?transaction_no='+this.form.transaction_no)
                .then((data)=>{
                  this.entries = data.data;
                })
                .catch(()=>{
                  //
                });
          },
          loadPaymentHistory(account_code) {
                axios.get('api/cd/entries/list?account_code='+account_code)
                .then((data)=>{
                  this.payment_history = data.data;
                })
                .catch(()=>{
                  //
                });

          },
          getPaymentHistotyPage(page){
              
              
              axios.get('api/cd/entries/list?account_code='+this.account_code+'&page='+ page)
                .then((data)=>{
                  this.payment_history = data.data;
                })
                .catch(()=>{
                });
              
          },
          addPaymentToPurchseRecord(){
              
              //this.current_purchase_id
          },
          updatePurchase(payment_amount){
            

            this.purchases.data.map((purchase) => {
              if(!purchase.total_payment){
                purchase.total_payment = 0;
              }
              if(purchase.id == this.current_purchase_id){
                
                return purchase.total_payment = parseFloat(purchase.total_payment) + parseFloat(payment_amount);
              } else {
                return purchase.total_payment = purchase.total_payment;
              }
            })
            
          }
          },

        created() {
            
            
            
            this.loadPayees();
            this.loadBranches();
            this.initChartAccounts();

            this.loadEntryItems();
            this.loadEntries();
            
            this.loadPurchase();


            VueListen.$on('RefreshItemTable',() => {
                this.loadEntryItems();
            });
            
            VueListen.$on('RefreshEntryTable',() => {
                this.loadEntries();
            });
            

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
            
          

        },
        components: {
          
          
        }
    }
</script>
<style type="text/css">
  
</style>