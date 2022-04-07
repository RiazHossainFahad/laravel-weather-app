require('./bootstrap');
import Vue from 'vue'
window.Vue = Vue
import Vuelidate from 'vuelidate'


Vue.use(Vuelidate)

// Package Components

require('@/helpers/ObjectToFormData');


// Global Mixins 
Vue.mixin({
    methods: {
        ucFirst(str) {
            const string = str.replace(/_/g, " ");
            return string.charAt(0).toUpperCase() + string.slice(1);
        },
        showToastMessage: (message, type = 'success') => {
            toastr.options =
            {
                "closeButton": true,
                "progressBar": true
            }
            switch (type) {
                case 'success':
                    toastr.success(message);
                    break;
                case 'error':
                    toastr.error(message);
                    break;
                case 'warning':
                    toastr.warning(message);
                    break;
                case 'info':
                    toastr.info(message);
                    break;
            }
        },
    },
});

/*Components load in app.js for using in blade file*/
/* Ex: resources/js/components/tenant/TestComponent --> import as `<test-component/>`*/
const files = require.context('./', true, /\.vue$/i);
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

/*Init Vuejs with #app */
const app = new Vue({
    el: '#app'
});