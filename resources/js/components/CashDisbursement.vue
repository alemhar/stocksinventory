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
                  <button type="submit" class="btn btn-primary">Save</button>
                  <button class="btn btn-success" @click="">New <i class="fas fa-plus-circle fa-fw"></i></button>
                </div>
              </div>
              <div class="box-body row">
                <!--Left Col-->
                <div class="col-9">
                  <div class="form-group">
                    <label for="inputPayeesName">Payee</label>
                    <input type="text" class="form-control col-12" id="inputPayeesName" placeholder="Payees Name">
                  </div>

                  <div class="form-group">
                    <!--select name='payee_id' v-model='form.payee_id' class="form-control col-12">
                      <option value=''>Please select payee...</option>
                      <option v-for="payee in payees.data" v-bind:value="payee.id">{{ payee.name }}</option>
                      
                      
                    </select -->


                  </div>

                  <div class="form-group">
                    <dynamic-select 
                      :options="payees.data"
                      option-value="name"
                      option-text="name"
                      placeholder="type to search"
                      v-model="form.payee_id" />
                  </div>
                  

                  <div class="form-group">
                    <input type="text" class="form-control col-12" id="inputPayeesAddress" placeholder="Address">
                  </div>
                  <div class="form-group row">
                    <div class="col-6">
                      <input type="text" class="form-control" id="inputPayeesTIN" placeholder="TIN">
                    </div>
                    <div class="col-6">
                      <select class="form-control col-12">
                        <option>TAX TYPE</option>
                        <option>VAT REG</option>
                        <option>NON VAT</option>
                        <option>VAT EXEMPT</option>
                        <option>ZERO RATED</option>
                      </select>
                    </div>  
                  </div>
                  <div class="form-group">
                    <label>Account</label>
                    <select class="form-control col-12">
                      <option>SALARIES AND WAGES</option>
                      <option>TRAININGS AND SEMINARS</option>
                      <option>TRAVEL AND TRANSPORTATION</option>
                      <option>MEALS AMD SNACKS</option>
                      <option>REPRESENTATION EXPENSES</option>
                    </select>
                  </div>
                  

                </div>
                <!--Right Col-->
                <div class="col-3">
                  <div class="form-group">
                    <label for="inputReferenceNo">Reference No.</label>
                    <input type="text" class="form-control col-12" id="inputReferenceNo" placeholder="Reference No">
                  </div>
                  <div class="form-group">
                    <input type="text" class="form-control col-12" id="inputDate" placeholder="Date">
                  </div>
                  <div class="form-group">
                    <input type="text" class="form-control col-12" id="inputDCNo" placeholder="CD Number">
                  </div>
                  <div class="form-group">
                    <label for="inputAmount">Amount</label>
                    <input type="text" class="form-control col-12" id="inputAmount" placeholder="Amount">
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
            </form>
          </div>

          <div class="box box-warning mt-2">
            <div class="col-md-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Debits</h3>
                  <div class="box-tools">
                    <button class="btn btn-success" @click="">Add Items <i class="fas fa-plus-circle fa-fw"></i></button>
                  </div>
                </div>

                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
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
            <div class="box box-warning mt-2">
              
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
      

    </div>
</template>
<script>
    //import { ModelSelect } from 'vue-search-select'
    //import { DynamicSelect } from 'vue-dynamic-select'
    export default {
        data() {
          return {
              editmode: false,
              cd : {},
              form: new Form({

                  id:'',
                  name : '',
                  payee_id: '',
                  password: '',
                  type: '',
                  bio: '',
                  photo: '',
                  payee_id: ''
              }),
              payees: {}
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
