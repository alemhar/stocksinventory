<template>
  <transition name="sa-modal-fade">
    <!-- Accounts Modal -->
      <div class="modal fade" id="account-list" tabindex="-1" role="dialog" aria-labelledby="account-list" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <!--
              <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New</h5>
              <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Entry</h5>
              -->
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <!-- form @submit.prevent="editmode ? updatePayee() : createPayee()" -->

            <div class="modal-body">
            
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
              

            </div>
            <div class="modal-footer">
              
            </div>

            <!-- /form -->
          </div>
        </div>
      </div>
      <!-- Accounts Modal -->
  </transition>
</template>
<script>
  
  export default {
    data: function() {
      return {
        name: 'modal',
        accounts : {}
                  
      };
    },
    methods: {
      close() {
        this.$emit('close');
      },
      getResults(page = 1) {
            axios.get('api/chartaccount?page=' + page)
              .then(response => {
                this.accounts = response.data;
              });
          },
      loadAccounts(){
            //if(this.$gate.isAdminOrAuthor()){
                axios.get("api/chartaccount").then(({ data }) => (this.accounts = data));
                //axios.get("api/user").then(({ data }) => (this.users = data.data));
            //} 
          },     
    },
    created() {
      this.loadAccounts();
    },  
    components: {
      //BootstrapTable
    }



    
  };
      
  
</script>

<style>
  .sa-modal-backdrop {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: rgba(0, 0, 0, 0.3);
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .sa-modal {
    background: #FFFFFF;
    box-shadow: 2px 2px 20px 1px;
    overflow-x: auto;
    display: flex;
    flex-direction: column;
    width: 650px;
    height: 400px;
  }

  .sa-modal-header,
  .sa-modal-footer {
    padding: 5px;
    /*display: flex;*/
  }

  .sa-modal-header {
    border-bottom: 1px solid #eeeeee;
    color: #4AAE9B;
    justify-content: space-between;
  }

  .sa-modal-footer {
    border-top: 1px solid #eeeeee;
    justify-content: flex-end;
  }

  .sa-modal-body {
    position: relative;
    padding: 20px 10px;
  }

  .sa-btn-close {
    border: none;
    font-size: 20px;
    padding: 20px;
    cursor: pointer;
    font-weight: bold;
    color: #4AAE9B;
    background: transparent;
  }

  .sa-btn-green {
    color: white;
    background: #4AAE9B;
    border: 1px solid #4AAE9B;
    border-radius: 2px;
  }
  .sa-modal-fade-enter,
  .sa-modal-fade-leave-active {
    opacity: 0;
  }

  .sa-modal-fade-enter-active,
  .sa-modal-fade-leave-active {
    transition: opacity .5s ease
  }
</style>