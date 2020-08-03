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
                <h3 class="box-title box-title-transaction" >Sales</h3>
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
                      <span class="input-group-text inputGroup-sizing-default">Payee</span>
                    </div>  
                    <input v-model="current_payee_name" v-bind:readonly="transaction_created" type="text" class="form-control col-12" id="inputPayeeName" placeholder="Payees Name">
                      
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
                      <span class="input-group-text inputGroup-sizing-default">CR #</span>
                    </div>
                    <input type="text" v-model="form.transaction_no" readonly class="form-control col-12" id="inputDCNo" placeholder="SALES Number">
                  </div>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text inputGroup-sizing-default">Amount</span>
                    </div>
                    
                    
                    <currency-input v-model="form.amount" v-bind:isReadonly="true" v-bind:fc="true" v-bind:col="12" id="inputAmount" placeholder="Amount"></currency-input>
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


          <div  v-show="transaction_created" class="box box-warning mt-2">
            <div class="col-md-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title box-title-transaction">Debits</h3>
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
                        <th>Tax Excluded</th>
                        <th>Tax</th>
                        <th>Option</th>
                        
                      </tr>
                      <tr v-for="entry in entries.data" :key="entry.id" @click="selectDebitRow(entry.id)" :class="{ 'table-warning' : active_debit_row == entry.id }" >
                        <td>{{ entry.account_code }}</td>
                        <td>{{ entry.account_name }}</td>
                        <td>{{ entry.branch_name }}</td>
                        <td>{{ entry.amount }}</td>
                        <td>{{ entry.amount_ex_tax }}</td>
                        <td>{{ entry.vat }}</td>
                        <td>
                          <a href="#" @click="deleteEntry(entry.id,entry.amount,entry.amount_ex_tax,entry.vat)">
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
                      <tr v-for="item in items.data" :key="item.id">
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

                  <currency-input v-model="form.amount_ex_tax" v-bind:isReadonly="true" v-bind:fc="true" v-bind:col="12" id="inputAmountExclusiveTax" placeholder="Amount Exclusive of Tax"></currency-input>

                  
                </div>
                </div>
              </div>

              <div class="form-group col-12 float-right">
                <div class="row">
                
                <label for="inputVAT" class="col-sm-9 col-form-label" style="text-align: right;">VAT</label>
                <div class="col-sm-3">

                  <currency-input v-model="form.vat" v-bind:isReadonly="true" v-bind:fc="true" v-bind:col="12" id="inputVAT" placeholder="VAT"></currency-input>

                 
                  </div>
                </div>
              </div>

              <div class="form-group col-12 float-right">
                <div class="row">
                
                <label for="inputWTax" class="col-sm-7 col-form-label" style="text-align: right;">Withholding Tax  <span v-if="wTaxExist" class="text-danger">  {{ this.wTaxExist.tax_rate}}%</span></label>
                <div class="col-sm-2">
                  
                  <input type="text" @blur="computerWTax" v-model="form.wtax_code" class="form-control col-12" id="inputwtax_code" placeholder="ATC Code"> 
                  <span v-if="!wTaxExist" class="text-danger"> Code Not Found!</span>
                  
                
                 
                  </div>
                  <div class="col-sm-3">
                    <currency-input v-model="form.wtax" v-bind:isReadonly="true" v-bind:fc="true" v-bind:col="12" id="inputwtax"></currency-input>
                  </div>
                
                </div>
              </div>


              <div class="form-group col-12 float-right">
                <div class="row">
                
                <label for="inputTotalAmount" class="col-sm-9 col-form-label" style="text-align: right;">Total Amount</label>
                <div class="col-sm-3">
                  <currency-input v-model="form.amount" v-bind:isReadonly="true" v-bind:fc="true" v-bind:col="12" id="inputTotalAmount" placeholder="Total Amount"></currency-input>

                  
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

              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text inputGroup-sizing-default">Branch</span>
                </div>
                  <select v-model="selected_branch" class="form-control col-12" aria-describedby="inputGroup-sizing-default" @change="branchChange">
                      <option v-for="branch in branches.data" v-bind:value="{ id: branch.id, name: branch.name }">{{ branch.name }}</option>
                  </select>
                  <has-error :form="form_entry" field="branch_id"></has-error>
              </div>
              <div class="input-group mb-2">
                <p v-show="no_entry_branch_id" class="empty-field-message">** Please indicate branch.</p>
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
                      <tr v-for="item in items.data" :key="item.id">
                        <td>{{ item.item }}</td>
                        <td>{{ item.quantity }}</td> 
                        <td>{{ item.price }}</td> 
                        <td>{{ item.sub_total }}</td> 
                        <td>{{ item.tax_excluded }}</td> 
                        <td>{{ item.vat }}</td> 
                        <td>
                          <a href="#" @click="deleteItem(item.id,item.sub_total,item.tax_excluded,item.vat)">
                            <i class="fa fa-trash"></i>
                          </a>
                        </td>
                      </tr>  


                  </tbody>
                </table>
                </div>
                <!-- /.box-body -->

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

              
                  <input v-model="Number(form_entry.amount_ex_tax).toLocaleString()" name="amount_ex_tax" id="amount_ex_tax"
                  
                  class="form-control" :class="{ 'is-invalid': form_entry.errors.has('amount_ex_tax') }" readonly aria-describedby="inputGroup-sizing-default">
                  <has-error :form="form_entry" field="amount_ex_tax"></has-error>
              </div>


              <div class="input-group mb-2">
                
                <div class="input-group-prepend">
                  <span class="input-group-text inputGroup-sizing-default">Tax</span>
                </div>
              
                  <input v-model="Number(form_entry.vat).toLocaleString()" name="vat" id="vat"
                  
                  class="form-control" :class="{ 'is-invalid': form_entry.errors.has('vat') }" readonly aria-describedby="inputGroup-sizing-default">
                  <has-error :form="form_entry" field="vat"></has-error>
              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" @click="cancelDebitEntry">Cancel</button>
              <button type="button" class="btn btn-success" @click="saveDebitEntry">Save</button>
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
              <button type="button" @click="saveItem" class="btn btn-success">Save</button>
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
              <!--button v-show="editmode" type="submit" class="btn btn-success">Update</button -->
              <!--button v-show="!editmode" type="submit" class="btn btn-primary">Create</button -->
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
      

    </div>
