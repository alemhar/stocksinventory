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
              //running_balance: 0,
              from_transaction_date: this.getDate(),
              to_transaction_date: this.getDate(),
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
            generateReport(){
                /*
                axios.get('api/daily?report_type='+this.report_type+'&transaction_date='+this.transaction_date)
                .then((response)=>{
                  this.reports = response.data;
                  console.log(response.data); 

                })
                .catch(()=>{
                });
                */
            },
            generateReportIS(){
                
                axios.get('api/daily?sub_account_type=SALES_AND_REVENUES&from_transaction_date='+this.from_transaction_date+'&to_transaction_date='+this.to_transaction_date)
                .then((response)=>{
                  this.sales = response.data;
                  console.log(response.data); 

                })
                .catch(()=>{
                });

                var doc = new jspdf();
                doc.setFontSize(14);
                doc.text('Sales',15,15);
                doc.setFontSize(10);
                doc.text('test',15,25);
                
                doc.save('test.pdf');

            }

        
        },

        created() {
            
        },
        computed: {
    

        },
        components: {
          
          
        }
    }
</script>
