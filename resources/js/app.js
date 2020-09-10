/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import moment from 'moment';
import { Form, HasError, AlertError } from 'vform';

import Gate from "./Gate";
Vue.prototype.$gate = new Gate(window.user);

import money from './v-money';
Vue.use(money, {precision: 2});

//npm install vue-progressbar --save
import swal from 'sweetalert2';
window.swal = swal;

const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});

window.toast = toast;

//npm i axios vform | https://github.com/cretueusebiu/vform
window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

Vue.component('pagination', require('laravel-vue-pagination'));

Vue.component('currency-input', {
    props: ["value","isReadonly","fc","col"],
    template: `
        <div>
            <input type="text" v-bind:readonly="isReadonly" v-bind:class="[{'form-control': fc}, col ? 'col-'+col: '']" v-model="displayValue" @blur="isInputActive = false" @focus="isInputActive = true"/>
        </div>`,
    data: function() {
        return {
            isInputActive: false
        }
    },
    computed: {
        displayValue: {
            get: function() {
                if (this.isInputActive) {
                    // Cursor is inside the input field. unformat display value for user
                    return this.value.toString()
                } else {
                    // User is not modifying now. Format display value for user interface
                    return this.value.toFixed(2).replace(/(\d)(?=(\d{3})+(?:\.\d+)?$)/g, "$1,")
                    //return "$ " + this.value.toFixed(2).replace(/(\d)(?=(\d{3})+(?:\.\d+)?$)/g, "$1,")
                }
            },
            set: function(modifiedValue) {
                // Recalculate value after ignoring "$" and "," in user input
                let newValue = parseFloat(modifiedValue.replace(/[^\d\.]/g, ""))
                // Ensure that it is not NaN
                if (isNaN(newValue)) {
                    newValue = 0
                }
                // Note: we cannot set this.value as it is a "prop". It needs to be passed to parent component
                // $emit the event so that parent component gets it
                this.$emit('input', newValue)
            }
        }
    }
});




import VueRouter from 'vue-router';
Vue.use(VueRouter);

/*
import DynamicSelect from 'vue-dynamic-select';
Vue.use(DynamicSelect);
Vue.component('DynamicSelect', DynamicSelect);
*/




//import ModelSelect from 'vue-search-select'
//Vue.use(ModelSelect);
//Vue.component('ModelSelect', ModelSelect);

//npm install vue-progressbar | http://hilongjw.github.io/vue-progressbar/index.html 
import VueProgressBar from 'vue-progressbar'
Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '3px'
  });

let routes = [
  { path: '/dashboard', component: require('./components/Dashboard.vue').default },
  { path: '/developer', component: require('./components/Developer.vue').default },
  { path: '/account', component: require('./components/Account.vue').default },
  { path: '/users', component: require('./components/Users.vue').default },
  { path: '/cd', component: require('./components/CD.vue').default },
  { path: '/cr', component: require('./components/CR.vue').default },
  
  { path: '/sales', component: require('./components/Sales.vue').default },
  { path: '/purchase', component: require('./components/Purchase.vue').default },
  { path: '/payments', component: require('./components/Payments.vue').default },
  
  { path: '/payees', component: require('./components/Payees.vue').default },
  { path: '/ledger', component: require('./components/Ledger.vue').default },
  { path: '/collections', component: require('./components/Collections.vue').default },
  { path: '/generaljournal', component: require('./components/GeneralJournal.vue').default },
  { path: '/transations', component: require('./components/Transactions.vue').default },
  { path: '*', component: require('./components/NotFound.vue').default }
  
];

const router = new VueRouter({
  mode: 'history',
  routes // short for `routes: routes`
});


Vue.filter('upText',function(text){
	return text.charAt(0).toUpperCase() + text.slice(1);
});

Vue.filter('formatDate',function(created){
	return moment(created).format('ll');
});

const VueListen = new Vue();
window.VueListen = VueListen;


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);

Vue.component(
    'not-found',
    require('./components/NotFound.vue').default
);

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

Vue.component('search-account', require('./components/SearchAccount.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


const app = new Vue({
    el: '#app',
    router,
    data: {
      search: '',
      readabilityObject: {
        fontSize: 14
      }
      
    },
    methods:{
      SearchIt: _.debounce(() => {
            VueListen.$emit('Search');
        },1000),

        printme() {
            window.print();
        }

    }
});

