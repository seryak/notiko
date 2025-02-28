import './bootstrap';
import { createApp } from 'vue';
import Note from './components/Note.vue';

// Подключаем popup
import '../css/popup.css';
import '../js/popup.js';

const app = createApp({
    components: {
        Note
    }
}).mount('#app');

// Регистрируем popup
window.poper = window.popup.register('note-popup', {
    center: true,
    beforeOpen: function(event) {
        console.log('beforeOpen', event);
        const content = event.detail.content;
        document.querySelector('.popup-content').innerHTML = content;
    }
});
