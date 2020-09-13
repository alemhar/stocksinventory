<!-- CheckInput.vue -->
<template>
  <div class="child">
    <!-- Simplest - call `$emit()` inline-->
    <!-- button type="button" name="button" v-on:click="$emit('increment')">Click me to increment!</button -->

    <div class="input-group mb-2">
        <div class="input-group-prepend">
            <span class="input-group-text inputGroup-sizing-default">Check #</span>
        </div>
        <input type="text" v-model="check.check_no" class="form-control col-12" id="check_no" placeholder="Check No"  v-on:keyup="emitToParent">
        <!-- p v-show="no_reference_no" class="empty-field-message">** Please enter reference number!</p -->
    </div>

    <div class="input-group mb-2">
        <div class="input-group-prepend">
            <span class="input-group-text inputGroup-sizing-default">Bank</span>
        </div>
        <input type="text"  v-model="check.check_bank" class="form-control col-12" id="check_bank" placeholder="Bank"  v-on:keyup="emitToParent">
        <!-- p v-show="no_reference_no" class="empty-field-message">** Please enter reference number!</p -->
    </div>

    <div class="input-group mb-2">
        <div class="input-group-prepend">
            <span class="input-group-text inputGroup-sizing-default">Branch</span>
        </div>
        <input type="text"  v-model="check.check_bank_branch" class="form-control col-12" id="check_bank_branch" placeholder="Branch"  v-on:keyup="emitToParent">
        <!-- p v-show="no_reference_no" class="empty-field-message">** Please enter reference number!</p -->
    </div>

    <div class="input-group mb-2">
        <div class="input-group-prepend">
            <span class="input-group-text inputGroup-sizing-default">Check Date</span>
        </div>
        <input type="date"  v-model="check.check_date" class="form-control col-12" id="check_date" placeholder="Date"  v-on:change="emitToParent">
        <!-- p v-show="no_reference_no" class="empty-field-message">** Please enter reference number!</p -->
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