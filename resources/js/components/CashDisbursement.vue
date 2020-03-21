<template>
    <div class="container">
        <div class="row mt-1" v-if="$gate.isAdminOrAuthor()">
        <div class="col-md-12">
          <div class="box mt-4">
            <!-- general form elements -->
          <div class="box box-primary">

            <div class="box-header with-border">
              <h3 class="box-title">Cash Disbursement</h3>
              <div class="box-tools">
                <button class="btn btn-success" @click="newCD">New CD <i class="fas fa-plus-circle fa-fw"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputPayeesName">Payee</label>
                  <input type="text" class="form-control col-6" id="inputPayeesName" placeholder="Payees Name">
                </div>
                <div class="form-group">
                  <input type="text" class="form-control col-6" id="inputPayeesAddress" placeholder="Address">
                </div>
                <div class="form-group">
                  <input type="text" class="form-control col-3" id="inputPayeesTIN" placeholder="TIN"><input type="text" class="form-control col-3" id="inputPayeesTaxType" placeholder="Tax Type">
                </div>
                <div class="form-group">
                  <label>Account</label>
                  <select class="form-control col-6">
                    <option>SALARIES AND WAGES</option>
                    <option>TRAININGS AND SEMINARS</option>
                    <option>TRAVEL AND TRANSPORTATION</option>
                    <option>MEALS AMD SNACKS</option>
                    <option>REPRESENTATION EXPENSES</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="inputPayeesAmount">Amount</label>
                  <input type="text" class="form-control col-3" id="inputPayeesAmount" placeholder="Amount">
                </div>

                <!-- div class="form-group">
                  <label for="exampleInputFile">File input</label>
                  <input type="file" id="exampleInputFile">

                  <p class="help-block">Example block-level help text here.</p>
                </div -->
                <div class="checkbox">
                  <label>
                    <input type="checkbox"> with VAT
                  </label>
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="submit" class="btn btn-danger">Cancel</button>
              </div>
            </form>
          </div>
          <!-- /.box -->
       
          </div>
          <!-- /.box -->
        </div>
        </div>

        <div class="row mt-1" v-if="!$gate.isAdminOrAuthor()">
          <not-found></not-found>
        </div>  
      <!-- Modal -->
      <div class="modal fade" id="UserDetails" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New</h5>
              <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update User's Info</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form @submit.prevent="editmode ? updateUser() : createUser()">
            <div class="modal-body">
              
              <div class="form-group">
                <input v-model="form.name" type="text" name="name"
                  placeholder="Name"
                  class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" >
                <has-error :form="form" field="name"></has-error>
              </div>
              <div class="form-group">
                <input v-model="form.email" type="email" name="email"
                  placeholder="Email Address"
                  class="form-control" :class="{ 'is-invalid': form.errors.has('email') }" >
                <has-error :form="form" field="email"></has-error>
              </div>

              <div class="form-group">
                  <textarea v-model="form.bio" name="bio" id="bio"
                  placeholder="Short bio for user (Optional)"
                  class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
                  <has-error :form="form" field="bio"></has-error>
              </div>


              <div class="form-group">
                  <select name="type" v-model="form.type" id="type" class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                      <option value="">Select User Role</option>
                      <option value="admin">Admin</option>
                      <option value="user">Standard User</option>
                      <!-- option value="author">Author</option -->
                  </select>
                  <has-error :form="form" field="type"></has-error>
              </div>

              <div class="form-group">
                  <label>Password</label>
                  <input v-model="form.password" type="password" name="password" id="password"
                  class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                  <has-error :form="form" field="password"></has-error>
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
              users : {},
              form: new Form({

                  id:'',
                  name : '',
                  email: '',
                  password: '',
                  type: '',
                  bio: '',
                  photo: ''
              })
          }
        },
        methods: {
          getResults(page = 1) {
            axios.get('api/user?page=' + page)
              .then(response => {
                this.users = response.data;
              });
          },
          editUser(user){
              this.editmode = true;
              this.form.reset();
              $('#UserDetails').modal('show');
              this.form.fill(user);
          },
          newUser(){
              this.editmode = false;
              this.form.reset();
              $('#UserDetails').modal('show');
          },
          deleteUser(id){
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
                      
                      this.form.delete('api/user/'+id)
                      .then(()=>{
                          swal.fire(
                            'Deleted!',
                            'User has been deleted.',
                            'success'
                          );

                          Fire.$emit('RefreshUsersTable');
                      })
                      .catch(()=>{
                        swal("Failed!","Failed to delete user!", "warning");

                      });
                      



                    }
                  });
          },
          loadUsers(){

            if(this.$gate.isAdminOrAuthor()){
                axios.get("api/user").then(({ data }) => (this.users = data));
                //axios.get("api/user").then(({ data }) => (this.users = data.data));
            } 
             
          },
          createUser(){
            

            this.$Progress.start()
            this.form.post('api/user')
            .then(()=>{
                VueListen.$emit('RefreshUsersTable');
                $('#UserDetails').modal('hide');

                toast.fire({
                  type: 'success',
                  title: 'User created successfully'
                })
                this.$Progress.finish();
            })
            .catch(()=>{

            });
            
          },
          updateUser(){
            console.log('Edit User');
            this.$Progress.start();
            this.form.put('api/user/'+this.form.id)
            .then(() => {
                $('#UserDetails').modal('hide');
                swal.fire(
                    'Updated!',
                    'User information has been updated.',
                    'success'
                  );
                  this.$Progress.finish();
                  VueListen.$emit('RefreshUsersTable');
            })
            .catch(() => {
                this.$Progress.fail();
            });

            

          }


        },

        created() {

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
            
            this.loadUsers();

            VueListen.$on('RefreshUsersTable',() => {
              
               this.loadUsers();
            });
            

            //setInterval(() => this.loadUsers(),3000);
        }
    }
</script>
