	      check: {
                check_no: '',
                check_bank: '',
                check_bank_branch: '',
                check_date: '',
                check_amount: 0
              },
              checks: []





                  this.check= {
                    check_no: '',
                    check_bank: '',
                    check_bank_branch: '',
                    check_date: '',
                    check_amount: 0
                  };
                  this.checks = [];





      <!-- Check Details Modal 
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

      <div class="modal fade" id="check-details" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
        
          <check-input v-on:updateCheckDetails="updateCheckDetails"></check-input>
          
      </div>
      <!-- Check Details Modal -->   





    import CheckInput from './CheckInput.vue';






                if(this.check.check_no){

                    this.checks.push({ 
                        check_no: this.check.check_no,
                        check_bank: this.check.check_bank,
                        check_bank_branch: this.check.check_bank_branch,
                        check_date: this.check.check_date,
                        check_amount: this.form.amount,
                        reference_no: this.form.reference_no,
                        transaction_no: this.form.transaction_no,
                        transaction_type: this.form.transaction_type,
                        transaction_date: this.form.transaction_date,
                        deposit_reference_no: '',
                        deposit_date: '',
                        deposit_amount: 0,
                        status: 'CONFIRMED'
                    });  
                    

                    // Save Check START
                    let rawCheckData = {
                        checks: this.checks
                    }
                    rawCheckData = JSON.stringify(rawCheckData);
                    let formCheckData = new FormData();
                        formCheckData.append('checks', rawCheckData);
                    axios.post('api/check', formCheckData, {
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
                    // Save Check END

                }




showCheckDetails(){
              $('#check-details').modal('show');
          },
          updateCheckDetails(child_check){
              this.check.check_no = child_check.check_no;
              this.check.check_bank = child_check.check_bank;
              this.check.check_bank_branch = child_check.check_bank_branch;
              this.check.check_date = child_check.check_date;
              this.check.check_amount = child_check.check_amount;
              console.log('hide');
              $('#check-details').modal('hide');
            //console.log(value);          
          }
