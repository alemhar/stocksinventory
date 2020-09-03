<template>
    <div class="container">
        <div class="row mt-1" v-if="$gate.isAdminOrUser()">
        <div class="col-md-12">
          <div class="box mt-4">
            <!-- general form elements -->
          <div class="box box-warning">


            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" @submit.prevent="createTransaction()">
              <div class="box-header with-border">
                <h3 class="box-title box-title-transaction">Cash Disbursement</h3>
                <div class="box-tools">
                  <button type="submit" v-show="!transaction_created" class="btn btn-success">Create <i class="fas fa-plus-circle fa-fw"></i></button>
                  <!-- @click="createCD()"  -->
                  <button  type="button" class="btn btn-danger"  v-show="transaction_created" @click="cancelTransaction">Cancel <i class="fas fa-window-close fa-fw"></i></button>
                  <button type="button"  class="btn btn-success"  v-show="transaction_created" @click="saveTransaction">Save <i class="fas fa-save fa-fw"></i></button>

                </div>
              </div>
              <div class="box-body row">
                <!--Left Col-->
                <div class="col-8">


                  <div class="input-group mb-2">

                    <div class="input-group-prepend">
                      <span class="input-group-text inputGroup-sizing-default"  v-bind:style="[readabilityObject]">Payee</span>
                    </div>  
                    <input v-model="current_payee_name" v-bind:readonly="transaction_created" type="text" class="form-control col-12" id="inputPayeeName" placeholder="Payees Name" v-bind:style="[readabilityObject]">
                      
                    <span class="input-group-btn col-1">
                        <button type="button" v-show="!transaction_created" class="btn btn-success" @click="searchPayeeModal"><i class="fas fa-search fa-fw"></i></button>
                    </span>  

                    <p v-show="no_payee" class="empty-field-message">** Please select payee!</p>  
                  

                  </div>
                  

                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="inputGroup-sizing-default">Address</span>
                    </div>
                    <input v-bind:readonly="transaction_created" type="text" class="form-control col-12" id="inputPayeesAddress" placeholder="Address" v-model="current_payee_address">
                  </div>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="inputGroup-sizing-default">TIN</span>
                    </div>
                      <input v-bind:readonly="transaction_created" type="text" class="form-control" id="inputPayeesTIN" placeholder="TIN"  v-model="current_payee_tin">
                    

                  </div>

                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text inputGroup-sizing-default">Account</span>
                    </div>
                    

                    <input v-bind:readonly="transaction_created" type="text" class="form-control col-2" id="inputAccountCode" placeholder="Code"  v-model="form.account_code">

                    <input readonly="true" type="text" class="form-control col-9" id="inputAccountName" placeholder="Account Name" v-model="form.account_name">

                    <span class="input-group-btn col-1">
                        <button type="button" v-show="!transaction_created" class="btn btn-success" @click="searchAccountModal('header')"><i class="fas fa-search fa-fw"></i></button>

                    </span>
                  </div>

                  <div class="input-group mb-2">
                    <p v-show="no_account_code" class="empty-field-message">** Please select account!</p>
                  </div>  


                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text inputGroup-sizing-default">Branch</span>
                    </div>
                    <input v-bind:readonly="transaction_created" type="text" class="form-control col-2" id="inputBranchId" placeholder="Code"  v-model="form.branch_id">
                    <input readonly="true" type="text" class="form-control col-9" id="inputBranchName" placeholder="Branch Name" v-model="current_branch_name">
                    <span class="input-group-btn col-1">
                        <button type="button" v-show="!transaction_created" class="btn btn-success" @click="searchBranchModal()"><i class="fas fa-search fa-fw"></i></button>
                    </span>
                  </div>
                  <div class="input-group mb-2">
                    <p v-show="no_branch_id" class="empty-field-message">** Please select branch!</p>
                  </div>

                </div>
                <!--Right Col-->
                <div class="col-4">
                  <div class="input-group mb-2">
                    <!--label for="inputReferenceNo">Reference No.</label -->
                    <div class="input-group-prepend">
                      <span class="input-group-text inputGroup-sizing-default">Ref. #</span>
                    </div>

                    <input v-bind:readonly="transaction_created" type="text"  v-model="form.reference_no" class="form-control col-12" id="inputReferenceNo" placeholder="Reference No">
                    <p v-show="no_reference_no" class="empty-field-message">** Please enter reference number!</p>
                  </div>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text inputGroup-sizing-default">Date</span>
                    </div>
                    <input v-bind:readonly="transaction_created" type="date" v-model="form.transaction_date" class="form-control col-12" id="inputDate" placeholder="Date">
                  </div>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text inputGroup-sizing-default">Transaction #</span>
                    </div>
                    <input type="text" v-model="form.transaction_no" readonly class="form-control col-12" id="inputDCNo" placeholder="Transaction Number">
                  </div>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text inputGroup-sizing-default">Amount</span>
                    </div>
                    
                    <!--input type="text"  v-model="Number(form.amount).toLocaleString()" readonly class="form-control col-12" id="inputAmount" placeholder="Amount" -->
                    <currency-input v-model="form.amount" v-bind:isReadonly="true" v-bind:fc="true" v-bind:col="12" id="inputAmount" placeholder="Amount"></currency-input>
                  </div>

                </div>
              </div>
              <!-- /.box-body -->
            </form>
          </div>




      <!-- Entry List     
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


          <div  v-show="transaction_created" class="box box-warning mt-2">
            <div class="col-md-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title box-title-transaction">Entries</h3>
                  <div class="box-tools">
                    <button class="btn btn-success" @click="newEntry">Add Entry <i class="fas fa-plus-circle fa-fw"></i></button>
                  </div>
                </div>


                <!-- /.box-header -->
                <div id="debits-list" class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tbody>
                      <tr>
                        <th>Account No.</th>
                        <th>Name</th>
                        <th>Amount</th>
                        <!-- th>Tax Excluded</th -->
                        <th>Tax</th>
                        <th>Option</th>
                        
                      </tr>
                      <tr v-for="entry in transactions" :key="entry.transaction_entry_id" @click="selectEntryRow(entry.transaction_entry_id)" :class="{ 'table-warning' : active_debit_row == entry.transaction_entry_id }" >
                        <td>{{ entry.account_code }}</td>
                        <td>{{ entry.account_name }}</td>
                        <td>{{ entry.amount }}</td>
                        <!-- td>{{ entry.amount_ex_tax }}</td -->
                        <td>{{ entry.vat }}</td>
                        <td>
                          <a href="#" @click="deleteEntry(entry.transaction_entry_id,entry.amount,entry.vat)">
                            <i class="fa fa-trash"></i>
                          </a>
                        </td>
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

          <div v-show="transaction_created" class="box box-warning mt-2">
            <div class="col-md-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title box-title-transaction">Items</h3>
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
                      <tr v-for="item in currentItems" :key="item.item_no">
                        <td>{{ item.account_code }}</td>
                        <td>{{ item.item }}</td>
                        <td>{{ item.quantity }}</td>
                        <td>{{ item.price }}</td>
                        <td>{{ item.tax_type }}</td>
                        <td>{{ item.sub_total }}</td>
                        <td>{{ item.tax_excluded }}</td>
                        <td>{{ item.vat }}</td>
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

            <div v-show="transaction_created" class="box box-warning mt-2">
              
              <div class="form-group col-12 float-right">
                <div class="row mt-2">
                <label for="inputAmountExclusiveTax" class="col-sm-9 col-form-label" style="text-align: right;">Amount Exclusive of Tax</label>
                <div class="col-sm-3">
                  <input readonly v-model="Number(form.amount_ex_tax).toLocaleString()" type="text" class="form-control" id="inputAmountExclusiveTax" placeholder="Amount Exclusive of Tax">
                </div>
                </div>
              </div>
              <div class="form-group col-12 float-right">
                <div class="row">
                
                <label for="inputVAT" class="col-sm-9 col-form-label" style="text-align: right;">VAT</label>
                <div class="col-sm-3">
                  <input readonly v-model="Number(form.vat).toLocaleString()" type="text" class="form-control" id="inputVAT" placeholder="VAT">
                </div>
              </div>
              </div>
              <div class="form-group col-12 float-right">
                <div class="row">
                
                <label for="inputTotalAmount" class="col-sm-9 col-form-label" style="text-align: right;">Total Amount</label>
                <div class="col-sm-3">
                  <input readonly v-model="Number(form.amount).toLocaleString()" type="text" class="form-control" id="inputTotalAmount" placeholder="Total Amount">
                </div>
              </div>
              </div>
            
            </div>
          
          </div>
          <!-- /.box -->
        </div>
        </div>

        <div class="row mt-1" v-if="!$gate.isAdminOrUser()">
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


      <div class="modal fade" v-show="transaction_created" id="entry-details" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content" style="width: 800px;">
            <div class="modal-header">
              <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add Entry</h5>
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
                  readonly
                  class="form-control col-4" :class="{ 'is-invalid': form_entry.errors.has('account_code') }" aria-describedby="inputGroup-sizing-default">
                <has-error :form="form_entry" field="account_code"></has-error>
                <span class="input-group-btn col-1">
                    <button type="button" class="btn btn-success" @click="searchAccountModal('detail')"><i class="fas fa-search fa-fw"></i></button>
                </span>
              </div>
              <div class="input-group mb-2">
                <p v-show="no_entry_account_code" class="empty-field-message">** Please select account!</p>
              </div>
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text inputGroup-sizing-default">Account</span>
                </div>
                
                <input v-model="form_entry.account_name" type="text" name="account_name"
                  
                  class="form-control" :class="{ 'is-invalid': form_entry.errors.has('account_name') }" readonly aria-describedby="inputGroup-sizing-default">
                <has-error :form="form_entry" field="account_name"></has-error>
              </div>

              
              <div class="box-header">
                  <h3 class="box-title box-title-transaction">Items</h3>
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
                        <th>Option</th>
                      </tr>
                      <tr v-for="item in currentItems" :key="item.item_no">
                        <td>{{ item.item }}</td>
                        <td>{{ item.quantity }}</td> 
                        <td>{{ item.price }}</td> 
                        <td>{{ item.sub_total }}</td> 
                        <td>{{ item.tax_excluded }}</td> 
                        <td>{{ item.vat }}</td> 
                        <td>
                          <a href="#" @click="deleteItem(item.item_no,item.sub_total,item.tax_excluded,item.vat)">
                            <i class="fa fa-trash"></i>
                          </a>
                        </td>
                      </tr>  


                  </tbody>
                </table>
                </div>
                <!-- /.box-body -->

              <div v-if="depreciates">
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <span class="input-group-text inputGroup-sizing-default">Useful Life</span>
                  </div>

                    <input v-model="form_entry.useful_life" name="useful_life" id="useful_life"
                    class="form-control" aria-describedby="inputGroup-sizing-default">
                    
                </div>

                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <span class="input-group-text inputGroup-sizing-default">Salvage Value</span>
                  </div>

                    <input v-model="form_entry.salvage_value" name="salvage_value" id="salvage_value"
                    class="form-control" aria-describedby="inputGroup-sizing-default">
                    
                </div>
              </div>


              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text inputGroup-sizing-default">Amount</span>
                </div>

                  <input v-model="Number(form_entry.amount).toLocaleString()" name="amount" id="amount"
                  readonly
                  class="form-control" :class="{ 'is-invalid': form_entry.errors.has('amount') }" aria-describedby="inputGroup-sizing-default">
                  <has-error :form="form_entry" field="amount"></has-error>
              </div>

              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text inputGroup-sizing-default">Tax Excluded</span>
                </div>

              
                  <input v-model="Number(form_entry.amount_ex_tax).toLocaleString()" name="amount_ex_tax" id="amount_ex_tax2"
                  
                  class="form-control" :class="{ 'is-invalid': form_entry.errors.has('amount_ex_tax') }" readonly aria-describedby="inputGroup-sizing-default">
                  <has-error :form="form_entry" field="amount_ex_tax"></has-error>
              </div>


              <div class="input-group mb-2">
                
                <div class="input-group-prepend">
                  <span class="input-group-text inputGroup-sizing-default">Tax</span>
                </div>
              
                  <input v-model="Number(form_entry.vat).toLocaleString()" name="vat" id="vat2"
                  
                  class="form-control" :class="{ 'is-invalid': form_entry.errors.has('vat') }" readonly aria-describedby="inputGroup-sizing-default">
                  <has-error :form="form_entry" field="vat"></has-error>
              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" @click="cancelEntry">Cancel</button>
              <button type="button" :disabled="!save_button_entry_enabled" class="btn btn-success" @click="saveEntry">Save</button>
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


      <div class="modal fade"  v-show="transaction_created"  id="entry-items" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
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
              
              
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text inputGroup-sizing-default">Name</span>
                </div>
                
                
                <input v-model="form_item.item" type="text" name="item_name"
                  
                  class="form-control" :class="{ 'is-invalid': form_item.errors.has('item') }" aria-describedby="inputGroup-sizing-default" onfocus="this.select()">
                   
                <has-error :form="form_item" field="item_name"></has-error>
              </div>
              <div class="input-group mb-2">
                <p v-show="no_item" class="empty-field-message">** Please enter item.</p>
              </div>  




              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text inputGroup-sizing-default">Price</span>
                </div>

              
                  <input v-model="form_item.price" name="price" id="price"
                  @change="computeTaxChange"
                  class="form-control" :class="{ 'is-invalid': form_entry.errors.has('price') }" aria-describedby="inputGroup-sizing-default" onfocus="this.select()">
                  <has-error :form="form_item" field="price"></has-error>
              </div>
              <div class="input-group mb-2">
                <p v-show="no_price" class="empty-field-message">** Please enter price.</p> 
              </div>  
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text inputGroup-sizing-default">Quantity</span>
                </div>

                <input v-model="form_item.quantity" type="text" name="entry_description"
                  @change="computeTaxChange"
                  class="form-control" :class="{ 'is-invalid': form_item.errors.has('quantity') }" aria-describedby="inputGroup-sizing-default" onfocus="this.select()">
                  
                <has-error :form="form_item" field="entry_description"></has-error>
              </div>
              <div class="input-group mb-2">
                <p v-show="no_quantity" class="empty-field-message">** Please enter quantity.</p> 
              </div>  
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text inputGroup-sizing-default">Total</span>
                </div>

                <input v-model="form_item.sub_total" type="text" name="entry_description"
                  @change="computeTaxChange"
                  class="form-control" :class="{ 'is-invalid': form_item.errors.has('sub_total') }" aria-describedby="inputGroup-sizing-default" readonly>
                <has-error :form="form_item" field="entry_description"></has-error>
              </div>

              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text inputGroup-sizing-default">Tax Type</span>
                </div>

              

                <select v-model="form_item.tax_type" @change="computeTaxChange" class="form-control col-12" aria-describedby="inputGroup-sizing-default">
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

              
                  <input v-model="form_item.tax_excluded" name="amount_ex_tax" id="amount_ex_tax"
                  
                  class="form-control" :class="{ 'is-invalid': form_item.errors.has('tax_excluded') }" readonly aria-describedby="inputGroup-sizing-default">
                  <has-error :form="form_item" field="amount_ex_tax"></has-error>
              </div>


              <div class="input-group mb-2">
                
                <div class="input-group-prepend">
                  <span class="input-group-text inputGroup-sizing-default">Tax</span>
                </div>
              
                  <input v-model="form_item.vat" name="vat" id="vat"
                  
                  class="form-control" :class="{ 'is-invalid': form_item.errors.has('vat') }" readonly aria-describedby="inputGroup-sizing-default">
                  <has-error :form="form_item" field="vat"></has-error>
              </div>

            </div>
            <div class="modal-footer">
              <button type="button" @click="cancelItem" class="btn btn-danger">Cancel</button>
              <button type="button" :disabled="!save_button_item_enabled" @click="saveItem" class="btn btn-success">Save</button>
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

      <div class="modal fade" id="select-account" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
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
                    <a href="#" @click="selectAccount(
                    chart_of_account.account_code,
                    chart_of_account.account_name,
                    chart_of_account.account_type,
                    chart_of_account.sub_account_type,
                    chart_of_account.main_code,
                    chart_of_account.main_account,
                    chart_of_account.type,
                    chart_of_account.counterpart_code,
                    chart_of_account.counterpart_name
                    )">Select
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

      <div class="modal fade" id="select-payee" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              
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


      <!-- Search Branch Modal 
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

      <div class="modal fade" id="select-branch" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form onsubmit="return false;">
            <div class="modal-body">
              
              <div class="form-group">
                <label>Search</label>
                <input type="text" name="search" v-model="searchBranch" @change="SearchBranch" class="float-right col-6">
              </div>
              
              <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody><tr>
                  <th>Code</th>
                  <th>Branch</th>
                  <th>Option</th>
                </tr>
                <tr v-for="branch in branches.data" :key="branch.id">
                  <td>{{ branch.id }}</td>
                  <td>{{ branch.name }}</td> 
                  <td>
                    <a href="#" @click="selectBranch(branch.id,branch.name)">Select
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
      <!-- Search Branch Modal -->   
      

    </div>
