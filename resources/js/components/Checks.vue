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
                            <td style="text-align: center;"><button :disabled="check.status == 'BANK' || check.status == 'REVERSE'" class="btn btn-success" @click="depositCheck(check.transaction_no,check.id)"><i class="fas fa-sign-in-alt"></i></button></td>
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


        


    
        
    </div>
    </div>
    </div>
    
</template>
<script>
    export default {
        data() {
          return {
              user_id: null,
              checks: {},
              //check: {},
              transaction_date: this.getDate(),
              transaction_type: 'ALL',
              active_row: 0,
              reverse: false
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