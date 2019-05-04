
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import VeeValidate from 'vee-validate';
import VueRouter from 'vue-router';
import BootstrapVue from 'bootstrap-vue';
import City from './components/ControlPanel/City/CityComponent.vue';
import Area from './components/ControlPanel/Area/AreaComponent.vue';
import Specialty from './components/ControlPanel/Specialty/SpecialtyComponent.vue';
import Doctor from './components/ControlPanel/Doctor/DoctorComponent.vue';
import SubSpecialty from './components/ControlPanel/SubSpecialty/SubSpecialtyComponent.vue';
import Insurance from './components/ControlPanel/Insurance/InsuranceComponent.vue';
const router = new VueRouter({
    mode: 'history',
    routes:[
        {
            path: "/cityV",
            component: City
        },
        {
            path: "/areaV",
            component: Area
        },
        {
            path: "/specailtyV",
            component: Specialty
        },
        {
            path: "/sub_specailtyV",
            component: SubSpecialty
        },
        {
            path: "/doctorV",
            component: Doctor
        },
        {
            path: "/insuranceV",
            component: Insurance
        }
    ]
});
Vue.use(VueRouter)
Vue.use(BootstrapVue)
Vue.use(VeeValidate);
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router
});
