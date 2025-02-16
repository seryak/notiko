import './bootstrap';
import { createApp } from 'vue';
import NoteEditor from './components/NoteEditor.vue';


const app = createApp({
    components: {
        NoteEditor
    }
}).mount('#app');