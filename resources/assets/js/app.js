/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.VueResource = require('vue-resource');



import VueRouter from 'vue-router';
import {store} from './store/index';
import VeeValidate from 'vee-validate';
import {ServerTable, ClientTable, Event} from 'vue-tables-2';


window.Vue.use(VueRouter);
window.Vue.use(VeeValidate);


Vue.http.headers.common['X-CSRF-TOKEN'] = document.head.querySelector('meta[name="csrf-token"]').content;

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import {Pagination} from 'vue-pagination-2';

Vue.component('pagination', Pagination);

Vue.component('headerApp', require('./components/Header/index.vue'));
Vue.component('viewList', require('./components/ListJson/index.vue'));
Vue.component('newJson', require('./components/NewJson/index.vue'));
Vue.component('viewJson', require('./components/ViewJson/index.vue'));
Vue.component('editJson', require('./components/EditJson/index.vue'));
Vue.component('mudForm', require('./components/MudForm/index.vue'));
Vue.component('adminUsers', require('./components/Admin/users.vue'));
Vue.component('adminDomain', require('./components/Admin/domains.vue'));
Vue.component('modal', require('./components/Modal/index.vue'));

Vue.use(ClientTable);

let routes = [
    {path: '/login', component: require('./components/Auth/MainPage.vue')},
    {path: '/reset-password-user/:key', component: require('./components/Auth/MainPage.vue')},
    {path: '/', component: require('./components/ListJson/index.vue')},
    {path: '/create', component: require('./components/NewJson/index.vue')},
    {path: '/show/:userId', component: require('./components/ViewJson/index.vue')},
    {path: '/edit/:id', component: require('./components/EditJson/index.vue')},
    //admin
    {path: '/admin/users', component: require('./components/Admin/users.vue')},
    {path: '/admin/domains', component: require('./components/Admin/domains.vue')},
    {path: '/admin/files', component: require('./components/Admin/files.vue')}
];

let router = new VueRouter({
    routes: routes,
    mode: 'history'
});

const app = new Vue({
    el: '#app',
    store: store,
    router: router,
});
