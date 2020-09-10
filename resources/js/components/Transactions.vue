<template>

    <div class="container">
        <div class="row mt-1" v-if="$gate.isAdminOrUser()">
        <div class="">
            <div class="input-group">
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <span class="input-group-text inputGroup-sizing-default">Account</span>
                </div>
                <input type="text" class="form-control col-2" id="inputAccountCode" placeholder="Code" @keyup.enter="loadLedger(account_code)" v-model="account_code">
                <input readonly="true" type="text" class="form-control col-9" id="inputAccountName" placeholder="Account Name" v-model="account_name">
                <span class="input-group-btn col-1">
                    <button type="button" class="btn btn-success" @click="searchAccountModal"><i class="fas fa-search fa-fw"></i></button>
                </span>
                </div>
            </div>
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <span class="input-group-text inputGroup-sizing-default">Date</span>
                </div>
                <input  type="date"  class="form-control col-12" id="inputDate" placeholder="Date">
            </div>
        </div>

        <div class="col-md-12">
          <div class="box mt-4">
          <div class="box-header">
            <h3 class="box-title">Transactions</h3>
            
          </div>  
              <div  v-show="true" class="box box-warning mt-2">
                <div class="col-md-12">
                  <div class="box">

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
              running_balance: 0
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
            loadLedger(account_code) {
                if(account_code.trim()  === "" ){
                  return false;
                }

                axios.get('api/ledgers/'+account_code)
                    .then((data)=>{
                        this.running_balance = 0;
                        this.ledgers = data.data;
                        //console.log(this.ledgers);
                    })
                    .catch(()=>{
                    //
                    });
            }  
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