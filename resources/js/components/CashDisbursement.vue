<template>

    <div class="container">
        <div class="row mt-1" v-if="$gate.isAdminOrAuthor()">
        <div class="col-md-12">
          <div class="box mt-4">
            <!-- general form elements -->
          <div class="box box-warning">


            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" @submit.prevent="createCD()">
              <div class="box-header with-border">
                <h3 class="box-title">Cash Disbursement</h3>
                
                <div class="box-tools">
                  <button type="submit" v-show="!cd_created" class="btn btn-success">Create <i class="fas fa-plus-circle fa-fw"></i></button>
                  <!-- @click="createCD()"  -->
                  <!-- button type="button"  class="btn btn-success"  v-show="cd_created" @click="saveCD">Save <i class="fas fa-plus-circle fa-fw"></i></button-->
                  <button  type="button" class="btn btn-danger"  v-show="cd_created" @click="cancelCD">Cancel <i class="fas fa-window-close fa-fw"></i></button>

                </div>
              </div>
              <div class="box-body row">
                <!--Left Col-->
                <div class="col-8">
                  <!-- div class="form-group">
                    <label for="inputPayeesName">Payee</label>
                    <input type="text" class="form-control col-12" id="inputPayeesName" placeholder="Payees Name">
                  </div -->

                  <!-- div class="form-group" -->
                    <!--select name='payee_id' v-model='form.payee_id' class="form-control col-12">
                      <option value=''>Please select payee...</option>
                      <option v-for="payee in payees.data" v-bind:value="payee.id">{{ payee.name }}</option>
                    </select -->
                  <!-- /div -->

                  <div class="input-group mb-2">
                    <!-- label for="inputPayeesName">Payee</label -->
                    <!--dynamic-select 
                      :options="payees.data"
                      option-value="id"
                      option-text="name"
                      placeholder="Type to search"
                      v-on:input="eventChild"
                      v-model="current_payee"
                      v-show="!cd_created"
                      / -->
                    <div class="input-group-prepend">
                      <span class="input-group-text inputGroup-sizing-default">Payee</span>
                    </div>  
                    <input v-model="current_payee_name" v-bind:readonly="cd_created" type="text" class="form-control col-12" id="inputPayeeName" placeholder="Payees Name">
                      
                    <span class="input-group-btn col-1">
                        <button type="button" v-show="!cd_created" class="btn btn-success" @click="searchPayeeModal"><i class="fas fa-search fa-fw"></i></button>
                    </span>  

                    <p v-show="no_payee" class="empty-field-message">** Please select payee!</p>  
                  

                  </div>
                  

                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="inputGroup-sizing-default">Address</span>
                    </div>
                    <input v-bind:readonly="cd_created" type="text" class="form-control col-12" id="inputPayeesAddress" placeholder="Address" v-model="current_payee_address">
                  </div>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="inputGroup-sizing-default">TIN</span>
                    </div>
                      <input v-bind:readonly="cd_created" type="text" class="form-control" id="inputPayeesTIN" placeholder="TIN"  v-model="current_payee_tin">
                    
                    <!-- div class="col-6">
                      <select v-model="form.tax_type" class="form-control col-12">
                        <option value="TAX TYPE">TAX TYPE</option>
                        <option value="VAT REG">VAT REG</option>
                        <option value="NON VAT">NON VAT</option>
                        <option value="VAT EXEMPT">VAT EXEMPT</option>
                        <option value="ZERO RATED">ZERO RATED</option>
                      </select>
                    </div-->  
                  </div>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text inputGroup-sizing-default">Account</span>
                    </div>
                    
                    <!-- select v-bind:readonly="cd_created" v-model="form.account_code" class="form-control col-12">
                      <option>SALARIES AND WAGES</option>
                      <option>TRAININGS AND SEMINARS</option>
                      <option>TRAVEL AND TRANSPORTATION</option>
                      <option>MEALS AMD SNACKS</option>
                      <option>REPRESENTATION EXPENSES</option>
                    </select -->
                    <input v-bind:readonly="cd_created" type="text" class="form-control col-2" id="inputAccountCode" placeholder="Code"  v-model="form.account_code">

                    <input readonly="true" type="text" class="form-control col-9" id="inputAccountName" placeholder="Account Name" v-model="form.account_name">

                    <span class="input-group-btn col-1">
                        <button type="button" v-show="!cd_created" class="btn btn-success" @click="searchAccountModal('header')"><i class="fas fa-search fa-fw"></i></button>

                    </span>
                    
                    
                  </div>
                  <div class="input-group mb-2">
                    <p v-show="no_account_code" class="empty-field-message">** Please select account!</p>
                  </div>  
                </div>
                <!--Right Col-->
                <div class="col-4">
                  <div class="input-group mb-2">
                    <!--label for="inputReferenceNo">Reference No.</label -->
                    <div class="input-group-prepend">
                      <span class="input-group-text inputGroup-sizing-default">Ref. #</span>
                    </div>

                    <input v-bind:readonly="cd_created" type="text"  v-model="form.reference_no" class="form-control col-12" id="inputReferenceNo" placeholder="Reference No">
                    <p v-show="no_reference_no" class="empty-field-message">** Please enter reference number!</p>
                  </div>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text inputGroup-sizing-default">Date</span>
                    </div>
                    <input v-bind:readonly="cd_created" type="date" v-model="form.transaction_date" class="form-control col-12" id="inputDate" placeholder="Date">
                  </div>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text inputGroup-sizing-default">CD #</span>
                    </div>
                    <input v-bind:readonly="cd_created" type="text" v-model="form.transaction_no" class="form-control col-12" id="inputDCNo" placeholder="CD Number">
                  </div>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text inputGroup-sizing-default">Amount</span>
                    </div>
                    
                    <input v-bind:readonly="cd_created" type="text"  v-model="form.amount" class="form-control col-12" id="inputAmount" placeholder="Amount">
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
            </form>
          </div>




      <!-- Debit List     
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      -->


          <div  v-show="cd_created" class="box box-warning mt-2">
            <div class="col-md-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Debits</h3>
                  <div class="box-tools">
                    <button class="btn btn-success" @click="newEntry">Add Items <i class="fas fa-plus-circle fa-fw"></i></button>
                  </div>
                </div>


                <!-- /.box-header -->
                <div id="debits-list" class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tbody>
                      <tr>
                        <th>Account No.</th>
                        <th>Name</th>
                        <th>Branch</th>
                        <th>Amount</th>
                      </tr>
                      <tr>
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
                      </tr>
                      <tr>
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


      <!-- Item List     
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      MAIN FORM ITEMS TABLE
      -->


          <div v-show="cd_created"  class="box box-warning mt-2">
            <div class="col-md-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Items</h3>
                  <!--div class="box-tools">
                    <button class="btn btn-success" @click="newItem">Add Items <i class="fas fa-plus-circle fa-fw"></i></button>
                  </div-->
                </div>
                <!-- /.box-header -->
                <div id="item-list" class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tbody>
                      <tr>
                        <th>Account No.</th>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Tax Type</th>
                        <th>Sub-Total</th>
                        <th>Tax Excluded</th>
                        <th>Vat</th>
                      </tr>
                      <tr @click="selectDebitRow(1)" :class="{ 'table-warning' : active_debit_row == 1 }" >
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                      </tr>
                      <tr @click="selectDebitRow(2)" :class="{ 'table-warning' : active_debit_row == 2 }">
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                      </tr>
                      <tr  @click="selectDebitRow(3)" :class="{ 'table-warning' : active_debit_row == 3 }" >
                        <td> - </td>
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

            <div v-show="cd_created" class="box box-warning mt-2">
              
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

      <!-- Entry Modal
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      -->


      <div class="modal fade" v-show="cd_created" id="entry-details" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add Entry</h5>
              <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Entry</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <!-- form onsubmit="return false;" -->
            <div class="modal-body">
              
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text inputGroup-sizing-default">Code</span>
                </div>
                <input v-model="form_entry.account_code" type="text" name="account_code" 
                  
                  class="form-control col-4" :class="{ 'is-invalid': form_entry.errors.has('account_code') }" aria-describedby="inputGroup-sizing-default">
                <has-error :form="form_entry" field="account_code"></has-error>
                <span class="input-group-btn col-1">
                    <button type="button" class="btn btn-success" @click="searchAccountModal('detail')"><i class="fas fa-search fa-fw"></i></button>
                </span>
              </div>
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text inputGroup-sizing-default">Account</span>
                </div>
                
                <input v-model="form_entry.account_name" type="text" name="account_name"
                  
                  class="form-control" :class="{ 'is-invalid': form_entry.errors.has('account_name') }" readonly aria-describedby="inputGroup-sizing-default">
                <has-error :form="form_entry" field="account_name"></has-error>
              </div>

              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text inputGroup-sizing-default">Branch</span>
                </div>
                  <select v-model="selected_branch" class="form-control col-12" aria-describedby="inputGroup-sizing-default" @change="branchChange">
                      <option v-for="branch in branches.data" v-bind:value="{ id: branch.id, name: branch.name }">{{ branch.name }}</option>
                  </select>
                  <has-error :form="form_entry" field="branch_id"></has-error>
              </div>

              <div class="box-header">
                  <h3 class="box-title">Items</h3>
                  <div class="box-tools">
                    <button class="btn btn-success" @click="newItem">Add Items <i class="fas fa-plus-circle fa-fw"></i></button>
                  </div>
                </div>

                <!-- /.box-header -->
                <div id="item-list" class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tbody>
                      <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Sub-Total</th>
                        <th>Tax Excluded</th>
                        <th>Vat</th>
                      </tr>
                      <tr>
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
                      </tr>
                      <tr>
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

              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text inputGroup-sizing-default">Amount</span>
                </div>

                  <input v-model="form_entry.amount" name="amount" id="amount"
                  readonly
                  class="form-control" :class="{ 'is-invalid': form_entry.errors.has('amount') }" aria-describedby="inputGroup-sizing-default">
                  <has-error :form="form_entry" field="amount"></has-error>
              </div>

              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text inputGroup-sizing-default">Tax Excluded</span>
                </div>

              
                  <input v-model="form_entry.amount_ex_tax" name="amount_ex_tax" id="amount_ex_tax"
                  
                  class="form-control" :class="{ 'is-invalid': form_entry.errors.has('amount_ex_tax') }" readonly aria-describedby="inputGroup-sizing-default">
                  <has-error :form="form_entry" field="amount_ex_tax"></has-error>
              </div>


              <div class="input-group mb-2">
                
                <div class="input-group-prepend">
                  <span class="input-group-text inputGroup-sizing-default">Tax</span>
                </div>
              
                  <input v-model="form_entry.vat" name="vat" id="vat"
                  
                  class="form-control" :class="{ 'is-invalid': form_entry.errors.has('vat') }" readonly aria-describedby="inputGroup-sizing-default">
                  <has-error :form="form_entry" field="vat"></has-error>
              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-success"  data-dismiss="modal" @click="saveDebitEntry">Save</button>
            </div>

            <!-- /form -->
          </div>
        </div>
      </div>
      <!-- Entry Modal -->


      <!-- Item Modal
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      -->


      <div class="modal fade"  v-show="cd_created"  id="entry-items" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add Item</h5>
              <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Entry</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <!-- form onsubmit="return false;" -->
            <div class="modal-body">
              
              <!--div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text nputGroup-sizing-default">Code</span>
                </div>
                <input v-model="form_entry.account_code" type="text" name="account_code" 
                  
                  class="form-control col-4" :class="{ 'is-invalid': form_entry.errors.has('account_code') }" aria-describedby="inputGroup-sizing-default">
                <has-error :form="form_entry" field="account_code"></has-error>
                <span class="input-group-btn col-1">
                    <button type="button" class="btn btn-success" @click="searchAccountModal('detail')"><i class="fas fa-search fa-fw"></i></button>
                </span>
              </div>
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text inputGroup-sizing-default">Account</span>
                </div>
                
                <input v-model="form_entry.account_name" type="text" name="account_name"
                  
                  class="form-control" :class="{ 'is-invalid': form_entry.errors.has('account_name') }" readonly aria-describedby="inputGroup-sizing-default">
                <has-error :form="form_entry" field="account_name"></has-error>
              </div -->
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text inputGroup-sizing-default">Name</span>
                </div>
                
                
                <input v-model="form_entry.entry_name" type="text" name="entry_name"
                  
                  class="form-control" :class="{ 'is-invalid': form_entry.errors.has('entry_name') }" aria-describedby="inputGroup-sizing-default">
                <has-error :form="form_entry" field="entry_name"></has-error>
              </div>
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text inputGroup-sizing-default">Description</span>
                </div>

                <input v-model="form_entry.entry_description" type="text" name="entry_description"
                  
                  class="form-control" :class="{ 'is-invalid': form_entry.errors.has('entry_description') }" aria-describedby="inputGroup-sizing-default">
                <has-error :form="form_entry" field="entry_description"></has-error>
              </div>

              <!--div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text inputGroup-sizing-default">Branch</span>
                </div>

              
                  <select v-model="form_entry.branch_name" class="form-control col-12" aria-describedby="inputGroup-sizing-default">
                      <option v-for="branch in branches.data" v-bind:value="branch.id">{{ branch.name }}</option>
                  </select>
                  
                  <has-error :form="form_entry" field="branch_name"></has-error>
              </div -->




              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text inputGroup-sizing-default">Price</span>
                </div>

              
                  <input v-model="form_entry.amount" name="amount" id="amount"
                  
                  @change="computeTaxChange"
                  class="form-control" :class="{ 'is-invalid': form_entry.errors.has('amount') }" aria-describedby="inputGroup-sizing-default">
                  <has-error :form="form_entry" field="amount"></has-error>
              </div>

              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text inputGroup-sizing-default">Quantity</span>
                </div>

                <input v-model="form_entry.entry_description" type="text" name="entry_description"
                  
                  class="form-control" :class="{ 'is-invalid': form_entry.errors.has('entry_description') }" aria-describedby="inputGroup-sizing-default">
                <has-error :form="form_entry" field="entry_description"></has-error>
              </div>

              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text inputGroup-sizing-default">Tax Type</span>
                </div>

              

                <select v-model="form_entry.tax_type" @change="onTaxChange" class="form-control col-12" aria-describedby="inputGroup-sizing-default">
                  <option value="TAX TYPE"></option>
                  <option value="VAT">VAT</option>
                  <option value="NON VAT">NON VAT</option>
                  <option value="VAT EXEMPT">VAT EXEMPT</option>
                  <option value="ZERO RATED">ZERO RATED</option>
                </select>
              </div>

              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text inputGroup-sizing-default">Tax Excluded</span>
                </div>

              
                  <input v-model="form_entry.amount_ex_tax" name="amount_ex_tax" id="amount_ex_tax"
                  
                  class="form-control" :class="{ 'is-invalid': form_entry.errors.has('amount_ex_tax') }" readonly aria-describedby="inputGroup-sizing-default">
                  <has-error :form="form_entry" field="amount_ex_tax"></has-error>
              </div>


              <div class="input-group mb-2">
                
                <div class="input-group-prepend">
                  <span class="input-group-text inputGroup-sizing-default">Tax</span>
                </div>
              
                  <input v-model="form_entry.vat" name="vat" id="vat"
                  
                  class="form-control" :class="{ 'is-invalid': form_entry.errors.has('vat') }" readonly aria-describedby="inputGroup-sizing-default">
                  <has-error :form="form_entry" field="vat"></has-error>
              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-success"  data-dismiss="modal">Save</button>
            </div>

            <!-- /form -->
          </div>
        </div>
      </div>
      <!-- Item Modal -->


      <!-- Search Account Modal 
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      -->

      <div class="modal fade" id="select-account" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <!--h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New</h5>
              <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Entry</h5 -->
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form onsubmit="return false;">
            <div class="modal-body">
              
              <div class="form-group">
                <label>Search</label>
                <input type="text" name="search" v-model="searchText" @change="SearchIt" class="float-right col-6">
              </div>
              
              <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody><tr>
                  <th>Code</th>
                  <th>Name</th>
                  <th>Option</th>
                </tr>
                <tr v-for="chart_of_account in chart_of_accounts.data" :key="chart_of_account.id">
                  <td>{{ chart_of_account.account_code }}</td>
                  <td>{{ chart_of_account.account_name }}</td>
                  <td>
                    <a href="#" @click="selectAccount(chart_of_account.account_code,chart_of_account.account_name)">Select
                      <i class="fa fa-edit"></i>
                    </a>
                  </td>
                </tr>
              </tbody></table>
            </div>
            <!-- /.box-body -->


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
      <!-- Search Account Modal -->

    
      <!-- Search Payee Modal 
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      *
      -->

      <div class="modal fade" id="select-payee" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <!--h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New</h5>
              <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Entry</h5 -->
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form onsubmit="return false;">
            <div class="modal-body">
              
              <div class="form-group">
                <label>Search</label>
                <input type="text" name="search" v-model="searchPayee" @change="SearchPayee" class="float-right col-6">
              </div>
              
              <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody><tr>
                  <th>Code</th>
                  <th>Name</th>
                  <th>Option</th>
                </tr>
                <tr v-for="payee in payees.data" :key="payee.id">
                  <td>{{ payee.id }}</td>
                  <td>{{ payee.name }}</td> 
                  <td>
                    <a href="#" @click="selectPayee(payee.id,payee.name,payee.address,payee.tin)">Select
                      <i class="fa fa-edit"></i>
                    </a>
                  </td>
                </tr>
              </tbody></table>
            </div>
            <!-- /.box-body -->


            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

            </form>
          </div>
        </div>
      </div>
      <!-- Search Payee Modal -->      

      <!--search-account
      v-show="isModalVisible"
      @close="closeSearchAccount"
      / -->

      

    </div>