</template>
<script>

  
    export default {
        data() {
          return {
              user_id: '',
              //isModalVisible: false,
              editmode: false,
              transaction_created: false,
              no_payee: false,
              no_reference_no: false,
              no_account_code: false,
              no_item: false,
              no_price: false,
              no_quantity: false,
              no_entry_account_code: false,
              no_entry_branch_id: false,
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
                  transaction_type: 'SALES', // default for SALES
                  transaction_date: this.getDate(),
                  account_code: '',
                  account_name:'',
                  amount: 0,
                  credit_amount: 0,
                  debit_amount: 0,
                  amount_ex_tax: 0,
                  vat: 0,
                  canceled: 0,
                  user_id: document.querySelector('meta[name="user-id"]').getAttribute('content'),
                  wtax_code: 'NA',
                  wtax: 0
                  
                  
              }),
              form_entry: new Form({

                  id:'',
                  transaction_id:'',
                  transaction_no:'',
                  transaction_type:'',
                  account_code : '',
                  account_name: '',
                  //entry_name: '',
                  //entry_description: '',
                  branch_id: '',
                  branch_name: '',
                  amount: 0,
                  amount_ex_tax: 0,
                  
                  vat: 0,
                  credit_amount: 0,
                  debit_amount: 0,
                  transaction_date: this.getDate(),
                  
                  
              }),
              form_item: new Form({

                  id:'',
                  transaction_entry_id:'',
                  transaction_no:'',
                  transaction_type:'',
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
              items: {},
              entries: {},
              chart_of_accounts: {},
              chart_of_accounts_header: {},
              chart_of_accounts_detail: {},
              wtax: [],
              wTaxExist: true

          }
        },
        methods: {
          loadBranches(){

            if(this.$gate.isAdminOrUser()){
                axios.get("api/branch").then(({data}) => (this.branches = data ));
                //axios.get("api/user").then(({ data }) => (this.users = data.data));
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
              axios.get('api/chartaccount?headerordetail=header&transaction_type=SALES')
                .then((data)=>{
                  this.chart_of_accounts_header = data.data;
                })
                .catch(()=>{
                  //
                });
              axios.get('api/chartaccount?headerordetail=detail&transaction_type=SALES')
                .then((data)=>{
                  this.chart_of_accounts_detail = data.data;
                })
                .catch(()=>{
                  //
                });  
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
            
            if(this.form.reference_no.length == 0) {
              this.no_reference_no = true;
            } else {
              this.no_reference_no = false;
            }

            if (this.no_account_code || this.no_reference_no || this.no_payee){
              this.transaction_created = false;
            } else {
              this.transaction_created = true;
            }


            this.form.transaction_no = this.createSerialNumber();
            this.form.transaction_type = 'SALES';
            this.form.post('api/cd')
                .then((data)=>{
                  //console.log(data.data.id);
                  this.form.id = data.data.id;
                })
                .catch(()=>{
                  //
                });
            
          },
          saveTransaction(){
            if(this.form.amount == 0) {
              return false;
            }
            this.$Progress.start();
            this.form_entry.put('api/cd/'+this.form.id)
            .then(() => {
                swal.fire(
                    'Saved!',
                    'Transaction Completed.',
                    'success'
                  );
                  this.transaction_created = false;
                  this.form.post('api/cd/confirm/'+this.form.transaction_no);
                  this.$Progress.finish();
                                    
            })
            .catch(() => {
                this.$Progress.fail();
            });

            
            //this.$router.go();  

          },
          cancelTransaction(){
            this.transaction_created = false;
            this.form.delete('api/cd/cancel/'+this.form.transaction_no);
          },
          searchAccountModal(headerOrDetail = 'header'){
              this.headerOrDetail = headerOrDetail;
              this.searchText = this.form.account_code;
              //this.searchText = '';
              this.loadChartAccounts(headerOrDetail);
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
              let headerOrDetail = this.headerOrDetail;
              axios.get('api/searchAccount?q='+query+'&transaction_type=SALES&headerordetail='+headerOrDetail)
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
          loadEntryItems() {
              let entry_id = this.form_entry.id;
              axios.get('api/cd/items/list?entry_id='+entry_id)
                .then((data)=>{
                  this.items = data.data;
                })
                .catch(()=>{
                  //
                });
          },
          loadEntries() {
              let transaction_no = this.form.transaction_no;
              axios.get('api/cd/entries/list?transaction_no='+transaction_no)
                .then((data)=>{
                  this.entries = data.data;
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
          selectDebitRow(active_debit_row_id){
              this.active_debit_row = active_debit_row_id;
              this.form_entry.id = active_debit_row_id;
              VueListen.$emit('RefreshItemTable');
              //console.log(active_debit_row);

          },
          newEntry(){
              this.editmode = false;
              this.form_entry.reset();
              this.form_entry.transaction_id = this.form.id;
              this.form_entry.transaction_no = this.form.transaction_no;
              this.form_entry.transaction_type = 'SALES';

              this.form_entry.post('api/cd/entry')
                .then((data)=>{
                  this.form_entry.id = data.data.id;
                  //console.log(data.data.id);
                  VueListen.$emit('RefreshItemTable');    
                })
                .catch(()=>{
                  //
                });
                
              $('#entry-details').modal('show');


          },
          newItem(){

              if(this.form_entry.account_code == 0) {
                this.no_entry_account_code = true;
              } else {
                this.no_entry_account_code = false;
              }
              if(this.form_entry.branch_id == 0) {
                this.no_entry_branch_id = true;
              } else {
                this.no_entry_branch_id = false;
              }

              if (this.no_entry_account_code || this.no_entry_branch_id){
                return false;
              }

              this.editmode = false;
              this.form_item.reset();

              this.no_item = false;
              this.no_price = false;
              this.no_quantity = false;
              this.form_item.transaction_entry_id = this.form_entry.id;
              this.form_item.transaction_no = this.form.transaction_no;
              this.form_item.transaction_type = 'SALES';
              this.form_item.account_code = this.form_entry.account_code;

              this.form_item.post('api/cd/item')
                .then((data)=>{
                  this.form_item.id = data.data.id;
                  //console.log(data.data.id);
                })
                .catch(()=>{
                  //
                });
              this.loadEntryItems();
              $('#entry-items').modal('show');
          },
          cancelDebitEntry(){
            //this.$Progress.start();
            this.form_item.delete('api/cd/entry/'+this.form_entry.id)
            .then(() => {
                $('#entry-details').modal('hide');
                /*
                swal.fire(
                    'Updated!',
                    'Payee information has been updated.',
                    'success'
                  );
                */
                  //this.$Progress.finish();
                  VueListen.$emit('RefreshEntryTable');
            })
            .catch(() => {
                this.$Progress.fail();
            });
          },
          saveDebitEntry(){
            //console.log('Edit Payee');
            
            // ** Temporary data to bypass Column cannot be null ERROR's
            //this.form_entry.amount = 0;
            //this.form_entry.amount_ex_tax = 0;
            //this.form_entry.vat = 0;
            this.form_entry.credit_amount = this.form_entry.amount
            //this.form_entry.debit_amount = 0;
            // ** Temporary data to bypass Column cannot be null ERROR's

            this.$Progress.start();
            this.form_entry.put('api/cd/entry/'+this.form_entry.id)
            .then(() => {
                $('#entry-details').modal('hide');
                /*
                swal.fire(
                    'Updated!',
                    'Payee information has been updated.',
                    'success'
                  );
                */
                  this.form.amount += this.form_entry.amount;
                  this.form.amount_ex_tax += this.form_entry.amount_ex_tax;
                  this.form.vat += this.form_entry.vat;

                  this.$Progress.finish();
                  VueListen.$emit('RefreshEntryTable');
            })
            .catch(() => {
                this.$Progress.fail();
            });
          },
          deleteEntry(entry_id,entry_amount,entry_amount_ex_tax,entry_vat){
            this.form_item.delete('api/cd/entry/'+entry_id)
              .then(() => {
                  //$('#entry-items').modal('hide');
                  /*
                  swal.fire(
                      'Updated!',
                      'Payee information has been updated.',
                      'success'
                    );
                  */
                    this.form.amount = parseFloat(this.form.amount - entry_amount).toFixed(2) * 1;
                    this.form.amount_ex_tax = (this.form.amount_ex_tax - entry_amount_ex_tax).toFixed(2) * 1;
                    this.form.vat = (this.form.vat - entry_vat).toFixed(2) * 1;
                    
                    VueListen.$emit('RefreshItemTable');
                    VueListen.$emit('RefreshEntryTable');

              })
              .catch(() => {
                  this.$Progress.fail();
              });
          },
          cancelItem(){
            //this.$Progress.start();
            this.form_item.delete('api/cd/item/'+this.form_item.id)
            .then(() => {
                $('#entry-items').modal('hide');
                /*
                swal.fire(
                    'Updated!',
                    'Payee information has been updated.',
                    'success'
                  );
                */
                  //this.$Progress.finish();

                  VueListen.$emit('RefreshItemTable');
            })
            .catch(() => {
                this.$Progress.fail();
            });
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

            

            this.$Progress.start();
            this.form_item.put('api/cd/item/'+this.form_item.id)
            .then(() => {
                $('#entry-items').modal('hide');
                /*
                swal.fire(
                    'Updated!',
                    'Payee information has been updated.',
                    'success'
                  );
                */
                  this.form_entry.amount = parseFloat(this.form_entry.amount + this.form_item.sub_total).toFixed(2) * 1;
                  this.form_entry.amount_ex_tax = (this.form_entry.amount_ex_tax + this.form_item.tax_excluded).toFixed(2) * 1;
                  this.form_entry.vat += (this.form_item.vat * 1);
                  
                  this.$Progress.finish();
                  VueListen.$emit('RefreshItemTable');
            })
            .catch(() => {
                this.$Progress.fail();
            });
          },
          deleteItem(item_id,item_sub_total,item_tax_excluded,item_vat){
              this.form_item.delete('api/cd/item/'+item_id)
              .then(() => {
                  //$('#entry-items').modal('hide');
                  /*
                  swal.fire(
                      'Updated!',
                      'Payee information has been updated.',
                      'success'
                    );
                  */
                    this.form_entry.amount = parseFloat(this.form_entry.amount - item_sub_total).toFixed(2) * 1;
                    this.form_entry.amount_ex_tax = (this.form_entry.amount_ex_tax - item_tax_excluded).toFixed(2) * 1;
                    this.form_entry.vat = (this.form_entry.vat - item_vat).toFixed(2) * 1;
                    
                    VueListen.$emit('RefreshItemTable');
              })
              .catch(() => {
                  this.$Progress.fail();
              });
          },
          branchChange(){
            this.form_entry.branch_id = this.selected_branch.id ;
            this.form_entry.branch_name = this.selected_branch.name;
          },
          loadWTax(){
            //axios.get("api/taxrate").then(({data}) => (this.wtax = json_decode(data.data) ));

            axios.get('api/taxrate')
                .then((response)=>{
                  this.wtax = response.data;
                })
                .catch(()=>{
                  //
                });
          },
          computerWTax(){
            if(!this.form.wtax_code){
              return false;
            }
            this.wTaxExist = this.wtax.find(tax => tax.atc_code == this.form.wtax_code);
            if(this.wTaxExist){
               this.form.wtax = (this.form.amount_ex_tax * (this.wTaxExist.tax_rate/100)).toFixed(2) * 1;
               this.form.amount = parseFloat(this.form.amount_ex_tax) + parseFloat(this.form.vat) - parseFloat(this.form.wtax);
            } else {

            }
          }

          


        },

        created() {
            
            this.loadPayees();
            this.loadBranches();
            this.initChartAccounts();

            this.loadEntryItems();
            this.loadEntries();
            this.loadWTax();
            //this.SearchIt = _.debounce(this.SearchIt, 1000);
            
            VueListen.$on('RefreshItemTable',() => {
                this.loadEntryItems();
                this.computerWTax();

            });
            
            VueListen.$on('RefreshEntryTable',() => {
                this.loadEntries();
                this.computerWTax();
            });
            

            this.user_id = document.querySelector('meta[name="user-id"]').getAttribute('content');
            //console.log(document.querySelector('meta[name="user-id"]').getAttribute('content'));
            //console.log(this.payees);

            //setInterval(() => this.loadUsers(),3000);


            /* Scrollbar fix
               If you have a modal on your page that exceeds the browser height, then you can't scroll in it when closing an second modal. To fix this add: */
            $(document).on('hidden.bs.modal', '.modal', function () {
                $('.modal:visible').length && $(document.body).addClass('modal-open');
            });

            
        },
        computed: {
            
          

        },
        components: {
          
          
        }
    }
</script>
<style type="text/css">

</style>