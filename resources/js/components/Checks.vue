<template>

    <div class="container">
        <div class="row mt-1" v-if="$gate.isAdminOrUser()">
        

        <div class="col-md-12">
            <div class="row mt-3">
                <div class="col-4">
                    <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" for="inputGroupSelect01">Options</span>
                    </div>
                    <select v-model="transaction_type" class="custom-select" id="inputGroupSelect01">
                        <option value="ALL" selected>All</option>
                        <option value="CD">Cash Disbursements</option>
                        <option value="CR">Cash Receipts</option>
                        <option value="PAYMENT">Payments</option>
                        <option value="COLLECTION">Collections</option>
                        <option value="GENERAL">General</option>
                        <option value="REVERSE">Reverse</option>
                        
                    </select>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text inputGroup-sizing-default">Date</span>
                        </div>
                        <input  type="date" v-model="transaction_date" class="form-control col-12" id="inputDate" placeholder="Date">
                    </div>
                    <button @click="loadChecks" class="btn btn-primary float-right">Search</button>
                </div>
            </div>
            
          <div class="box mt-4">
          <div class="box-header">
            <h3 class="box-title">Checks</h3>
            
          </div>  
              <div  v-show="true" class="box box-warning mt-2">
                <div class="col-md-12">
                  <div class="">

                    <!-- /.box-header -->
                    <div id="cd-list" class="box-body table-responsive no-padding">
                      <table class="table table-hover">
                        <tbody>
                          <tr>
                            <th>Date</th>
                            <th>Trans. #</th>
                            <th>Type</th>
                            <th>Ref. #</th>
                            <th>Check #</th>
                            <th>Bank</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Transfer</th>
                            <th>Option</th>
                            
                          </tr>
                          
                          <tr v-for="check in checks" :key="check.id" :class="{ 'table-warning' : active_row == check.id }">
                            <td>{{ check.transaction_date }}</td>
                            <td>{{ check.transaction_no }}</td>
                            <td>{{ check.transaction_type }}</td>
                            <td>{{ check.reference_no }}</td>
                            <td>{{ check.check_no }}</td>
                            <td>{{ check.check_bank }}</td>
                            <td>{{ check.check_amount }}</td>
                            <td>{{ check.status }}</td>
                            <td style="text-align: center;"><button :disabled="check.status == 'BANK' || check.status == 'REVERSE'" class="btn btn-success" @click="depositCheck(check.transaction_no,check.id,check.check_amount,check.transaction_type)"><i class="fas fa-sign-in-alt"></i></button></td>
                            <td>
                                <button :disabled="check.status == 'BANK' || check.status == 'REVERSE'" class="btn btn-danger" @click="reverseTransaction(transaction.transaction_no,transaction.id)">Reverse</button>
                            </td>
                          </tr>
                          
                      </tbody>
                    </table>
                    </div>
                    <div class="box-footer">
                      <!-- pagination :data="cds" @pagination-change-page="getCDs"></pagination -->
                    </div> 

                  </div>
                  <!-- /.box -->
                </div>

              </div>  
              <!-- /.box -->
        </div>


      <!-- Deposit Modal
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


      <div class="modal fade" id="entry-deposit" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content" style="width: 800px;">
            <div class="modal-header">
              <h5 class="modal-title" id="addNewLabel">Select Account</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <!-- form onsubmit="return false;" -->
            <div class="modal-body">
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text inputGroup-sizing-default">Code</span>
                </div>
                <input v-model="account_code" type="text" name="account_code" readonly class="form-control col-4" aria-describedby="inputGroup-sizing-default">
                
                <span class="input-group-btn col-1">
                    <button type="button" class="btn btn-success" @click="searchAccountModal('header')"><i class="fas fa-search fa-fw"></i></button>
                </span>
              </div>
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text inputGroup-sizing-default">Account</span>
                </div>
                <input v-model="account_name" type="text" name="account_name" class="form-control" readonly aria-describedby="inputGroup-sizing-default">
              </div>

                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <span class="input-group-text inputGroup-sizing-default">Date</span>
                    </div>
                    <input type="date" v-model="transaction_date" class="form-control col-12" id="inputDate" placeholder="Date">
                </div>
                

                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <span class="input-group-text inputGroup-sizing-default">Description/Bank Details</span>
                  </div>

                    <input v-model="description" name="description" id="description"
                    class="form-control" aria-describedby="inputGroup-sizing-default">
                </div>

              

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" @click="cancelEntry">Cancel</button>
              <button type="button" :disabled="!save_button_entry_enabled" class="btn btn-success" @click="postTransaction">Post</button>
            </div>

            <!-- /form -->
          </div>
        </div>
      </div>
      <!-- Deposit Modal -->

      <!-- Search Account Modal -->

      <div class="modal fade" id="select-account" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              
              
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
              
            </div>

            </form>
          </div>
        </div>
      </div>
      <!-- Search Account Modal -->
        


    
        
    </div>
    </div>
    </div>
    
