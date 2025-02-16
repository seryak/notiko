import './bootstrap';
import { createApp } from 'vue';
import Note from './components/Note.vue';


const app = createApp({
    components: {
        Note
    }
}).mount('#app');