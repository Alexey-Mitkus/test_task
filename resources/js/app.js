"use strict";

import route from '../../vendor/tightenco/ziggy/dist/';
import { Ziggy } from './ziggy';
import VueJsonp from 'vue-jsonp';
import moment from 'moment';
import Vuelidate from 'vuelidate';
import VueCookie from 'vue-cookie';
import VueLodash from 'vue-lodash';
import lodash from 'lodash';
import vSelect from 'vue-select';

// window._ = require('lodash');
window.axios = require('axios');
window.collect = require('collect.js');
window.Vue = require('vue').default;
window.moment = moment;
window.route = route;
window.Ziggy = Ziggy;


window.axios.defaults.headers.common = {
    // 'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    'X-Requested-With': 'XMLHttpRequest'
};
// window.axios.defaults.headers.common.crossDomain = true;

if( document.head.querySelector('meta[name="api-token"]') !== null )
{
    window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + document.head.querySelector('meta[name="api-token"]').getAttribute('content');
}


Vue.mixin({
    methods: {
        // route: function ( name, params, absolute ) { return route(name, params, absolute, Ziggy); },
        // if you prefer ES6 syntax
        route: (name, params, absolute) => route(name, params, absolute, Ziggy),
        moment() {
            return moment();
        }
    }
});
 
Vue.filter('formatDate', function(value){
    if( value )
    {
        return moment(String(value)).format('DD/MM/YYYY')
    }
});

const files = require.context('./', true, /\.vue$/i);

files.keys().map((key) => {
    var namesArray = key.split('/'),
        NewName = '';
    namesArray.forEach(function(item, i){
        if( i > 1 )
        {
            NewName = NewName + (item.charAt(0).toUpperCase() + item.slice(1));
        }
    });
    return Vue.component(NewName.split('/').pop().split('.')[0], files(key).default);
});
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
//Vue.component('example-component', require('./components/ExampleComponent.vue').default);

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');
    require('bootstrap');
} catch (e) {}


Vue.use(VueJsonp);
Vue.use(Vuelidate);
Vue.use(VueCookie);
Vue.use(VueLodash, { lodash: lodash });

Vue.component('v-select', vSelect);

const app = new Vue({
    el: '#app',
});
