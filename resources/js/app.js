/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
*/             
import $ from 'jquery';
import jQuery from 'jquery';
window.$ = $;
window.jQuery = jQuery
import './bootstrap';
import axios from 'axios';
window.axios = axios;
import Vue from "vue";
const _ = require('lodash')
window.Vue = require('vue').default;
const files = require.context('./components/', true, /\.vue$/i,'lazy');
files.keys().map(key => Vue.component( (key.split('/').pop().split('.')[0]).replace(/([a-zA-Z])(?=[A-Z])/g,'$1-').toLowerCase(), () => import('./components'+key.replace('.',''))));

