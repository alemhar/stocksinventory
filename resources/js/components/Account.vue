<style>
.widget-user-header{
    background-position: center center;
    background-size: cover;
    height: 250px !important;
}
.widget-user .card-footer{
    padding: 0;
}
</style>

<template>
    <div class="container">
        <div class="row justify-content-center">
          <!-- tab -->

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                        <!--  li class="nav-item"><a class="nav-link" href="#activity" data-toggle="tab">Activity</a></li -->
                        <li class="nav-item"><a class="nav-link active show" href="#settings" data-toggle="tab">Settings</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Activity Tab -->
                            <!-- div class="tab-pane" id="activity">
                                <h3 class="text-center">Display User Activity</h3>
                            </div -->
                            <!-- Setting Tab -->
                            <div class="tab-pane active show" id="settings">
                                <form class="form-horizontal">
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label">Name</label>

                                    <div class="col-sm-12">
                                    <input type="" v-model="form.name" class="form-control" id="inputName" placeholder="Name" :class="{ 'is-invalid': form.errors.has('name') }">
                                     <has-error :form="form" field="name"></has-error>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                                    <div class="col-sm-12">
                                    <input type="email" v-model="form.email" class="form-control" id="inputEmail" placeholder="Email"  :class="{ 'is-invalid': form.errors.has('email') }">
                                     <has-error :form="form" field="email"></has-error>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputcompany" class="col-sm-2 control-label">Company</label>

                                    <div class="col-sm-12">
                                    <input type="text" v-model="form.company" class="form-control" id="inputcompany" placeholder="Company Name"  :class="{ 'is-invalid': form.errors.has('company') }">
                                     <has-error :form="form" field="company"></has-error>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputline_of_business" class="col-sm-2 control-label">Line of Business</label>

                                    <div class="col-sm-12">
                                    <input type="text" v-model="form.line_of_business" class="form-control" id="inputline_of_business" placeholder="Line of Business"  :class="{ 'is-invalid': form.errors.has('line_of_business') }">
                                     <has-error :form="form" field="line_of_business"></has-error>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="inputtin1" class="col control-label">TIN</label>
                                    <div class="col">
                                        <input type="text" v-model="form.tin1" class="form-control" id="inputtin1" placeholder="TIN1"  :class="{ 'is-invalid': form.errors.has('tin1') }">
                                    </div>
                                    <div class="col">
                                        <input type="text" v-model="form.tin2" class="form-control" id="inputtin2" placeholder="TIN2"  :class="{ 'is-invalid': form.errors.has('tin2') }">
                                    </div>
                                    <div class="col">
                                        <input type="text" v-model="form.tin3" class="form-control" id="inputtin3" placeholder="TIN3"  :class="{ 'is-invalid': form.errors.has('tin2') }">
                                    </div>
                                    <div class="col">
                                        <input type="text" v-model="form.branch_code" class="form-control" id="inputbranch_code" placeholder="BC"  :class="{ 'is-invalid': form.errors.has('branch_code') }">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="inputtin2" class="col-sm-2 control-label">TIN2</label>
                                    <div class="col-sm-12">
                                    <input type="text" v-model="form.tin2" class="form-control" id="inputtin2" placeholder="tin2"  :class="{ 'is-invalid': form.errors.has('tin2') }">
                                     <has-error :form="form" field="tin2"></has-error>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputtin3" class="col-sm-2 control-label">TIN3</label>
                                    <div class="col-sm-12">
                                    <input type="text" v-model="form.tin3" class="form-control" id="inputtin3" placeholder="tin3"  :class="{ 'is-invalid': form.errors.has('tin3') }">
                                     <has-error :form="form" field="tin3"></has-error>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputbranch_code" class="col-sm-2 control-label">Branch Code</label>
                                    <div class="col-sm-12">
                                    <input type="text" v-model="form.branch_code" class="form-control" id="inputbranch_code" placeholder="Branch Code"  :class="{ 'is-invalid': form.errors.has('branch_code') }">
                                     <has-error :form="form" field="branch_code"></has-error>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputrdo_code" class="col-sm-2 control-label">RDO Code</label>
                                    <div class="col-sm-12">
                                    <input type="text" v-model="form.rdo_code" class="form-control" id="inputrdo_code" placeholder="RDO Code"  :class="{ 'is-invalid': form.errors.has('rdo_code') }">
                                     <has-error :form="form" field="rdo_code"></has-error>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label for="inputaddress" class="col-sm-2 control-label">Address 1</label>
                                    <div class="col-sm-12">
                                    <input type="text" v-model="form.address" class="form-control" id="inputaddress" placeholder="Address 1"  :class="{ 'is-invalid': form.errors.has('address') }">
                                     <has-error :form="form" field="address"></has-error>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputaddress2" class="col-sm-2 control-label">Address 2</label>
                                    <div class="col-sm-12">
                                    <input type="text" v-model="form.address2" class="form-control" id="inputaddress2" placeholder="Address 2"  :class="{ 'is-invalid': form.errors.has('address2') }">
                                     <has-error :form="form" field="address2"></has-error>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label for="inputzip_code" class="col-sm-2 control-label">Zip Code</label>
                                    <div class="col-sm-12">
                                    <input type="text" v-model="form.zip_code" class="form-control" id="inputzip_code" placeholder="Zip Code"  :class="{ 'is-invalid': form.errors.has('zip_code') }">
                                     <has-error :form="form" field="zip_code"></has-error>
                                    </div>
                                </div>


                                <!-- div class="form-group">
                                    <label for="inputExperience" class="col-sm-2 control-label">Experience</label>

                                    <div class="col-sm-12">
                                    <textarea  v-model="form.bio" class="form-control" id="inputExperience" placeholder="Experience" :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
                                     <has-error :form="form" field="bio"></has-error>
                                    </div>
                                </div -->
                                <!-- div class="form-group">
                                    <label for="photo" class="col-sm-2 control-label">Profile Photo</label>
                                    <div class="col-sm-12">
                                        <input type="file" @change="updateProfile" name="photo" class="form-input">
                                    </div>

                                </div -->
                                <!-- div class="form-group">
                                    <label for="input-font-size" class="col-sm-2 control-label">Readability</label>
                                    <div class="col-sm-12">
                                    <select v-model="form.font_size" class="form-control col-3" id="input-font-size">
                                        <option value="SMALL">SMALL</option>
                                        <option value="MEDIUM">MEDIUM</option>
                                        <option value="LARGE">LARGE</option>
                                    </select>
                                    </div>
                                </div -->    
                                <div class="form-group">
                                    <label for="password" class="col-sm-12 control-label">Password (leave empty if not changing)</label>

                                    <div class="col-sm-12">
                                    <input type="password"
                                        v-model="form.password"
                                        class="form-control"
                                        id="password"
                                        placeholder="Password"
                                        :class="{ 'is-invalid': form.errors.has('password') }"
                                    >
                                     <has-error :form="form" field="password"></has-error>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-12">
                                    <button @click.prevent="updateInfo" type="submit" class="btn btn-success">Update</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
                <!-- /.nav-tabs-custom -->
          </div>
          <!-- end tabs -->

        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                    user_id: document.querySelector('meta[name="user-id"]').getAttribute('content'),
                 form: new Form({
                    id:'',
                    name : '',
                    email: '',
                    password: '',
                    type: '',
                    bio: '',
                    photo: '',
                    font_size: '',
                    tin1: '',
                    tin2: '',
                    tin3: '',
                    branch_code: '',
                    rdo_code: '',
                    line_of_busines: '',
                    address1: '',
                    address2: '',
                    zip_code: ''
                    
                })
            }
        },
        mounted() {
            console.log('Component Account.vue mounted.')
        },
        methods:{
            getProfilePhoto(){
                let photo = (this.form.photo.length > 200) ? this.form.photo : "img/profile/"+ this.form.photo ;
                return photo;
                
            },
            updateInfo(){
                this.$Progress.start();
                
                if(this.form.password == ''){
                    this.form.password = undefined;
                }
                
                this.form.put('api/account')
                .then(()=>{
                    VueListen.$emit('RefreshUsersInfo');
                    this.$Progress.finish();
                })
                .catch(() => {
                    this.$Progress.fail();
                });

            },
            updateProfile(e){
                let file = e.target.files[0];
                let reader = new FileReader();
                let limit = 1024 * 1024 * 2;
                if(file['size'] > limit){
                    swal({
                        type: 'error',
                        title: 'Oops...',
                        text: 'You are uploading a large file',
                    })
                    return false;
                }
                reader.onloadend = (file) => {
                    this.form.photo = reader.result;
                }
                reader.readAsDataURL(file);
            }
        },
        created() {

            axios.get("api/account")
            .then(({ data }) => (this.form.fill(data)));

            VueListen.$on('RefreshUsersInfo',() => {
                axios.get("api/account")
                .then(({ data }) => (this.form.fill(data)));
            });
            
        }
    }
</script>
