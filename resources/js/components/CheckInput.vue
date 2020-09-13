<!-- CheckInput.vue -->
<template>
  <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
    <!-- Simplest - call `$emit()` inline-->
    <!-- button type="button" name="button" v-on:click="$emit('increment')">Click me to increment!</button -->

    
            <div class="modal-header">
              <button type="button" class="close"  @click="updateCheckDetails" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form onsubmit="return false;">
            <div class="modal-body">
              
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <span class="input-group-text inputGroup-sizing-default">Check #</span>
                    </div>
                    <input type="text" v-model="check.check_no" class="form-control col-12" id="check_no" placeholder="Check No">
                    <!-- p v-show="no_reference_no" class="empty-field-message">** Please enter reference number!</p -->
                </div>

                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <span class="input-group-text inputGroup-sizing-default">Bank</span>
                    </div>
                    <input type="text"  v-model="check.check_bank" class="form-control col-12" id="check_bank" placeholder="Bank">
                    <!-- p v-show="no_reference_no" class="empty-field-message">** Please enter reference number!</p -->
                </div>

                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <span class="input-group-text inputGroup-sizing-default">Branch</span>
                    </div>
                    <input type="text"  v-model="check.check_bank_branch" class="form-control col-12" id="check_bank_branch" placeholder="Branch">
                    <!-- p v-show="no_reference_no" class="empty-field-message">** Please enter reference number!</p -->
                </div>

                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <span class="input-group-text inputGroup-sizing-default">Check Date</span>
                    </div>
                    <input type="date"  v-model="check.check_date" class="form-control col-12" id="check_date" placeholder="Date">
                    <!-- p v-show="no_reference_no" class="empty-field-message">** Please enter reference number!</p -->
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" @click="updateCheckDetails">Close</button>
            </div>

            </form>
          

  </div>
</div>
</template>
<script>
export default {
  data() {
    return {
      check: {
            check_no: '',
            check_bank: '',
            check_bank_branch: '',
            check_date: '',
            check_amount: 0
        },
    //   props: {
    //         parentCheckDetails: Object,
    //     }  
    }
  },
  name: 'CheckInput',
  methods: {
    // Define the method that emits data to the parent as the first parameter to `$emit()`.
    // This is referenced in the <template> call in the parent. The second parameter is the payload.
    emitToParent (event) {
      this.$emit('updateCheckDetails', this.check)
    }
  }
}
</script>