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
                </div>
                    
                <div class="box mt-4">
                    <div class="box-header">
                        <h3 class="box-title">Report</h3>
                    </div>  
                    <div  v-show="true" class="box box-warning mt-2">
                        <div class="col-md-12">
                        <div class="">

                            <!-- /.box-header -->
                            <div id="cd-list" class="box-body table-responsive no-padding">
                            <table class="table table-hover">
                                <tbody>
                                <tr>
                                    <th>Titles</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                </tr>
                                <tr v-for="report in reports" :key="report.id" :class="{ 'table-warning' : active_debit_row == report.id }">
                                    <td>{{ report.account_name }}</td>
                                    <td>{{ report.debit }}</td>
                                    <td>{{ report.credit }}</td>
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
              reports: {},
              running_balance: 0,
              transaction_date: this.getDate(),
              report_type: 'IS',
              active_debit_row: 0
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
                axios.get('api/daily?report_type='+this.report_type+'&transaction_date='+this.transaction_date)
                .then((response)=>{
                  this.reports = response.data;
                  console.log(response.data); 

                })
                .catch(()=>{
                });
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
