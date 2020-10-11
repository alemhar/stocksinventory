<template>
    <div class="container">
        <div class="row mt-5" v-if="$gate.isAdminOrUser()">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Payees List</h3>
              <div class="box-tools">
                <button class="btn btn-success" @click="newPayee">Add New  <i class="fas fa-user-plus fa-fw"></i></button>
              </div>
            </div>

            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody><tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>TIN</th>
                  <th>Modify</th>
                </tr>
                <tr v-for="payee in payee.data" :key="payee.id">
                  <td>{{ payee.id }}</td>
                  <td>{{ payee.name }}</td>
                  <td>{{ payee.tin }}</td>
                  <td>
                    <a href="#" @click="editPayee(payee)">Edit
                      <i class="fa fa-edit"></i>
                    </a>
                    |
                    <a href="#" @click="deletePayee(payee.id)"><span class="red">Delete</span>
                      <i class="fa fa-trash"></i>
                    </a>
                  </td>
                </tr>
              </tbody></table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <pagination :data="payee" @pagination-change-page="getResults"></pagination>
            </div> 
          </div>
          <!-- /.box -->
        </div>
        </div>

        <div class="row mt-5" v-if="!$gate.isAdminOrUser()">
          <not-found></not-found>
        </div>  



      <!-- Modal -->
      <div class="modal fade" id="PayeeDetails" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New</h5>
              <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Payee's Info</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form @submit.prevent="editmode ? updatePayee() : createPayee()">
            <div class="modal-body">
              
              <div class="form-group">
                <label for="inputName" class="col-sm-2 control-label pl-0">Name</label>
                <input v-model="form.name" type="text" name="name"
                  placeholder="Name"
                  class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" >
                <has-error :form="form" field="name"></has-error>
              </div>
              <div class="form-group">
                <label for="inputaddress" class="col-sm-2 control-label pl-0">Address</label>
                <input v-model="form.address" type="text" name="address"
                  placeholder="Address"
                  class="form-control" :class="{ 'is-invalid': form.errors.has('address') }" >
                <has-error :form="form" field="address"></has-error>
              </div>
              <div class="form-group">
                <label for="inputcity" class="col-sm-2 control-label pl-0">City</label>
                <input v-model="form.city" type="text" name="city"
                  placeholder="City"
                  class="form-control" :class="{ 'is-invalid': form.errors.has('city') }" >
                <has-error :form="form" field="city"></has-error>
              </div>
              <div class="form-group">
                <label for="inputphone" class="col-sm-2 control-label pl-0">Phone</label>
                <input v-model="form.phone" type="text" name="phone"
                  placeholder="Phone"
                  class="form-control" :class="{ 'is-invalid': form.errors.has('phone') }" >
                <has-error :form="form" field="phone"></has-error>
              </div>

              <div class="form-group">
                  <label for="inputTIN" class="col-sm-2 control-label pl-0">TIN</label>
                  <input v-model="form.tin" name="tin" id="tin"
                  placeholder="TIN"
                  class="form-control" :class="{ 'is-invalid': form.errors.has('tin') }">
                  <has-error :form="form" field="tin"></has-error>
              </div>

              <div class="form-group">
                  <label for="inputcontactemail" class="col-sm-2 control-label pl-0">Email</label>
                  <input v-model="form.contactemail" name="contactemail" id="tin"
                  placeholder="Email"
                  class="form-control" :class="{ 'is-invalid': form.errors.has('contactemail') }">
                  <has-error :form="form" field="contactemail"></has-error>
              </div>

              <div class="form-group">
                  <label for="inputpayable" class="col-sm-2 control-label pl-0">Payable</label>
                  <input v-model="form.payable" name="payable" id="payable"
                  placeholder="Payable"
                  class="form-control" :class="{ 'is-invalid': form.errors.has('payable') }">
                  <has-error :form="form" field="payable"></has-error>
              </div>

              <div class="form-group">
                  <label for="inputreceivable" class="col-sm-2 control-label pl-0">Receivable</label>
                  <input v-model="form.receivable" name="receivable" id="receivable"
                  placeholder="Receivable"
                  class="form-control" :class="{ 'is-invalid': form.errors.has('receivable') }">
                  <has-error :form="form" field="receivable"></has-error>
              </div>


              <div class="form-group">
                  <label for="inputreceivable" class="col-sm-2 control-label pl-0">Entity Type</label>
                  <select v-model="form.entity_type" class="form-control col-12" aria-describedby="inputGroup-sizing-default">
                    <option value="PRIVATE">PRIVATE</option>
                    <option value="GOVERNMENT">GOVERNMENT</option>
                    
                  </select>
              </div>



            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
              <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
            </div>

            </form>
          </div>
        </div>
      </div>

    </div>
