      <!-- Entry Modal -->
      <div class="modal fade" id="entry-details" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New</h5>
              <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Entry</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form @submit.prevent="editmode ? updatePayee() : createPayee()">
            <div class="modal-body">
              
              <div class="form-group">
                <input v-model="form_entry.account_code" type="text" name="account_code"
                  placeholder="Account Code"
                  class="form-control" :class="{ 'is-invalid': form_entry.errors.has('account_code') }" >
                <has-error :form="form_entry" field="account_code"></has-error>
              </div>
              <div class="form-group">
                <input v-model="form_entry.account_name" type="text" name="account_name"
                  placeholder="Account Name"
                  class="form-control" :class="{ 'is-invalid': form_entry.errors.has('account_name') }" >
                <has-error :form="form_entry" field="account_name"></has-error>
              </div>
              <div class="form-group">
                <input v-model="form_entry.entry_name" type="text" name="entry_name"
                  placeholder="Name"
                  class="form-control" :class="{ 'is-invalid': form_entry.errors.has('entry_name') }" >
                <has-error :form="form_entry" field="entry_name"></has-error>
              </div>
              <div class="form-group">
                <input v-model="form_entry.entry_description" type="text" name="entry_description"
                  placeholder="Description"
                  class="form-control" :class="{ 'is-invalid': form_entry.errors.has('entry_description') }" >
                <has-error :form="form_entry" field="entry_description"></has-error>
              </div>

              <div class="form-group">
                  <input v-model="form_entry.branch_code" name="branch_code" id="branch_code"
                  placeholder="Branch Code"
                  class="form-control" :class="{ 'is-invalid': form_entry.errors.has('branch_code') }">
                  <has-error :form="form_entry" field="branch_code"></has-error>
              </div>




              <div class="form-group">
                  <input v-model="form_entry.amount" name="amount" id="amount"
                  placeholder="Amount"
                  class="form-control" :class="{ 'is-invalid': form_entry.errors.has('amount') }">
                  <has-error :form="form_entry" field="amount"></has-error>
              </div>

              <div class="form-group">
                  <input v-model="form_entry.amount_ex_tax" name="amount_ex_tax" id="amount_ex_tax"
                  placeholder="Amount Excluding Tax"
                  class="form-control" :class="{ 'is-invalid': form_entry.errors.has('amount_ex_tax') }">
                  <has-error :form="form_entry" field="amount_ex_tax"></has-error>
              </div>


              <div class="form-group">
                  <input v-model="form_entry.vat" name="vat" id="vat"
                  placeholder="Vat"
                  class="form-control" :class="{ 'is-invalid': form_entry.errors.has('vat') }">
                  <has-error :form="form_entry" field="vat"></has-error>
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
      <!-- Entry Modal -->

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



      
<template>
  <transition name="modal-fade">
    <div class="modal-backdrop">
      <div class="modal"
        role="dialog"
        aria-labelledby="modalTitle"
        aria-describedby="modalDescription"
      >
        <header
          class="modal-header"
          id="modalTitle"
        >
          <slot name="header">
            This is the default tile!

            <button
              type="button"
              class="btn-close"
              @click="close"
              aria-label="Close modal"
            >
              x
            </button>
          </slot>
        </header>
        <section
          class="modal-body"
          id="modalDescription"
        >
          <slot name="body">
            I'm the default body!
          </slot>
        </section>
        <footer class="modal-footer">
          <slot name="footer">
            I'm the default footer!

            <button
              type="button"
              class="btn-green"
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
    name: 'modal',
    methods: {
      close() {
        this.$emit('close');
      },
    },
  };
</script>


<style>
  .modal-backdrop {
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

  .modal {
    background: #FFFFFF;
    box-shadow: 2px 2px 20px 1px;
    overflow-x: auto;
    display: flex;
    flex-direction: column;
  }

  .modal-header,
  .modal-footer {
    padding: 15px;
    display: flex;
  }

  .modal-header {
    border-bottom: 1px solid #eeeeee;
    color: #4AAE9B;
    justify-content: space-between;
  }

  .modal-footer {
    border-top: 1px solid #eeeeee;
    justify-content: flex-end;
  }

  .modal-body {
    position: relative;
    padding: 20px 10px;
  }

  .btn-close {
    border: none;
    font-size: 20px;
    padding: 20px;
    cursor: pointer;
    font-weight: bold;
    color: #4AAE9B;
    background: transparent;
  }

  .btn-green {
    color: white;
    background: #4AAE9B;
    border: 1px solid #4AAE9B;
    border-radius: 2px;
  }
  .modal-fade-enter,
  .modal-fade-leave-active {
    opacity: 0;
  }

  .modal-fade-enter-active,
  .modal-fade-leave-active {
    transition: opacity .5s ease
  }
</style>