<template>

    <div class="container">
        <div class="row mt-1" v-if="$gate.isAdminOrUser()">
        <div class="col-md-12">
          <div class="box mt-4">
          <div class="box-header">
            <h3 class="box-title">Cash Disbursement</h3>
            <div class="box-tools">
              <div class="input-group">
                <label>Search</label>
                <input type="text" name="search" v-model="searchText" @change="SearchIt" class="float-right col-8">
                <span class="input-group-btn col-1">
                    <button type="button" v-show="true" class="btn btn-success" @click="searchAccountModal('header')"><i class="fas fa-search fa-fw"></i></button>

                </span>
              </div>
              
            </div>
          </div>  
          <!-- CD List     
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


              <div  v-show="true" class="box box-warning mt-2">
                <div class="col-md-12">
                  <div class="box">
                    


                    <!-- /.box-header -->
                    <div id="cd-list" class="box-body table-responsive no-padding">
                      <table class="table table-hover">
                        <tbody>
                          <tr>
                            <th>CD No.</th>
                            <th>Payee</th>
                            <th>Account</th>
                            <th>Ref. #</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Option</th>
                            
                          </tr>
                          <tr v-for="cd in cds.data" :key="cd.id" @click="selectCDRow(cd.transaction_no)" :class="{ 'table-warning' : transaction_no == cd.transaction_no }" >
                            <td>{{ cd.transaction_no }}</td>
                            <td>{{ cd.payee ? cd.payee.name : ''}}</td>
                            <td>{{ cd.branch_name }}</td>
                            <td>{{ cd.amount }}</td>
                            <td>{{ cd.amount_ex_tax }}</td>
                            <td>{{ cd.vat }}</td>
                            <td>
                              <a href="#" @click="viewCD(cd.id)">
                                <i class="fa fa-eye"></i>

                              </a>
                            </td>
                          </tr>
                          
                      </tbody>
                    </table>
                    </div>
                    <div class="box-footer">
                      <pagination :data="cds" @pagination-change-page="getCDs"></pagination>
                    </div> 

                  </div>
                  <!-- /.box -->
                </div>

              </div>  
              <!-- /.box -->






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


          <div  v-show="true" class="box box-warning mt-2">
            <div class="col-md-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Debits</h3>
                  <div class="box-tools">
                    <button class="btn btn-success" @click="">Add Items <i class="fas fa-plus-circle fa-fw"></i></button>
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
                        <!-- th>Option</th -->
                        
                      </tr>
                      <tr v-for="entry in entries.data" :key="entry.id" @click="selectDebitRow(entry.id)" :class="{ 'table-warning' : active_debit_row_id == entry.id }" >
                        <td>{{ entry.account_code }}</td>
                        <td>{{ entry.account_name }}</td>
                        <td>{{ entry.branch_name }}</td>
                        <td>{{ entry.amount }}</td>
                        <td>{{ entry.amount_ex_tax }}</td>
                        <td>{{ entry.vat }}</td>
                        <!-- td>
                          <a href="#" @click="deleteEntry(entry.id,entry.amount,entry.amount_ex_tax,entry.vat)">
                            <i class="fa fa-trash"></i>
                          </a>
                        </td -->
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

          <div v-show="true" class="box box-warning mt-2">
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

            <div v-show="cd_created" class="box box-warning mt-2">
              
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


      <div class="modal fade" v-show="cd_created" id="entry-details" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
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
                  <select v-model="selected_branch" class="form-control col-12" aria-describedby="inputGroup-sizing-default" @change="">
                      <option v-for="branch in branches.data" v-bind:value="{ id: branch.id, name: branch.name }">{{ branch.name }}</option>
                  </select>
                  <has-error :form="form_entry" field="branch_id"></has-error>
              </div>
              <div class="input-group mb-2">
                <p v-show="no_entry_branch_id" class="empty-field-message">** Please indicate branch.</p>
              </div>
              <div class="box-header">
                  <h3 class="box-title">Items</h3>
                  <div class="box-tools">
                    <button class="btn btn-success" @click="">Add Items <i class="fas fa-plus-circle fa-fw"></i></button>
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
              <button type="button" class="btn btn-danger" @click="">Cancel</button>
              <button type="button" class="btn btn-success" @click="">Save</button>
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


      <div class="modal fade"  v-show="cd_created"  id="entry-items" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
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
                  @change=""
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
                  @change=""
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
                  @change=""
                  class="form-control" :class="{ 'is-invalid': form_item.errors.has('sub_total') }" aria-describedby="inputGroup-sizing-default" readonly>
                <has-error :form="form_item" field="entry_description"></has-error>
              </div>

              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text inputGroup-sizing-default">Tax Type</span>
                </div>

              

                <select v-model="form_item.tax_type" @change="" class="form-control col-12" aria-describedby="inputGroup-sizing-default">
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
              <button type="button" @click="" class="btn btn-danger">Cancel</button>
              <button type="button" @click="" class="btn btn-success">Save</button>
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
              active_debit_row_id: 0,
              transaction_no: 0,
              selected_branch: {},
              cd : {},
              form: new Form({

                  id:'',
                  payee_id: '',
                  reference_no: '',
                  transaction_no: '',
                  transaction_type: 'CD', // default for Cash Disbursement
                  transaction_date: '',
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
                  //entry_name: '',
                  //entry_description: '',
                  branch_id: '',
                  branch_name: '',
                  amount: 0,
                  amount_ex_tax: 0,
                  
                  vat: 0,
                  credit_amount: 0,
                  debit_amount: 0,
                  transaction_date: '',
                  
                  
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
              cds:{},
              chart_of_accounts: {},
              chart_of_accounts_header: {},
              chart_of_accounts_detail: {}

          }
        },
        methods: {
          getCDs(page = 1) {
            axios.get('api/cd?page=' + page)
              .then(response => {
                this.cds = response.data;
              });
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
              let transaction_no = this.transaction_no;
              axios.get('api/cd/entries/list?transaction_no='+transaction_no)
                .then((data)=>{
                  this.entries = data.data;
                })
                .catch(()=>{
                  //
                });
          },
          loadCDs() {
              axios.get("api/cd").then(({ data }) => (this.cds = data));
          },
          
          selectDebitRow(active_debit_row_id){
              this.active_debit_row_id = active_debit_row_id;
              //this.form_entry.id = active_debit_row_id;
              VueListen.$emit('RefreshItemTable');
              //console.log(active_debit_row);

          },
          selectCDRow(transaction_no){
              this.transaction_no = transaction_no;
              //this.form_entry.id = active_debit_row_id;
              VueListen.$emit('RefreshEntryTable');
              //console.log(active_debit_row);

          },

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
            
            this.loadCDs();
            //this.loadPayees();
            //this.loadBranches();
            //this.initChartAccounts();

            this.loadEntryItems();
            this.loadEntries();
            //this.SearchIt = _.debounce(this.SearchIt, 1000);
            
            VueListen.$on('RefreshItemTable',() => {
                this.loadEntryItems();
            });
            
            VueListen.$on('RefreshEntryTable',() => {
                this.loadEntries();
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
          
          
        }
    }
</script>
<style type="text/css">

</style>