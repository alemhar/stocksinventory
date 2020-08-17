const test = (message = 'testCD') => {
    console.log(message);
}

const loadBranches = () => {

    if(this.$gate.isAdminOrUser()){
        axios.get("api/branch").then(({data}) => (this.branches = data ));
        //axios.get("api/user").then(({ data }) => (this.users = data.data));
    } 
     
  };

export { test }
/*
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

      axios.get('api/chartaccount?headerordetail=header&transaction_type=CD')
        .then((data)=>{
          this.chart_of_accounts_header = data.data;
        })
        .catch(()=>{
          //
        });
        
      axios.get('api/chartaccount?headerordetail=detail&transaction_type=CD')
        .then((data)=>{
          this.chart_of_accounts_detail = data.data;
        })
        .catch(()=>{
          //
        });  
  },
  
  //  Check if this is still being used.
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
    /*
    this.form.post('api/cd')
        .then((data)=>{
          //console.log(data.data.id);
          this.form.id = data.data.id;
        })
        .catch(()=>{
          //
        });
    */

  },
  saveCD(){
    if(this.form.amount == 0) {
      return false;
    }
    this.$Progress.start();
    this.form_entry.put('api/cd/'+this.form.id)
    .then(() => {
          this.cd_created = false;
          this.form.post('api/cd/confirm/'+this.form.transaction_no);
          this.ledgers.push({ 
              id: this.form.id,
              transaction_id: this.form.id, 
              transaction_no: this.form.transaction_no,
              transaction_type: this.form.transaction_type,
              account_code: this.form.account_code,
              account_name: this.form.account_name,
              transaction_date: this.form.transaction_date,
              credit_amount: this.form.amount,
              debit_amount: 0
            });
          this.ledgers.push({ 
              id: 1,
              transaction_id: this.form.id, 
              transaction_no: this.form.transaction_no,
              transaction_type: this.form.transaction_type,
              account_code: '1105110',
              account_name: 'Input Tax',
              transaction_date: this.form.transaction_date,
              credit_amount: 0,
              debit_amount: this.form.vat
            });  

              let rawData = {
                  ledgers: this.ledgers
              }
              rawData = JSON.stringify(rawData);
              let formData = new FormData();
                  formData.append('ledgers', rawData);
              axios.post('api/ledgers', formData, {
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
                
          this.$Progress.finish();
                            
    })
    .catch(() => {
        this.$Progress.fail();
    });

    swal.fire({
          title: 'Saved!',
          text: "Journal posted",
          icon: 'info',
          showCancelButton: false,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ok'
        }).then((result) => {
          if (result.value) {
            //Reload Current Page
            this.$router.go();        
          }
        });
     

  },
  cancelCD(){
    this.cd_created = false;
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
      axios.get('api/searchAccount?q='+query+'&transaction_type=CD&headerordetail='+headerOrDetail)
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
      //this.form_entry.transaction_id = this.form.id;
      this.form_entry.transaction_id = 0;
      this.form_entry.transaction_no = this.form.transaction_no;
      this.form_entry.transaction_type = 'CD';
      this.save_button_entry_enabled = true;
      /*  
      this.form_entry.post('api/cd/entry')
        .then((data)=>{
          this.form_entry.id = data.data.id;
          //console.log(data.data.id);
          VueListen.$emit('RefreshItemTable');    
        })
        .catch(()=>{
          //
        });
      */  
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

      this.save_button_item_enabled = true;

      this.editmode = false;
      this.form_item.reset();

      this.no_item = false;
      this.no_price = false;
      this.no_quantity = false;
      this.form_item.transaction_entry_id = this.form_entry.id;
      this.form_item.transaction_no = this.form.transaction_no;
      this.form_item.transaction_type = 'CD';
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
    this.save_button_entry_enabled = false;
      
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

          this.ledgers.push({ 
              id: this.form_entry.id,
              transaction_id: this.form.id, 
              transaction_no: this.form.transaction_no,
              transaction_type: this.form.transaction_type,
              account_code: this.form_entry.account_code,
              account_name: this.form_entry.account_name,
              transaction_date: this.form.transaction_date,
              credit_amount: 0,
              debit_amount: this.form_entry.amount_ex_tax
            });
          //console.log(this.ledgers);
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

    this.save_button_item_enabled = false;
    

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
  }
*/