</template>
<script>

    export default {
        data() {
          return {
              transaction_type: 'CD',
              //ledgers: [],
              transactions: [],
              items: [],
              item_no: 0,
              transaction_entry_id: 0,
              current_transaction_entry_id: 0,
              user_id: '',
              editmode: false,
              transaction_created: false,
              no_payee: false,
              no_reference_no: false,
              no_account_code: false,
              no_branch_id: false,
              no_item: false,
              no_price: false,
              no_quantity: false,
              no_entry_account_code: false,
              no_entry_branch_id: false,
              save_button_item_enabled: true,
              save_button_entry_enabled: true,
              searchText: '',
              searchPayee: '',
              searchBranch: '',
              headerOrDetail: 'header',
              current_branch_id: '',
              current_branch_name: '',
              current_payee_id: '',
              current_payee_name: '',
              current_payee_address: '',
              current_payee_tin: '',
              active_debit_row: 0,
              selected_branch: {},
              cd : {},
              form: new Form({
                  account_type:'',
                  sub_account_type:'',
                  main_code:0,
                  main_account:'',
                  type: '',
                  payee_id: '',
                  reference_no: '',
                  transaction_no: '',
                  transaction_type: this.transaction_type, // default for Cash Disbursement
                  transaction_date: this.getDate(),
                  account_code: '',
                  account_name:'',
                  amount: 0,
                  credit_amount: 0,
                  debit_amount: 0,
                  amount_ex_tax: 0,
                  vat: 0,
                  canceled: 0,
                  branch_id: '',
                  useful_life: 0,
                  salvage_value: 0,
                  counterpart_code: 0,
                  counterpart_name: 'NA',
                  user_id: document.querySelector('meta[name="user-id"]').getAttribute('content')
              }),
              form_entry: new Form({

                  account_type:'',
                  sub_account_type:'',
                  main_code:0,
                  main_account:'',
                  type: '',
                  transaction_no:'',
                  transaction_entry_id: 0,
                  transaction_type: this.transaction_type,
                  account_code : 0,
                  account_name: 'NA',
                  branch_id: '',
                  branch_name: '',
                  amount: 0,
                  amount_ex_tax: 0,
                  vat: 0,
                  credit_amount: 0,
                  debit_amount: 0,
                  useful_life: 0,
                  salvage_value: 0,
                  counterpart_code: 0,
                  counterpart_name: 'NA',
                  transaction_date: this.getDate(),
              }),
              form_item: new Form({

                  id:'',
                  transaction_entry_id:'',
                  transaction_no:'',
                  transaction_type: this.transaction_type,
                  account_code : '',
                  item: '',
                  quantity: 0,
                  price: 0,
                  sub_total: 0,
                  tax_type: 'VAT',
                  tax_excluded: 0,
                  vat: 0
                  
                  
              }),
              payees: {},
              branches: {},
              chart_of_accounts: {},
              chart_of_accounts_header: {},
              chart_of_accounts_detail: {},
              depreciates: false,
              readabilityObject: {
                fontSize: user.font_size
              }

          }
        },
        methods: {
          loadBranch(){
            if(this.$gate.isAdminOrUser()){
                axios.get("api/branch").then(({data}) => (this.branches = data ));
                
            } 
          },
          loadPayees(){
            if(this.$gate.isAdminOrUser()){
                axios.get("api/payee").then(({data}) => (this.payees = data ));
                //axios.get("api/user").then(({ data }) => (this.users = data.data));
            } 
          },
          loadChartAccounts(headerOrDetail = null){
              if(headerOrDetail == 'header'){
                  this.chart_of_accounts = this.chart_of_accounts_header; 
              } else {
                  this.chart_of_accounts = this.chart_of_accounts_detail;
              }
              
          },
          initChartAccounts(){

              axios.get('api/chartaccount?headerordetail=header&transaction_type='+this.transaction_type)
                .then((data)=>{
                  this.chart_of_accounts_header = data.data;
                })
                .catch(()=>{
                  //
                });
                
              axios.get('api/chartaccount?headerordetail=detail&transaction_type='+this.transaction_type)
                .then((data)=>{
                  this.chart_of_accounts_detail = data.data;
                })
                .catch(()=>{
                  //
                });  
          },
          
          getDate() {
            const toTwoDigits = num => num < 10 ? '0' + num : num;
            let today = new Date();
            let year = today.getFullYear();
            let month = toTwoDigits(today.getMonth() + 1);
            let day = toTwoDigits(today.getDate());
            return `${year}-${month}-${day}`;
          },
          createTransaction(){
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

            if(this.form.branch_id.length == 0) {
              this.no_branch_id = true;
            } else {
              this.no_branch_id = false;
            }
            
            if(this.form.reference_no.length == 0) {
              this.no_reference_no = true;
            } else {
              this.no_reference_no = false;
            }

            if (this.no_account_code || this.no_reference_no || this.no_payee || this.no_branch_id){
              this.transaction_created = false;
            } else {
              this.transaction_created = true;
            }


            this.form.transaction_no = this.createSerialNumber();
            this.form.transaction_type = this.transaction_type;

          },
          saveTransaction(){
            if(this.form.amount == 0) {
              return false;
            }
            
            this.transaction_created = false; 
            ++this.transaction_entry_id;
            this.transactions.push({ 

                // *************************
                account_type: this.form.account_type,
                sub_account_type: this.form.sub_account_type,
                main_code: this.form.main_code,
                main_account: this.form.main_account,
                type: this.form.type,
                counterpart_code: 0,
                counterpart_name: 'NA',
                
                // *************************

                transaction_entry_id: this.transaction_entry_id,
                payee_id: this.form.payee_id,
                branch_id: this.current_branch_id,
                account_code: this.form.account_code,
                account_name: this.form.account_name,
                reference_no: this.form.reference_no,
                transaction_no: this.form.transaction_no,
                transaction_type: this.form.transaction_type,
                transaction_date: this.form.transaction_date,
                amount: this.form.amount,
                credit_amount: this.form.amount,
                debit_amount: 0,
                total_payment: 0,
                amount_ex_tax: this.form.amount_ex_tax,
                vat: this.form.vat,
                wtax_code: 0,
                wtax: 0,
                user_id: this.form.user_id,
                status: 'CONFIRMED',
                depreciation_date: this.form.transaction_date,
                depreciated_id: 0,
                useful_life: 0,
                salvage_value: 0

            });
            ++this.transaction_entry_id;
            
            this.transactions.push({ 

                // *************************
                account_type: 'ASSETS',
                sub_account_type: 'CURRENT ASSETS',
                main_code: 0,
                main_account: 'NA',
                type: 'NA',
                counterpart_code: 0,
                counterpart_name: 'NA',
                
                // 

                transaction_entry_id: this.transaction_entry_id,
                payee_id: this.form.payee_id,
                branch_id: this.current_branch_id,
                account_code: '11051100',
                account_name: 'Input Tax',
                reference_no: this.form.reference_no,
                transaction_no: this.form.transaction_no,
                transaction_type: this.form.transaction_type,
                transaction_date: this.form.transaction_date,
                amount: this.form.vat,
                credit_amount: 0,
                debit_amount: this.form.vat,
                total_payment: 0,
                amount_ex_tax: 0,
                vat: 0,
                wtax_code: 0,
                wtax: 0,
                user_id: this.form.user_id,
                status: 'CONFIRMED',
                depreciation_date: this.form.transaction_date,
                depreciated_id: 0,
                useful_life: 0,
                salvage_value: 0
            });  


                // Save Transactions START
                let rawData = {
                    transactions: this.transactions
                }
                rawData = JSON.stringify(rawData);
                let formData = new FormData();
                    formData.append('transactions', rawData);
                axios.post('api/transactions', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then((response)=>{
                    
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });
                // Save Transactions END



                // Save Items START
                let rawItemData = {
                    items: this.items
                }
                rawItemData = JSON.stringify(rawItemData);
                let formItemData = new FormData();
                    formItemData.append('items', rawItemData);
                axios.post('api/items', formItemData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then((response)=>{
                    
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });
                // Save Items END


                swal.fire({
                    title: 'Saved!',
                    text: "Journal posted",
                    type: 'info',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ok'
                }).then((result) => {
                    if (result.value) {
                      //Reload Current Page
                      this.dataReset();
                    }
                });
          },
          dataReset(){
                  this.form.reset();
                  this.form_entry.reset();
                  this.form_item.reset();
                  this.transactions = [];
                  this.items = [];
                  this.transaction_entry_id = 0;
                  this.item_no = 0;
                  this.current_transaction_entry_id = 0;
                  this.searchText = '';
                  this.searchPayee = '';
                  this.searchBranch = '';
                  this.headerOrDetail = 'header';
                  this.current_branch_id = '';
                  this.current_branch_name = '';
                  this.current_payee_id = '';
                  this.current_payee_name = '';
                  this.current_payee_address = '';
                  this.current_payee_tin = '';
                  this.active_debit_row = 0;
          },
          cancelTransaction(){
            this.transaction_created = false;
            this.dataReset();
          },
          searchAccountModal(headerOrDetail = 'header'){
              this.headerOrDetail = headerOrDetail;
              //this.searchText = this.form.account_code;
              this.searchText = '';
              this.loadChartAccounts(headerOrDetail);
              $('#select-account').modal('show');
          },
          searchPayeeModal(){
              this.searchPayee = this.form.payee_id;
              this.loadPayees();
              $('#select-payee').modal('show');
          },
          searchBranchModal(){
              this.searchBranch = this.form.branch_id;
              this.loadBranch();
              $('#select-branch').modal('show');
          },
          // *************************************************
          selectAccount(
                        account_code  = null,
                        account_name = null, 
                        account_type,
                        sub_account_type,
                        main_code,
                        main_account,
                        type,
                        counterpart_code,
                        counterpart_name
                      ){
              if (account_code != null && account_name != null){
                  if(this.headerOrDetail == 'header'){
                      this.form.account_name = account_name;
                      this.form.account_code = account_code;
                      this.form.account_type = account_type;
                      this.form.sub_account_type = sub_account_type;
                      this.form.main_code = main_code;
                      this.form.main_account = main_account;
                      this.form.type = type;
                      this.form.counterpart_code = 0;
                      this.form.counterpart_name = 'NA';
                  } else {
                      if(account_code >= 15011200  && account_code < 15011550 ){
                        this.depreciates = true;
                      } else {
                        this.depreciates = false;
                      }
                      this.form_entry.account_name = account_name;
                      this.form_entry.account_code = account_code;
                      this.form_entry.account_type = account_type;
                      this.form_entry.sub_account_type = sub_account_type;
                      this.form_entry.main_code = main_code;
                      this.form_entry.main_account = main_account;
                      this.form_entry.type = type;
                      this.form_entry.counterpart_code = counterpart_code;
                      this.form_entry.counterpart_name = counterpart_name;
                  }
              }
              $('#select-account').modal('hide');  

          },
          // *************************************************
          
          selectPayee(id = null,name = null,address = null,tin = null){
              if (id){
                    
                      this.current_payee_id = id;
                      this.form.payee_id = id;
                      this.current_payee_name = name;
                      this.current_payee_address = address;
                      this.current_payee_tin = tin;
              }
              $('#select-payee').modal('hide');  

          },
          
          selectBranch(id = null,name = null){
              if (id){
                    
                      this.current_branch_id = id;
                      this.form.branch_id = id;

                      this.current_branch_name = name;
              }
              $('#select-branch').modal('hide');  

          },
          SearchIt() {
              let query = this.searchText;
              let headerOrDetail = this.headerOrDetail;
              axios.get('api/searchAccount?q='+query+'&transaction_type='+this.transaction_type+'&headerordetail='+headerOrDetail)
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
          SearchBranch() {
              let query = this.searchBranch;
              axios.get('api/searchBranch?q='+query)
                .then((data)=>{
                  this.branches = data.data;
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
          computeTaxChange(event){
              if(this.form_item.price && this.form_item.quantity){
                this.form_item.sub_total = this.form_item.price * this.form_item.quantity;
                if(this.form_item.tax_type == 'VAT'){
                  //this.form_item.amount = event.target.value;

                  

                  this.form_item.tax_excluded = (this.form_item.sub_total/1.12).toFixed(2) * 1;

                  this.form_item.vat = (this.form_item.tax_excluded * 0.12).toFixed(2)  * 1;

                } else {
                  //this.form_entry.amount = event.target.value;
                  this.form_item.vat = 0;
                  this.form_item.tax_excluded = this.form_item.sub_total  * 1;
                }
              }
          },
          newEntry(){
              this.editmode = false;
              this.form_entry.reset();
              ++this.transaction_entry_id;

              // To refresh ITEMS table
              this.current_transaction_entry_id = this.transaction_entry_id;

              this.form_entry.transaction_id = this.transaction_entry_id;
              this.form_entry.transaction_no = this.form.transaction_no;
              this.form_entry.transaction_type = this.transaction_type;
              this.save_button_entry_enabled = true;
              


              $('#entry-details').modal('show');


          },
          newItem(){

              if(this.depreciates && this.items.length > 0){
                swal.fire({
                    title: 'Warning!',
                    text: "Can only add one(1) item for "+ this.form_entry.account_name+ ". \nAdd another "+ this.form_entry.account_name+ " to enter another item.",
                    type: 'info',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ok'
                }).then((result) => {
                    
                });

                return false;
              }

              if(this.form_entry.account_code == 0) {
                this.no_entry_account_code = true;
              } else {
                this.no_entry_account_code = false;
              }
    
              if (this.no_entry_account_code){
                return false;
              }

              this.save_button_item_enabled = true;

              this.editmode = false;
              this.form_item.reset();

              this.no_item = false;
              this.no_price = false;
              this.no_quantity = false;
              this.form_item.transaction_entry_id = this.transaction_entry_id;
              this.form_item.transaction_no = this.form.transaction_no;
              this.form_item.transaction_type = this.transaction_type;
              this.form_item.account_code = this.form_entry.account_code;
              $('#entry-items').modal('show');
          },
          cancelEntry(){
            //this.$Progress.start();
            this.items = this.items.filter(( item ) => {
                return item.transaction_entry_id !== this.transaction_entry_id;
            });
            $('#entry-details').modal('hide');
 
          },
          saveEntry(){
            if(!this.form_entry.useful_life && this.depreciates){
                swal.fire({
                    title: 'Warning!',
                    text: "USEFUL LIFE input required for this entry.",
                    type: 'info',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ok'
                }).then((result) => {
                    
                });
                return false;
            }

            if(!this.form_entry.amount){
                swal.fire({
                    title: 'Warning!',
                    text: "Can not save ZERO amount entry.",
                    type: 'info',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ok'
                }).then((result) => {
                    
                });
                return false;
            }  

            this.form_entry.credit_amount = this.form_entry.amount

            // ** Temporary data to bypass Column cannot be null ERROR's
            this.save_button_entry_enabled = false;
            this.depreciates = false;
            this.form.amount += this.form_entry.amount;
            this.form.amount_ex_tax += this.form_entry.amount_ex_tax;
            this.form.vat += this.form_entry.vat;  

            // To refresh ITEMS table
            this.current_transaction_entry_id = this.transaction_entry_id;
            
            //this.$Progress.start();
            this.transactions.push({ 

                // *************************
                account_type: this.form_entry.account_type,
                sub_account_type: this.form_entry.sub_account_type,
                main_code: this.form_entry.main_code,
                main_account: this.form_entry.main_account,
                type: this.form_entry.type,
                counterpart_code: this.form_entry.counterpart_code,
                counterpart_name: this.form_entry.counterpart_name,
                // *************************

                transaction_entry_id: this.transaction_entry_id,
                payee_id: this.form.payee_id,
                branch_id: this.current_branch_id,
                account_code: this.form_entry.account_code,
                account_name: this.form_entry.account_name,
                reference_no: this.form.reference_no,
                transaction_no: this.form.transaction_no,
                transaction_type: this.form.transaction_type,
                transaction_date: this.form.transaction_date,
                amount: this.form_entry.amount_ex_tax,
                credit_amount: 0,
                debit_amount: this.form_entry.amount_ex_tax,
                total_payment: 0,
                amount_ex_tax: 0,
                vat: this.form_entry.vat,
                wtax_code: 0,
                wtax: 0,
                user_id: this.form.user_id,
                useful_life: this.form_entry.useful_life,
                salvage_value: this.form_entry.salvage_value,
                status: 'CONFIRMED',
                depreciation_date: this.form.transaction_date,
                depreciated_id: 0
            });
            $('#entry-details').modal('hide');
            //this.$Progress.finish();

          },
          deleteEntry(transaction_entry_id,entry_amount,entry_vat){

            this.transactions = this.transactions.filter(function( transaction ) {
                return transaction.transaction_entry_id !== transaction_entry_id;
            });

            this.items = this.items.filter(function( item ) {
                return item.transaction_entry_id !== transaction_entry_id;
            });

            this.form.amount = parseFloat(this.form.amount - entry_amount - entry_vat).toFixed(2) * 1;
            this.form.amount_ex_tax = (this.form.amount_ex_tax - entry_amount).toFixed(2) * 1;
            this.form.vat = (this.form.vat - entry_vat).toFixed(2) * 1;
            
           
          },
          cancelItem(){
            
            $('#entry-items').modal('hide');
            
          },
          saveItem(){
            
            if(this.form_item.item.length == 0) {
              this.no_item = true;
            } else {
              this.no_item = false;
            }

            if(this.form_item.price == 0) {
              this.no_price = true;
            } else {
              this.no_price = false;
            }
            
            if(this.form_item.quantity == 0) {
              this.no_quantity = true;
            } else {
              this.no_quantity = false;
            }
            
            if (this.no_item || this.no_price || this.no_quantity){
              return false;
            } 

            this.save_button_item_enabled = false;


            // To refresh ITEMS table
            this.current_transaction_entry_id = this.transaction_entry_id;

            //this.$Progress.start();
            
            this.form_entry.amount = parseFloat(this.form_entry.amount + this.form_item.sub_total).toFixed(2) * 1;
            this.form_entry.amount_ex_tax = (this.form_entry.amount_ex_tax + this.form_item.tax_excluded).toFixed(2) * 1;
            this.form_entry.vat += (this.form_item.vat * 1);
            
            ++this.item_no;
            
            this.items.push({ 
                item_no: this.item_no,
                transaction_entry_id: this.transaction_entry_id,
                transaction_no: this.form.transaction_no,
                transaction_type: this.form.transaction_type,
                account_code: this.form_entry.account_code,
                item: this.form_item.item,
                quantity: this.form_item.quantity,
                price: this.form_item.price, 
                sub_total: this.form_item.sub_total,
                tax_type: this.form_item.tax_type, 
                tax_excluded: this.form_item.tax_excluded, 
                vat: this.form_item.vat, 
                status: 'CONFIRMED'

            });
            $('#entry-items').modal('hide');
            
          },
          deleteItem(item_no,item_sub_total,item_tax_excluded,item_vat){

              this.form_entry.amount = parseFloat(this.form_entry.amount - item_sub_total).toFixed(2) * 1;
              this.form_entry.amount_ex_tax = (this.form_entry.amount_ex_tax - item_tax_excluded).toFixed(2) * 1;
              this.form_entry.vat = (this.form_entry.vat - item_vat).toFixed(2) * 1;

              this.items = this.items.filter(function( item ) {
                  return item.item_no !== item_no;
              });
              

          },
          branchChange(){
            this.form_entry.branch_id = this.selected_branch.id ;
            this.form_entry.branch_name = this.selected_branch.name;
          },
          selectEntryRow(current_transaction_entry_id){
              this.current_transaction_entry_id = current_transaction_entry_id;
              
          }
        },

        created() {
            this.loadPayees();
            this.loadBranch();
            this.initChartAccounts();
            this.user_id = document.querySelector('meta[name="user-id"]').getAttribute('content');


            /* Scrollbar fix
               If you have a modal on your page that exceeds the browser height, then you can't scroll in it when closing an second modal. To fix this add: */
            $(document).on('hidden.bs.modal', '.modal', function () {
                $('.modal:visible').length && $(document.body).addClass('modal-open');
            });


        },
        computed: {
            currentItems(){
                return this.items.filter(item  => 
                    {
                    return parseInt(item.transaction_entry_id)==parseInt(this.current_transaction_entry_id);
                  }  
                )
            }
        },
        components: {
          
          
        }
    }
</script>
<style type="text/css">
  
</style>