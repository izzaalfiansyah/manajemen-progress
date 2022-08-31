import { createApp } from 'vue';
import { createRouter, createWebHashHistory } from 'vue-router';
import App from './App.vue';
import routes from './router';

import './main.css';

const router = createRouter({
	history: createWebHashHistory(),
	routes,
});

const app = createApp(App);

app.use(router);

app.mount('#app');
