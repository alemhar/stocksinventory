<template>
    <div class="container">
        <div class="row mt-1">
        <div class="col-md-12">
          <div class="box mt-4">
            <!-- general form elements -->
          <div class="box box-warning">
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form">
              <div class="box-header with-border">
                <h3 class="box-title">Cash Disbursement</h3>
                <div class="box-tools">
                  <button type="submit" class="btn btn-primary">Save</button>
                  <button class="btn btn-success" @click="newCD">New<i class="fas fa-plus-circle fa-fw"></i></button>
                </div>
              </div>
              <div class="box-body row">
                <!--Left Col-->
                <div class="col-9">
                  <div class="form-group">
                    <label for="inputPayeesName">Payee</label>
                    <input type="text" class="form-control col-12" id="inputPayeesName" placeholder="Payees Name">
                  </div>
                  <div class="form-group">
                    <input type="text" class="form-control col-12" id="inputPayeesAddress" placeholder="Address">
                  </div>
                  <div class="form-group row">
                    <div class="col-6">
                      <input type="text" class="form-control" id="inputPayeesTIN" placeholder="TIN">
                    </div>
                    <div class="col-6">
                      <select class="form-control col-12">
                        <option>TAX TYPE</option>
                        <option>VAT REG</option>
                        <option>NON VAT</option>
                        <option>VAT EXEMPT</option>
                        <option>ZERO RATED</option>
                      </select>
                    </div>  
                  </div>
                  <div class="form-group">
                    <label>Account</label>
                    <select class="form-control col-12">
                      <option>SALARIES AND WAGES</option>
                      <option>TRAININGS AND SEMINARS</option>
                      <option>TRAVEL AND TRANSPORTATION</option>
                      <option>MEALS AMD SNACKS</option>
                      <option>REPRESENTATION EXPENSES</option>
                    </select>
                  </div>
                  

                </div>
                <!--Right Col-->
                <div class="col-3">
                  <div class="form-group">
                    <label for="inputReferenceNo">Reference No.</label>
                    <input type="text" class="form-control col-12" id="inputReferenceNo" placeholder="Reference No">
                  </div>
                  <div class="form-group">
                    <input type="text" class="form-control col-12" id="inputDate" placeholder="Date">
                  </div>
                  <div class="form-group">
                    <input type="text" class="form-control col-12" id="inputDCNo" placeholder="CD Number">
                  </div>
                  <div class="form-group">
                    <label for="inputAmount">Amount</label>
                    <input type="text" class="form-control col-12" id="inputAmount" placeholder="Amount">
                  </div>
                </div>

              </div>
              <!-- /.box-body -->

              <!-- div class="box-footer">
                
              </div -->
            </form>
          </div>
          <!-- /.box -->
          
          </div>  

          </div>
          <!-- /.box -->
        </div>
        </div>

        <div class="row mt-1" v-if="!$gate.isAdminOrAuthor()">
          <not-found></not-found>
        </div>  
      


</template>
<script>
    
</script>
