/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//require('./bootstrap');

//window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/UploadBlock.vue -> <example-component></example-component>
 */

//import Vue from 'vue'; //cause an error
import VueRouter from 'vue-router';
Vue.use(VueRouter);


// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

import ProductImportComponent from './components/ProductImportComponent.vue';
import AboutElement from './components/router/AboutElement.vue';
import ProductsViewer from './components/router/ProductsViewer.vue';
import SingleProduct from './components/router/SingleProduct.vue';
import Page404 from './components/router/Page404.vue';

//Router
const router = new VueRouter({
    mode: 'history',
    routes: [
        { path: '/', component: ProductImportComponent, name: 'home', },
        {
            path: '/about',
            component: AboutElement,
            name: 'about',
            children: [
                {
                    path: ':id',
                    components: {
                        default: SingleProduct,
                        preproducts: ProductsViewer,
                    },
                    props: {
                        default: true,
                        preproducts: false,
                    },
                },
            ],
        },
        { path: '/products', component: ProductsViewer, name: 'all-products' },
        { path: '*', component: Page404, name: 'not-found' },
    ],
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

export const eventBus = new Vue();

const app = new Vue({
    router,
    el: '#app',
});

/*Pusher.logToConsole = true;

var pusher = new Pusher('ede761db4039e47ce7e0', {
    cluster: 'eu'
});

var channel = pusher.subscribe('import-current-status');
channel.bind('changed', function(data) {
    console.log(data);
});*/