</template>
<script>
    export default {
        data() {
          return {
              transaction_type: 'ALL',
              transactions: [],
              transaction_entry_id: 0,
              transaction_no: '',
              user_id: document.querySelector('meta[name="user-id"]').getAttribute('content'),
              checks: {},
              //check: {},
              transaction_date: this.getDate(),
              current_date: this.getDate(),
              active_row: 0,
              account_code: 0,
              account_name: '',
                account_type: '',
                sub_account_type: '',
                main_code: 0,
                main_account: '',
                type: '',  
              description: '',
              save_button_entry_enabled: true,
              searchText: '',
              chart_of_accounts: {},
              check_amount: 0,
              id: 0,
              reverse: false
          }
        },
        methods: {
            initChartAccounts(){
              axios.get('api/chartaccount?headerordetail=header&transaction_type=CHECKS')
                .then((data)=>{
                  this.chart_of_accounts = data.data;
                })
                .catch(()=>{
                  //
                });
            },
            SearchIt() {
              let query = this.searchText;
              axios.get('api/searchAccount?q='+query+'&headerordetail=header&transaction_type=CHECKS')
                .then((data)=>{
                  this.chart_of_accounts = data.data;
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
            searchAccountModal(headerOrDetail = 'header'){
              this.headerOrDetail = headerOrDetail;
              //this.searchText = this.form.account_code;
              this.searchText = '';
              //this.loadChartAccounts(headerOrDetail);
              $('#select-account').modal('show');
            },
            selectAccount(
                        account_code  = null,
                        account_name = null, 
                        account_type,
                        sub_account_type,
                        main_code,
                        main_account,
                        type
                      ){

                this.account_code = account_code;
                this.account_name = account_name;

                this.account_type = account_type;
                this.sub_account_type = sub_account_type;
                this.main_code = main_code;
                this.main_account = main_account;
                this.type = type;

                $('#select-account').modal('hide');  
            },
            loadChecks(){
              this.checks = {};
              //this.check = {};
              axios.get('api/checks?transaction_type='+this.transaction_type+'&transaction_date='+ this.transaction_date)
                .then((response)=>{
                  this.checks = response.data.data;
                  //console.log(data.data); 

                })
                .catch(()=>{
                });
              
            },
            depositCheck(transaction_no,id,check_amount,transaction_type){
                this.id = id;
                this.account_code = 0;
                this.account_name = '';
                this.description = '';

                this.account_type = '';
                this.sub_account_type = '';
                this.main_code = '';
                this.main_account = '';
                this.type = '';

                this.check_amount = check_amount;
                this.current_transaction_type = transaction_type;
                this.save_button_entry_enabled =  true;
                $('#entry-deposit').modal('show');
                
            },
            createSerialNumber(){
                // Get current date
                var d = new Date();
                // Get pirmative value of date
                var n = d.valueOf();
                // Return concatenated primative value with the user id. 
                return ""+n+this.user_id;
            },
            postTransaction(){
                if(this.current_date < this.transaction_date){
                    swal.fire({
                        title: 'Invalid date!',
                        text: "Future/Advance date not accepted.",
                        type: 'info',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        
                    });
                    return false;
                }
                
                if(this.account_code == 0) {
                    swal.fire({
                        title: 'Missing account code!',
                        text: "Please provide account",
                        type: 'info',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        
                    });
                    return false;
                }
                this.transaction_no = this.createSerialNumber();

                ++this.transaction_entry_id;
                this.transactions.push({ 
                    account_type: 'ASSETS',
                    sub_account_type: 'CURRENT ASSETS',
                    main_code: '',
                    main_account: '',
                    type: 'NA',
                    entity_type: 'NA',
                    transaction_entry_id: this.transaction_entry_id,
                    payee_id: 0,
                    branch_id: 0,
                    account_code: 11011100,
                    account_name: 'Cash On Hand',
                    reference_no: '',
                    transaction_no: this.transaction_no,
                    transaction_type: 'DEPOSIT',
                    transaction_date: this.transaction_date,
                    amount: this.check_amount,
                    credit_amount: this.check_amount,
                    debit_amount: 0,
                    total_payment: 0,
                    total_collection: 0,
                    amount_ex_tax: 0,
                    vat: 0,
                    wtax_code: 0,
                    wtax: 0,
                    user_id: this.user_id,
                    status: 'CONFIRMED',
                    depreciation_date: '',
                    depreciated_id: 0,
                    useful_life: 0,
                    salvage_value: 0,
                    taxed: 'NA',
                    tax_of_id: 0,
                    tax_of_account: 0,
                    description: this.description
                });

                ++this.transaction_entry_id;
                this.transactions.push({ 
                    account_type: this.account_type,
                    sub_account_type: this.sub_account_type,
                    main_code: this.main_code,
                    main_account: this.main_account,
                    type: this.type,
                    entity_type: 'NA',
                    transaction_entry_id: this.transaction_entry_id,
                    payee_id: 0,
                    branch_id: 0,
                    account_code: this.account_code,
                    account_name: this.account_name,
                    reference_no: '',
                    transaction_no: this.transaction_no,
                    transaction_type: 'DEPOSIT',
                    transaction_date: this.transaction_date,
                    amount: this.check_amount,
                    credit_amount: 0,
                    debit_amount: this.check_amount,
                    total_payment: 0,
                    total_collection: 0,
                    amount_ex_tax: 0,
                    vat: 0,
                    wtax_code: 0,
                    wtax: 0,
                    user_id: this.user_id,
                    status: 'CONFIRMED',
                    depreciation_date: '',
                    depreciated_id: 0,
                    useful_life: 0,
                    salvage_value: 0,
                    taxed: 'NA',
                    tax_of_id: 0,
                    tax_of_account: 0,
                    description: this.description
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


                    

                    // Update Check Status START ********************
                    axios.post('api/update_check_status', {
                        id: this.id,
                        status: 'DEPOSIT',
                    })
                    .then((response)=>{

                    })
                    .catch(()=>{
                        
                    });
                    // Update Check Status END ********************


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
                        //this.dataReset();
                        this.transactions = {};
                        this.transaction_entry_id = 0;                                
                    }
                    });
            

            },
            reverseTransaction(transaction_no,active_row_id){
                /*
                if(!this.transaction.length){
                    swal.fire({
                        title: 'Warning!',
                        text: "Transaction details is empty, please load by clicking view icon of the transaction.",
                        type: 'info',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        
                    });
                    
                    this.reverse = false;
                    return false;
                } else {
                    this.reverse = true;
                }

                
                let credit_amount = 0;
                let debit_amount = 0;
                for (let i = 0; i < this.transaction.length; i++) {
                    //if (this.transaction[i].transaction_entry_id === transaction_entry_id) {
                        
                        credit_amount = this.transaction[i].credit_amount;
                        debit_amount = this.transaction[i].debit_amount;

                        this.transaction[i].credit_amount = debit_amount;
                        this.transaction[i].debit_amount = credit_amount;
                        this.transaction[i].status = 'REVERSE';
                        console.log(this.transaction[i].status); 

                        //return;
                    //}
                }
                for (let i = 0; i < this.transactions.length; i++) {
                    if (this.transactions[i].transaction_no === transaction_no) {
                        this.transactions[i].status = 'REVERSE';
                        
                    }
                }
                */

            },
            saveReverse(transaction_no){

                /*
                axios.get('api/reverse_transaction?transaction_no='+transaction_no)
                .then((response)=>{
                  //this.transaction = response.data.data;
                  //console.log(response); 

                })
                .catch(()=>{
                });

                // Save Transactions START
                

                let rawData = {
                    transactions: this.transaction
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
                    this.reverse = false;
                    //console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });
                // Save Transactions END

                */
            },
            cancelEntry(){
                //this.$Progress.start();
                $('#entry-deposit').modal('hide');
    
            },
        
        },

        created() {
            this.initChartAccounts();
            this.user_id = document.querySelector('meta[name="user-id"]').getAttribute('content');
            /*
            $(document).on('hidden.bs.modal', '.modal', function () {
                $('.modal:visible').length && $(document.body).addClass('modal-open');
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