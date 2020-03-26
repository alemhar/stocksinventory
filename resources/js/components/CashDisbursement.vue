<template>
    <div class="container">
        <div class="row mt-1" v-if="$gate.isAdminOrAuthor()">
        <div class="col-md-12">
          <div class="box mt-4">
            <!-- general form elements -->
          <div class="box box-warning">


            <!-- /.box-header -->
            <!-- form start -->
            <form role="form">
              <div class="box-header with-border">
                <h3 class="box-title">Cash Disbursement</h3>
                <div class="box-tools">
                  <button type="button" v-show="!cd_created" class="btn btn-success" @click="createCD">Create <i class="fas fa-plus-circle fa-fw"></i></button>
                  <!-- button type="button"  class="btn btn-success"  v-show="cd_created" @click="saveCD">Save <i class="fas fa-plus-circle fa-fw"></i></button-->
                  <button  type="button" class="btn btn-danger"  v-show="cd_created" @click="cancelCD">Cancel <i class="fas fa-plus-circle fa-fw"></i></button>
                </div>
              </div>
              <div class="box-body row">
                <!--Left Col-->
                <div class="col-9">
                  <!-- div class="form-group">
                    <label for="inputPayeesName">Payee</label>
                    <input type="text" class="form-control col-12" id="inputPayeesName" placeholder="Payees Name">
                  </div -->

                  <!-- div class="form-group" -->
                    <!--select name='payee_id' v-model='form.payee_id' class="form-control col-12">
                      <option value=''>Please select payee...</option>
                      <option v-for="payee in payees.data" v-bind:value="payee.id">{{ payee.name }}</option>
                    </select -->
                  <!-- /div -->

                  <div class="form-group">
                    <label for="inputPayeesName">Payee</label>
                    <dynamic-select 
                      :options="payees.data"
                      option-value="id"
                      option-text="name"
                      placeholder="Type to search"
                      v-on:input="eventChild"
                      v-model="current_payee"
                      v-show="!cd_created"
                      />
                    <p v-show="no_payee" class="empty-field-message">** Please select payee!</p>  
                  
                    <input  v-show="cd_created" v-bind:readonly="cd_created" type="text" class="form-control col-12" id="inputPayee" v-model="current_payee.name">
                  </div>
                  

                  <div class="form-group">
                    <input v-bind:readonly="cd_created" type="text" class="form-control col-12" id="inputPayeesAddress" placeholder="Address" v-model="current_payee.address">
                  </div>
                  <div class="form-group row">
                    <div class="col-6">
                      <input v-bind:readonly="cd_created" type="text" class="form-control" id="inputPayeesTIN" placeholder="TIN"  v-model="current_payee.tin">
                    </div>
                    <!-- div class="col-6">
                      <select v-model="form.tax_type" class="form-control col-12">
                        <option value="TAX TYPE">TAX TYPE</option>
                        <option value="VAT REG">VAT REG</option>
                        <option value="NON VAT">NON VAT</option>
                        <option value="VAT EXEMPT">VAT EXEMPT</option>
                        <option value="ZERO RATED">ZERO RATED</option>
                      </select>
                    </div-->  
                  </div>
                  <div class="form-group">
                    <label>Account</label>
                    <!-- select v-bind:readonly="cd_created" v-model="form.account_code" class="form-control col-12">
                      <option>SALARIES AND WAGES</option>
                      <option>TRAININGS AND SEMINARS</option>
                      <option>TRAVEL AND TRANSPORTATION</option>
                      <option>MEALS AMD SNACKS</option>
                      <option>REPRESENTATION EXPENSES</option>
                    </select -->
                    <div class="input-group">
                    
                    <input v-bind:readonly="cd_created" type="text" class="form-control col-10" id="inputAccountCode" placeholder="Code"  v-model="form.account_code">

                    <input readonly="true" type="text" class="form-control col-10" id="inputAccountName" placeholder="Account Name" v-model="form.account_name">

                    <span class="input-group-btn col-2">
                        <button type="button" v-show="!cd_created" class="btn btn-success" @click="searchAccount"><i class="fas fa-search fa-fw"></i></button>

                    </span>
                    </div>
                    <p v-show="no_account_code" class="empty-field-message">** Please select account!</p>
                  </div>
                </div>
                <!--Right Col-->
                <div class="col-3">
                  <div class="form-group">
                    <label for="inputReferenceNo">Reference No.</label>
                    <input v-bind:readonly="cd_created" type="text"  v-model="form.reference_no" class="form-control col-12" id="inputReferenceNo" placeholder="Reference No">
                    <p v-show="no_reference_no" class="empty-field-message">** Please enter reference number!</p>
                  </div>
                  <div class="form-group">
                    <input v-bind:readonly="cd_created" type="date" v-model="form.transaction_date" class="form-control col-12" id="inputDate" placeholder="Date">
                  </div>
                  <div class="form-group">
                    <input v-bind:readonly="cd_created" type="text" v-model="form.transaction_no" class="form-control col-12" id="inputDCNo" placeholder="CD Number">
                  </div>
                  <div class="form-group">
                    <label for="inputAmount">Amount</label>
                    <input v-bind:readonly="cd_created" type="text"  v-model="form.transaction_amt" class="form-control col-12" id="inputAmount" placeholder="Amount">
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
            </form>
          </div>

          <div  v-show="cd_created" class="box box-warning mt-2">
            <div class="col-md-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Debits</h3>
                  <div class="box-tools">
                    <button class="btn btn-success" @click="newEntry">Add Items <i class="fas fa-plus-circle fa-fw"></i></button>
                  </div>
                </div>

                <!-- /.box-header -->
                <div id="debits-list" class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tbody>
                      <tr>
                        <th>Account No.</th>
                        <th>Name</th>
                        <th>Item</th>
                        <th>Description</th>
                        <th>Branch</th>
                        <th>Tax Type</th>
                        <th>Amount</th>
                      </tr>
                      <tr>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                      </tr>
                      <tr>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                      </tr>
                      <tr>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
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
            <div v-show="cd_created" class="box box-warning mt-2">
              
              <div class="form-group col-12 float-right">
                <div class="row mt-2">
                <label for="inputAmountExclusiveTax" class="col-sm-9 col-form-label" style="text-align: right;">Amount Exclusive of Tax</label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" id="inputAmountExclusiveTax" placeholder="Amount Exclusive of Tax">
                </div>
                </div>
              </div>
              <div class="form-group col-12 float-right">
                <div class="row">
                
                <label for="inputVAT" class="col-sm-9 col-form-label" style="text-align: right;">VAT</label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" id="inputVAT" placeholder="VAT">
                </div>
              </div>
              </div>
              <div class="form-group col-12 float-right">
                <div class="row">
                
                <label for="inputTotalAmount" class="col-sm-9 col-form-label" style="text-align: right;">Total Amount</label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" id="inputTotalAmount" placeholder="Total Amount">
                </div>
              </div>
              </div>
            
            </div>
          
          </div>
          <!-- /.box -->
        </div>
        </div>

        <div class="row mt-1" v-if="!$gate.isAdminOrAuthor()">
          <not-found></not-found>
        </div>  
    

      

      <search-account v-model="isModalVisible">
      </search-account>

      

    </div>
