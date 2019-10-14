<template>
    <div class="container">
        <div class="row mt-5">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Users List</h3>
              <div class="box-tools">
                <button class="btn btn-success"  data-toggle="modal" data-target="#addNew">Add New  <i class="fas fa-user-plus fa-fw"></i></button>
              </div>
            </div>

            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody><tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Type</th>
                  <th>Registered</th>
                  <th>Modify</th>
                </tr>
                <tr v-for="user in users" :key="user.id">
                  <td>{{ user.id }}</td>
                  <td>{{ user.name }}</td>
                  <td>{{ user.email }}</td>
                  <td>{{ user.type | upText }}</td>
                  <td>{{ user.created_at | formatDate }}</td>
                  <td>
                    <a href="#">Edit
                      <i class="fa fa-edit"></i>
                    </a>
                    |
                    <a href="#" @click="deleteUser(user.id)"><span class="red">Delete</span>
                      <i class="fa fa-trash"></i>
                    </a>
                  </td>
                </tr>
              </tbody></table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>


      <!-- Modal -->
      <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addNewLabel">Add New</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form @submit.prevent="createUser">
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
              <button type="submit" class="btn btn-success">Create</button>
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
          deleteUser(id){
              Swal.fire({s
                      title: 'Are you sure?',
                      text: "You won't be able to revert this!",
                      type: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                      // Send request to the server
                      if (result.value) {
                        Swal.fire(
                          'Deleted!',
                          'User has been deleted.',
                          'success'
                        )
                      }
                    })
          },
          loadUsers(){
             axios.get("api/user").then(({ data }) => (this.users = data.data));
          },
          createUser(){
            

            this.$Progress.start()
            this.form.post('api/user')
            .then(()=>{
                Fire.$emit('AfterUserCreate');
                $('#addNew').modal('hide')

                toast.fire({
                  type: 'success',
                  title: 'User created successfully'
                })
                this.$Progress.finish()
            })
            .catch(()=>{

            });
            
          }


        },

        created() {
            this.loadUsers();
            Fire.on('AfterUserCreate',() => {
              this.loadUsers();
            });
            //setInterval(() => this.loadUsers(),3000);
        }
    }
</script>
