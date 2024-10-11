import './bootstrap';
import { createApp } from 'vue';
import AutobotsCounter from './components/AutobotsCounter.vue';
import './styles.css';

const app = createApp({});

app.component('autobots-counter', AutobotsCounter);

app.mount('#app');
