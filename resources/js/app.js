/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// import './bootstrap';
import { createApp } from 'vue';
import App from './pages/App.vue';
import router from './router.js';  // Adjust path if necessary
import Vue3Toastify, { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import PrimeVue from "primevue/config";  // Theme
import { createHead } from '@vueuse/head';
// app.js or main.js

// Theme for PrimeVue v3
import 'primevue/resources/themes/saga-blue/theme.css';  // Theme for PrimeVue v4
import 'primevue/resources/primevue.css';               // Core PrimeVue styles
import 'primeicons/primeicons.css';

const app = createApp(App);
const head = createHead();
app.use(head);

import InputMask from "primevue/inputmask";
import Chips from 'primevue/chips';

import Echo from 'laravel-echo';
import io from 'socket.io-client';

window.io = io;  // ← MUST be global

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: `${window.location.hostname}:7001`,
    transports: ['websocket', 'polling']
});

console.log('Echo ready:', window.Echo);

window.Echo.connector.socket.on('connect', () => {
    console.log('CONNECTED:', window.Echo.connector.socket.io.uri);
});


import odearnnav from './pages/DashboardNav.vue';
app.component('odearnnav-component', odearnnav);
import homenav from './pages/HomeNav.vue';
app.component('homenav-component', homenav);



app.component("input-mask", InputMask);
app.component("chips", Chips);

app.use(router);
app.use(PrimeVue);
app.mount('#app');

