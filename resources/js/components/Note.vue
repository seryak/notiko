<template>
  <div>
    <div v-if="!isEditing" @dblclick="editNote">
      <v-md-preview-html :html="markdownToHtml(note.content)" preview-class="vuepress-markdown-body"></v-md-preview-html>
      <button class="btn-view" @click="showPopup(note.id)">Просмотр</button>
    </div>
    <v-md-editor v-else v-model="note.content" mode="edit"
                 right-toolbar="fullscreen"
                 @blur="saveNote" />
  </div>
  <hr>
</template>

<script>
import VMdEditor from '@kangc/v-md-editor';
import '@kangc/v-md-editor/lib/style/base-editor.css';
import MarkdownIt from "markdown-it";
import VMdPreviewHtml from "@kangc/v-md-editor/lib/preview-html.js";
import '@kangc/v-md-editor/lib/style/preview-html.css';
// theme style
import './style.css';
import ru from '@kangc/v-md-editor/lib/lang/ru-RU.js';
VMdEditor.lang.use('ru-RU', ru);


export default {
  name: 'Note',
  props: {
    note: {
      type: Array,
      required: true
    }
  },
  components: {
    VMdEditor,
    VMdPreviewHtml
  },
  mounted() {
    document.addEventListener('click', this.handleClickOutside);
  },
  beforeUnmount() {
    document.removeEventListener('click', this.handleClickOutside);
  },
  data() {
    return {
      isEditing: false,
      customToolbar: [
        'bold',
        'italic',
        'strike',
        'underline',
      ], // Настройте панель инструментов по вашему усмотрению
    };
  },
  methods: {
    async showPopup(noteId) {
      try {
        const response = await fetch(`/notes/${noteId}`);
        const data = await response.json();
        console.log(data);
        const event = new CustomEvent('showPopup', {
          detail: {
            content: this.markdownToHtml(data.content)
          }
        });
        window.popup.open(window.poper, event);
      } catch (error) {
        console.error('Error fetching note:', error);
      }
    },
    editNote() {
      this.isEditing = true;
    },
    async saveNote() {
      this.isEditing = false;
      try {
        await this.updateNote();
      } catch (error) {
        console.error('Error updating note:', error);
      }
    },
    async updateNote() {
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      const response = await fetch(`/notes/${this.note.id}`, {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({ content: this.note.content }),
      });
      if (!response.ok) {
        throw new Error('Failed to update note');
      }
    },
    markdownToHtml(markdown) {
      const md = new MarkdownIt();
      return md.render(markdown);
    },
    handleClickOutside(event) {
      if (this.isEditing && !this.$el.contains(event.target)) {
        this.saveNote();
      }
    },
  },
};
</script>

<style scoped>
.btn-view {
  margin-top: 10px;
  padding: 5px 10px;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.btn-view:hover {
  background-color: #45a049;
}
</style>
