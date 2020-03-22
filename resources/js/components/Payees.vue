<template>
    <div class="container">
        <div class="row mt-5" v-if="$gate.isAdminOrAuthor()">
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
                  <th>Address</th>
                  <th>TIN</th>
                  <th>Created</th>
                  <th>Modify</th>
                </tr>
                <tr v-for="user in payee.data" :key="payee.id">
                  <td>{{ payee.id }}</td>
                  <td>{{ payee.name }}</td>
                  <td>{{ payee.address }}</td>
                  <td>{{ payee.tin }}</td>
                  <td>{{ payee.created_at | formatDate }}</td>
                  <td>
                    <a href="#" @click="editUser(payee)">Edit
                      <i class="fa fa-edit"></i>
                    </a>
                    |
                    <a href="#" @click="deleteUser(payee.id)"><span class="red">Delete</span>
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

        <div class="row mt-5" v-if="!$gate.isAdminOrAuthor()">
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
                <input v-model="form.name" type="text" name="name"
                  placeholder="Name"
                  class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" >
                <has-error :form="form" field="name"></has-error>
              </div>
              <div class="form-group">
                <input v-model="form.address" type="text" name="address"
                  placeholder="Address"
                  class="form-control" :class="{ 'is-invalid': form.errors.has('address') }" >
                <has-error :form="form" field="address"></has-error>
              </div>

              <div class="form-group">
                  <input v-model="form.tin" name="tin" id="tin"
                  placeholder="TIN"
                  class="form-control" :class="{ 'is-invalid': form.errors.has('tin') }">
                  <has-error :form="form" field="tin"></has-error>
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
                  tin: ''
              })
          }
        },
        methods: {
          getResults(page = 1) {
            axios.get('api/payee?page=' + page)
              .then(response => {
                this.users = response.data;
              });
          },
          editPayee(payee){
              this.editmode = true;
              this.form.reset();
              $('#PayeeDetails').modal('show');
              this.form.fill(user);
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
                        swal("Failed!","Failed to delete payee!", "warning");

                      });
                      



                    }
                  });
          },
          loadPayees(){

            if(this.$gate.isAdminOrAuthor()){
                axios.get("api/payee").then(({ data }) => (this.payee = data));
                //axios.get("api/user").then(({ data }) => (this.users = data.data));
            } 
             
          },
          createPayee(){
            
            console.log('Create Payee');
            this.$Progress.start()
            this.form.post('api/payee')
            .then(()=>{
                VueListen.$emit('RefreshUsersTable');
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
                axios.get('api/findPayee?q='+query)
                .then((data)=>{
                  this.users = data.data;
                })
                .catch(()=>{

                });
               //this.loadUsers();
            });
            
            this.loadPayees();

            VueListen.$on('RefreshUsersTable',() => {
              
               this.loadPayees();
            });
            

            //setInterval(() => this.loadUsers(),3000);
        }
    }
</script>