</template>
<script>
    export default {
        data() {
          return {
              editmode: false,
              payee : {},
              form: new Form({

                  id:'',
                  name : '',
                  address: '',
                  city: '',
                  phone: '',
                  tin: '',
                  contactemail: '',
                  payable: '',
                  receivable: '',
                  entity_type: ''
              })
          }
        },
        methods: {
          getResults(page = 1) {
            //axios.get("api/payee").then(({ data }) => (this.payee = data));
            axios.get('api/payee?page=' + page)
              .then(response => {
                this.payee = response.data;
              });
          },
          editPayee(payee){
              this.editmode = true;
              this.form.reset();
              $('#PayeeDetails').modal('show');
              this.form.fill(payee);
          },
          newPayee(){
              this.editmode = false;
              this.form.reset();
              $('#PayeeDetails').modal('show');
          },
          deletePayee(id){
              swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                  }).then((result) => {
                    if (result.value) {
                      
                      this.form.delete('api/payee/'+id)
                      .then(()=>{
                          swal.fire(
                            'Deleted!',
                            'Payee has been deleted.',
                            'success'
                          );

                          //Fire.$emit('RefreshPayeesTable');
                          VueListen.$emit('RefreshPayeesTable');
                      })
                      .catch(()=>{
                        swal("Failed!","Failed to delete payee!", "warning");

                      });
                      



                    }
                  });
          },
          loadPayees(){

            if(this.$gate.isAdminOrUser()){
                axios.get("api/payee").then(({ data }) => (this.payee = data));
                //axios.get("api/user").then(({ data }) => (this.users = data.data));
            } 
             
          },
          createPayee(){
            
            console.log('Create Payee');
            this.$Progress.start()
            this.form.post('api/payee')
            .then(()=>{
                VueListen.$emit('RefreshPayeesTable');
                $('#PayeeDetails').modal('hide');

                toast.fire({
                  type: 'success',
                  title: 'Payee created successfully'
                })
                this.$Progress.finish();
            })
            .catch(()=>{

            });
            
          },
          updatePayee(){
            console.log('Edit Payee');
            this.$Progress.start();
            this.form.put('api/payee/'+this.form.id)
            .then(() => {
                $('#PayeeDetails').modal('hide');
                swal.fire(
                    'Updated!',
                    'Payee information has been updated.',
                    'success'
                  );
                  this.$Progress.finish();
                  VueListen.$emit('RefreshPayeesTable');
            })
            .catch(() => {
                this.$Progress.fail();
            });

            

          }


        },

        created() {

            VueListen.$on('Search',() => {
                let query = this.$parent.search;
                axios.get('api/findPayee?q='+query)
                .then((data)=>{
                  this.users = data.data;
                })
                .catch(()=>{

                });
               //this.loadUsers();
            });
            
            this.loadPayees();

            VueListen.$on('RefreshPayeesTable',() => {
              
               this.loadPayees();
            });
            

            //setInterval(() => this.loadUsers(),3000);
        }
    }
</script>
