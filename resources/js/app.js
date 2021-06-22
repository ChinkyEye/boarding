/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');


import VueRouter from 'vue-router'
Vue.use(VueRouter)

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('student-main', require('./components/StudentMaster.vue').default);
Vue.component('sidebar', require('./components/student/Sidebar.vue').default);

import DatePicker from 'v-calendar/lib/components/date-picker.umd';
Vue.component('date-picker', DatePicker);

import { MonthPicker } from 'vue-month-picker'
import { MonthPickerInput } from 'vue-month-picker'
 
Vue.use(MonthPicker)
Vue.use(MonthPickerInput)

import VueMonthlyPicker from 'vue-monthly-picker'
 
Vue.use(VueMonthlyPicker)

import Vuex from 'vuex'
Vue.use(Vuex)

// import Vue from 'vue';
import VueHtmlToPaper from 'vue-html-to-paper';
Vue.use(VueHtmlToPaper);



import storeData from './store/index'
const store = new Vuex.Store(
		storeData
	)
//number lai word lai convert garna ko lagi
var converter = require('number-to-words');

Vue.filter('toWords', function(value) {
  if (!value) return '';
  return converter.toWords(value);
})

// import from page
require('./progressbar');

//Import Sweetalert2
import Swal from 'sweetalert2'
window.Swal = Swal
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  onOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})
window.Toast = Toast

//Import v-from
import { Form, HasError, AlertError } from 'vform'
window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

// ckeditor
import Vue from 'vue';
import CKEditor from 'ckeditor4-vue';
Vue.use( CKEditor );
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import {routes} from './routes';

const router = new VueRouter({
	routes,
	mode:'hash',
})

const app = new Vue({
    el: '#app',
    router,
    store
});
