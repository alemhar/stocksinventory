<template>
  <transition name="sa-modal-fade">
    <div class="sa-modal-backdrop">
      <div class="sa-modal"
        role="dialog"
        aria-labelledby="modalTitle"
        aria-describedby="modalDescription"
      >
        <header
          class="sa-modal-header"
          id="modalTitle"
        >
          <slot name="header">Search Account
            <button type="button" class="sa-btn-close" @click="close" aria-label="Close modal" > x </button>
          </slot>
        </header>
        <section
          class="sa-modal-body"
          id="modalDescription"
        >
          <slot name="body">
            <div class="col-md-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Users List</h3>
              <div class="box-tools">
                <input type="text" class="form-control col-12" id="searchAccountCode" placeholder="Code">
              </div>
            </div>

            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody><tr>
                  <th>Code</th>
                  <th>Name</th>
                  <th>Select</th>
                </tr>
                <tr v-for="account in accounts.data" :key="account.id">
                  <td>{{ account.account_code }}</td>
                  <td>{{ account.account_name }}</td>
                  <td>
                    <a href="#" @click="selectAccount(account.id)">Select
                      <i class="fa fa-edit"></i>
                    </a>
                  </td>
                </tr>
              </tbody></table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <pagination :data="accounts" @pagination-change-page="getResults"></pagination>
            </div> 
          </div>
          <!-- /.box -->
        </div>
          </slot>
        </section>
        <footer class="sa-modal-footer">
          <slot name="footer">
            I'm the default footer!

            <button
              type="button"
              class="sa-btn-green"
              @click="close"
              aria-label="Close modal"
            >
              Close me!
            </button>
          </slot>
        </footer>
      </div>
    </div>
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