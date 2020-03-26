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