</template>
<script>
    //import { ModelSelect } from 'vue-search-select'
    //import { DynamicSelect } from 'vue-dynamic-select'
    //import { BasicSelect } from 'vue-search-select'
    
    export default {
        data() {
          return {
              user_id: '',
              //isModalVisible: false,
              editmode: false,
              cd_created: false,
              no_payee: false,
              no_reference_no: false,
              no_account_code: false,
              searchText: '',
              searchPayee: '',
              headerOrDetail: 'header',
              current_payee_id: '',
              current_payee_name: '',
              current_payee_address: '',
              current_payee_tin: '',
              active_debit_row: 0,
              selected_branch: {},
              cd : {},
              form: new Form({

                  id:'',
                  payee_id: '',
                  reference_no: '',
                  transaction_no: '',
                  transaction_type: 'CD', // default for Cash Disbursement
                  transaction_date: this.getDate(),
                  account_code: '',
                  account_name:'',
                  amount: 0,
                  credit_amount: 0,
                  debit_amount: 0,
                  amount_ex_tax: 0,
                  vat: 0,
                  canceled: 0,
                  user_id: document.querySelector('meta[name="user-id"]').getAttribute('content')
                  
                  
              }),
              form_entry: new Form({

                  id:'',
                  transaction_id:'',
                  transaction_no:'',
                  transaction_type:'',
                  account_code : '',
                  account_name: '',
                  entry_name: '',
                  entry_description: '',
                  branch_id: '',
                  branch_name: '',
                  amount: '',
                  amount_ex_tax: '',
                  
                  vat: '',
                  credit_amount: '',
                  debit_amount: '',
                  transaction_date: this.getDate(),
                  
                  
              }),
              payees: {},
              branches: {},
              chart_of_accounts: {}

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
          loadBranches(){

            if(this.$gate.isAdminOrAuthor()){
                axios.get("api/branch").then(({data}) => (this.branches = data ));
                //axios.get("api/user").then(({ data }) => (this.users = data.data));
            } 
             
          },
          loadPayees(){
            if(this.$gate.isAdminOrAuthor()){
                axios.get("api/payee").then(({data}) => (this.payees = data ));
                //axios.get("api/user").then(({ data }) => (this.users = data.data));
            } 
          },
          loadChartAccounts(){
                axios.get("api/chartaccount").then(({ data }) => (this.chart_of_accounts = data));
          },
          eventChild(Obj){
            //console.log(Obj.id);
            this.form.payee_id = Obj.id;
          },
          getDate() {
            const toTwoDigits = num => num < 10 ? '0' + num : num;
            let today = new Date();
            let year = today.getFullYear();
            let month = toTwoDigits(today.getMonth() + 1);
            let day = toTwoDigits(today.getDate());
            return `${year}-${month}-${day}`;
          },
          createCD(){
            if(this.form.payee_id.length == 0) {
              this.no_payee = true;
            } else {
              this.no_payee = false;
            }

            if(this.form.account_code.length == 0) {
              this.no_account_code = true;
            } else {
              this.no_account_code = false;
            }
            
            if(this.form.reference_no.length == 0) {
              this.no_reference_no = true;
            } else {
              this.no_reference_no = false;
            }

            if (this.no_account_code || this.no_reference_no || this.no_payee){
              this.cd_created = false;
            } else {
              this.cd_created = true;
            }


            this.form.transaction_no = this.createSerialNumber();
            this.form.transaction_type = 'CD';
            this.form.post('api/cd')
                .then((data)=>{
                  //console.log(data.data.id);
                  this.form.id = data.data.id;
                })
                .catch(()=>{
                  //
                });
            
          },
          saveCD(){
            this.cd_created = false;
          },
          cancelCD(){
            this.cd_created = false;
            this.form.delete('api/cd/cancel/'+this.form.transaction_no);
          },
          //searchAccount() {
          //  this.isModalVisible = true;
          //},
          //closeSearchAccount(code = null,name = null) {
          //  this.isModalVisible = false;
          //  this.form.account_name = name;
          //  this.form.account_code = code;
            //console.log(code+' '+name);

          //},
          searchAccountModal(headerOrDetail = 'header'){
              this.headerOrDetail = headerOrDetail;
              this.searchText = this.form.account_code;
              //this.searchText = '';
              this.loadChartAccounts();
              $('#select-account').modal('show');
          },
          searchPayeeModal(){
              this.searchPayee = this.form.payee_id;
              //this.searchPayee = '';
              this.loadPayees();
              $('#select-payee').modal('show');
          },

          selectAccount(account_code  = null,account_name = null){
              if (account_code != null && account_name != null){
                  if(this.headerOrDetail == 'header'){
                      this.form.account_name = account_name;
                      this.form.account_code = account_code;
                  } else {
                      this.form_entry.account_name = account_name;
                      this.form_entry.account_code = account_code;
                  }
              }

              $('#select-account').modal('hide');  

          },

          selectPayee(id = null,name = null,address = null,tin = null){
              if (id){
                    console.log(name);
                      this.current_payee_id = id;
                      this.form.payee_id = id;
                      this.current_payee_name = name;
                      this.current_payee_address = address;
                      this.current_payee_tin = tin;
              }
              $('#select-payee').modal('hide');  

          },
          SearchIt() {
              let query = this.searchText;
              axios.get('api/searchAccount?q='+query)
                .then((data)=>{
                  this.chart_of_accounts = data.data;
                })
                .catch(()=>{
                  //
                });
          },
          SearchPayee() {
              let query = this.searchPayee;
              axios.get('api/searchPayee?q='+query)
                .then((data)=>{
                  this.payees = data.data;
                })
                .catch(()=>{
                  //
                });
          },
          createSerialNumber(){
              // Get current date
              var d = new Date();
              // Get pirmative value of date
              var n = d.valueOf();
              // Return concatenated primative value with the user id. 
              
              return ""+n+this.user_id;
          },
          onTaxChange(){
            if(this.form_entry.amount){
                if(this.form_entry.tax_type == 'VAT'){
              
                this.form_entry.vat = this.form_entry.amount * 0.12;
                this.form_entry.amount_ex_tax = this.form_entry.amount - this.form_entry.vat;
              } else {
                  this.form_entry.vat = 0;
                  this.form_entry.amount_ex_tax = this.form_entry.amount;
                }
            }
          },
          computeTaxChange(event){
              if(this.form_entry.amount){
                if(this.form_entry.tax_type == 'VAT'){
                  this.form_entry.amount = event.target.value;
                  this.form_entry.vat = this.form_entry.amount * 0.12;
                  this.form_entry.amount_ex_tax = this.form_entry.amount - this.form_entry.vat;
                } else {
                  this.form_entry.amount = event.target.value;
                  this.form_entry.vat = 0;
                  this.form_entry.amount_ex_tax = this.form_entry.amount;
                }
              }
          },
          selectDebitRow(active_debit_row){
              this.active_debit_row = active_debit_row;
              console.log(active_debit_row);
          },
          newEntry(){
              this.editmode = false;
              this.form_entry.reset();
              this.form_entry.transaction_id = this.form.id;
              this.form_entry.transaction_no = this.form.transaction_no;
              this.form_entry.transaction_type = 'CD';
              this.form_entry.post('api/cd/entry')
                .then((data)=>{
                  this.form_entry.id = data.data.id;
                  //console.log(data.data.id);

                })
                .catch(()=>{
                  //
                });

              $('#entry-details').modal('show');


          },
          newItem(){
              this.editmode = false;
              //this.form_entry.reset();
              $('#entry-items').modal('show');
          },
          saveDebitEntry(){
            //console.log('Edit Payee');
            
            this.$Progress.start();
            this.form_entry.put('api/entry/'+this.form_entry.id)
            .then(() => {
                $('#entry-details').modal('hide');
                /*
                swal.fire(
                    'Updated!',
                    'Payee information has been updated.',
                    'success'
                  );
                */
                  this.$Progress.finish();
                  VueListen.$emit('RefreshEntryTable');
            })
            .catch(() => {
                this.$Progress.fail();
            });
          },
          branchChange(){
            this.form_entry.branch_id = this.selected_branch.id ;
            this.form_entry.branch_name = this.selected_branch.name;
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
            this.loadBranches();
            this.loadChartAccounts();
            //this.SearchIt = _.debounce(this.SearchIt, 1000);
            
            /*
            VueListen.$on('RefreshUsersTable',() => {
               this.loadPayees();
               //this.loadUsers();
            });
            */
            
            this.user_id = document.querySelector('meta[name="user-id"]').getAttribute('content');
            //console.log(document.querySelector('meta[name="user-id"]').getAttribute('content'));
            //console.log(this.payees);

            //setInterval(() => this.loadUsers(),3000);


            /* Scrollbar fix
               If you have a modal on your page that exceeds the browser height, then you can't scroll in it when closing an second modal. To fix this add: */
            $(document).on('hidden.bs.modal', '.modal', function () {
                $('.modal:visible').length && $(document.body).addClass('modal-open');
            });

            /*Backdrop z-index fix
              This solution uses a setTimeout because the .modal-backdrop isn't created when the event show.bs.modal is triggered.

            $(document).on('show.bs.modal', '.modal', function () {
                var zIndex = 1040 + (10 * $('.modal:visible').length);
                $(this).css('z-index', zIndex);
                setTimeout(function() {
                    $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
                }, 0);
            });
            */
        },
        computed: {
            
          

        },
        components: {
          //DynamicSelect
          
        }
    }
</script>
<style type="text/css">

</style>