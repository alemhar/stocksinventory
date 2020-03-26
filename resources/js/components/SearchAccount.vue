<template>
  <transition name="sa-modal-fade">
    <div class="sa-modal-backdrop">
      <div class="sa-modal"
        role="dialog"
        aria-labelledby="modalTitle"
        aria-describedby="modalDescription"
      >
        <header
          class="sa-modal-header"
          id="modalTitle"
        >
          <slot name="header">
            Search Account

            <button
              type="button"
              class="sa-btn-close"
              @click="close"
              aria-label="Close modal"
            >
              x
            </button>
          </slot>
        </header>
        <section
          class="sa-modal-body"
          id="modalDescription"
        >
          <slot name="body">
            <BootstrapTable
              ref="table"
              :columns="columns"
              :data="data"
              :options="options"
              @on-post-body="onPostBody"
            />
          </slot>
        </section>
        <footer class="sa-modal-footer">
          <slot name="footer">
            I'm the default footer!

            <button
              type="button"
              class="sa-btn-green"
              @click="close"
              aria-label="Close modal"
            >
              Close me!
            </button>
          </slot>
        </footer>
      </div>
    </div>
  </transition>
</template>
<script>
  export default {
    data: function() {
      return {
        name: 'modal',

        methods: {
          close() {
            this.$emit('close');
          },
        },
        mounted: {

        },
                  
        columns: [
          {
            title: 'Item ID',
            field: 'id'
          },
          {
            field: 'name',
            title: 'Item Name'
          }, {
            field: 'price',
            title: 'Item Price'
          }
        ],
        data: [
          {
            id: 1,
            name: 'Item 1',
            price: '$1'
          }
        ],
        options: {
          search: true,
          showColumns: true
        }
                  
      };
    }



    
  };
      
  
</script>

<style>
  .sa-modal-backdrop {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: rgba(0, 0, 0, 0.3);
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .sa-modal {
    background: #FFFFFF;
    box-shadow: 2px 2px 20px 1px;
    overflow-x: auto;
    display: flex;
    flex-direction: column;
    width: 600px;
  }

  .sa-modal-header,
  .sa-modal-footer {
    padding: 15px;
    display: flex;
  }

  .sa-modal-header {
    border-bottom: 1px solid #eeeeee;
    color: #4AAE9B;
    justify-content: space-between;
  }

  .sa-modal-footer {
    border-top: 1px solid #eeeeee;
    justify-content: flex-end;
  }

  .sa-modal-body {
    position: relative;
    padding: 20px 10px;
  }

  .sa-btn-close {
    border: none;
    font-size: 20px;
    padding: 20px;
    cursor: pointer;
    font-weight: bold;
    color: #4AAE9B;
    background: transparent;
  }

  .sa-btn-green {
    color: white;
    background: #4AAE9B;
    border: 1px solid #4AAE9B;
    border-radius: 2px;
  }
  .sa-modal-fade-enter,
  .sa-modal-fade-leave-active {
    opacity: 0;
  }

  .sa-modal-fade-enter-active,
  .sa-modal-fade-leave-active {
    transition: opacity .5s ease
  }
</style>