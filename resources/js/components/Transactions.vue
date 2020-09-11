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
                    <select v-model="transction_type" class="custom-select" id="inputGroupSelect01">
                        <option value="ALL" selected>All</option>
                        <option value="CD">Cash Disbursements</option>
                        <option value="CR">Cash Receipts</option>
                        <option value="PURCHASE">Puchases</option>
                        <option value="SALES">Sales</option>
                        <option value="PAYMENT">Payments</option>
                        <option value="COLLECTION">Collections</option>
                    </select>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text inputGroup-sizing-default">Date</span>
                        </div>
                        <input  type="date" v-model="transaction_date" class="form-control col-12" id="inputDate" placeholder="Date">
                    </div>
                    <button @click="loadTransactions" class="btn btn-primary float-right">Search</button>
                </div>
            </div>
            
          <div class="box mt-4">
          <div class="box-header">
            <h3 class="box-title">Transactions</h3>
            
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
                            <th>Transaction #</th>
                            <th>Transaction Type</th>
                            <th>Debits</th>
                            <th>Credits</th>
                            <th>Option</th>
                            
                          </tr>
                          
                          <tr v-for="transaction in transactions.data" :key="transaction.id">
                            <td>{{ transaction.transaction_date }}</td>
                            <td>{{ transaction.transaction_no }}</td>
                            <td>{{ transaction.transaction_type }}</td>
                            <td>{{ transaction.reference_no }}</td>
                            <td>{{ ledtransactionger.status }}</td>
                            <td>button</td>
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


        <div class="box mt-4">
          <div class="box-header">
            <h3 class="box-title">Transactions</h3>
            
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
                            <th>Transaction #</th>
                            <th>Transaction Type</th>
                            <th>Debits</th>
                            <th>Credits</th>
                            <th>Balance</th>
                            
                          </tr>
                          
                          <tr v-for="(ledger, index) in ledgers.data" :key="ledger.id">
                            <td>{{ ledger.transaction_date }}</td>
                            <td>{{ ledger.transaction_no }}</td>
                            <td>{{ ledger.transaction_type }}</td>
                            <td>{{ ledger.debit_amount }}</td>
                            <td>{{ ledger.credit_amount }}</td>
                            <td>{{ runningBalance[index] }}</td>
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

        <div class="row mt-1" v-if="!$gate.isAdminOrUser()">
          <not-found></not-found>
        </div>


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
              user_id: null,
              chart_of_accounts: {},
              account_code: '',
              account_name: '',
              searchText: '',
              ledgers: {},
              transactions: {},
              running_balance: 0,
              transaction_date: this.getDate(),
              transction_type: 'ALL'
          }
        },
        methods: {
            initChartAccounts(){
              axios.get('api/chartaccount?transaction_type=LEDGER')
                .then((data)=>{
                  this.chart_of_accounts = data.data;
                })
                .catch(()=>{
                  //
                });
            },
            SearchIt() {
              let query = this.searchText;
              let headerOrDetail = this.headerOrDetail;
              axios.get('api/searchAccount?q='+query+'&transaction_type=LEDGER')
                .then((data)=>{
                  this.chart_of_accounts = data.data;
                })
                .catch(()=>{
                  //
                });
            },
            searchAccountModal(){
                this.initChartAccounts();
                this.searchText = '';
                this.running_balance = 0;
                $('#select-account').modal('show');
            },
            selectAccount(account_code  = null,account_name = null){
                if (account_code != null && account_name != null){
                    this.account_name = account_name;
                    this.account_code = account_code;
                }
                this.running_balance = 0;
                $('#select-account').modal('hide');  
                this.loadLedger(account_code);

            },
            getDate() {
                const toTwoDigits = num => num < 10 ? '0' + num : num;
                let today = new Date();
                let year = today.getFullYear();
                let month = toTwoDigits(today.getMonth() + 1);
                let day = toTwoDigits(today.getDate());
                return `${year}-${month}-${day}`;
            },
            loadTransactions(){
              
              axios.get('api/transactions?transaction_type='+this.transaction_type+'&transaction_date='+ this.transaction_date)
                .then((data)=>{
                  //this.transactions = data.data;
                    console.log(data.data); 

                })
                .catch(()=>{
                });
              
            },
        
        },

        created() {
            /*
            this.initChartAccounts();
            this.user_id = document.querySelector('meta[name="user-id"]').getAttribute('content');
            $(document).on('hidden.bs.modal', '.modal', function () {
                $('.modal:visible').length && $(document.body).addClass('modal-open');
            });
            */
            
        },
        computed: {
            /*
            runningBalance(){
                 return this.ledgers.data.map((ledger) => {
                    this.running_balance = this.running_balance + Number(ledger.debit_amount - ledger.credit_amount);
                    
                    return this.running_balance.toFixed(2);
                 });
            }  
            */          

        },
        components: {
          
          
        }
    }
</script>
<style type="text/css">

</style>