</template>
<script>
    //import { ModelSelect } from 'vue-search-select'
    //import { DynamicSelect } from 'vue-dynamic-select'
    //import { BasicSelect } from 'vue-search-select'
    
    export default {
        data() {
          return {
              isModalVisible: false,
              editmode: false,
              cd_created: false,
              no_payee: false,
              no_reference_no: false,
              no_account_code: false,
              cd : {},
              form: new Form({

                  id:'',
                  name : '',
                  payee_id: '',
                  reference_no: '',
                  transaction_no: '',
                  transaction_date: this.getDate(),
                  transaction_amt: '',
                  tax_type: 'TAX TYPE',
                  account_code: '',
                  account_name:''
                  
                  
              }),
              form_entry: new Form({

                  id:'',
                  account_code : '',
                  account_name: '',
                  entry_name: '',
                  entry_description: '',
                  branch_code: '',
                  tax_type: 'TAX TYPE',
                  amount: '',
                  amount_ex_tax: '',
                  vat: '',
                  transaction_date: this.getDate()
                  
                  
              }),
              payees: {},
              current_payee: {},
              options: [
                { value: '1', text: 'aa' + ' - ' + '1' },
                { value: '2', text: 'ab' + ' - ' + '2' },
                { value: '3', text: 'bc' + ' - ' + '3' },
                { value: '4', text: 'cd' + ' - ' + '4' },
                { value: '5', text: 'de' + ' - ' + '5' }
              ],
              item: {}

          }
        },
        methods: {

          // getResults(page = 1) {
          //   axios.get('api/user?page=' + page)
          //     .then(response => {
          //       this.users = response.data;
          //     });
          // },
          // editUser(user){
          //     this.editmode = true;
          //     this.form.reset();
          //     $('#UserDetails').modal('show');
          //     this.form.fill(user);
          // },
          // newUser(){
          //     this.editmode = false;
          //     this.form.reset();
          //     $('#UserDetails').modal('show');
          // },
          // deleteUser(id){
          //     swal.fire({
          //           title: 'Are you sure?',
          //           text: "You won't be able to revert this!",
          //           type: 'warning',
          //           showCancelButton: true,
          //           confirmButtonColor: '#3085d6',
          //           cancelButtonColor: '#d33',
          //           confirmButtonText: 'Yes, delete it!'
          //         }).then((result) => {
          //           if (result.value) {
                      
          //             this.form.delete('api/user/'+id)
          //             .then(()=>{
          //                 swal.fire(
          //                   'Deleted!',
          //                   'User has been deleted.',
          //                   'success'
          //                 );

          //                 Fire.$emit('RefreshUsersTable');
          //             })
          //             .catch(()=>{
          //               swal("Failed!","Failed to delete user!", "warning");

          //             });
                      



          //           }
          //         });
          // },
          // loadUsers(){

          //   if(this.$gate.isAdminOrAuthor()){
          //       axios.get("api/user").then(({ data }) => (this.users = data));
          //       //axios.get("api/user").then(({ data }) => (this.users = data.data));
          //   } 
             
          // },
          loadPayees(){

            if(this.$gate.isAdminOrAuthor()){
                axios.get("api/payee").then(({data}) => (this.payees = data ));
                //axios.get("api/user").then(({ data }) => (this.users = data.data));
            } 
             
          },
          eventChild(Obj){
            console.log(Obj.id);
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
            
          },
          saveCD(){
            this.cd_created = false;
          },
          cancelCD(){
            this.cd_created = false;
          },
          newEntry(){
              this.editmode = false;
              this.form_entry.reset();
              $('#entry-details').modal('show');
          },
          searchAccount() {
            this.isModalVisible = true;
          },
          closeSearchAccount() {
            this.isModalVisible = false;
          }
          // ,
          // createUser(){
            

          //   this.$Progress.start()
          //   this.form.post('api/user')
          //   .then(()=>{
          //       VueListen.$emit('RefreshUsersTable');
          //       $('#UserDetails').modal('hide');

          //       toast.fire({
          //         type: 'success',
          //         title: 'User created successfully'
          //       })
          //       this.$Progress.finish();
          //   })
          //   .catch(()=>{

          //   });
            
          // },
          // updateUser(){
          //   console.log('Edit User');
          //   this.$Progress.start();
          //   this.form.put('api/user/'+this.form.id)
          //   .then(() => {
          //       $('#UserDetails').modal('hide');
          //       swal.fire(
          //           'Updated!',
          //           'User information has been updated.',
          //           'success'
          //         );
          //         this.$Progress.finish();
          //         VueListen.$emit('RefreshUsersTable');
          //   })
          //   .catch(() => {
          //       this.$Progress.fail();
          //   });

            

          //}


        },

        created() {
            /*
            VueListen.$on('Search',() => {
                let query = this.$parent.search;
                axios.get('api/findUser?q='+query)
                .then((data)=>{
                  this.users = data.data;
                })
                .catch(()=>{

                });
               //this.loadUsers();
            });
            */
            
            //this.loadUsers();
            this.loadPayees();

            VueListen.$on('RefreshUsersTable',() => {
              
               this.loadPayees();
               //this.loadUsers();
            });

            
            //console.log(this.payees);

            //setInterval(() => this.loadUsers(),3000);
        },
        components: {
          //DynamicSelect
          
        }
    }
</script>
<style type="text/css">

</style>