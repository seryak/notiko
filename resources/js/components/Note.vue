<template>
  <div>
    <div v-if="!isEditing" @dblclick="editNote">
      <v-md-preview-html :html="markdownToHtml(note.content)" preview-class="vuepress-markdown-body"></v-md-preview-html>
    </div>
    <v-md-editor v-else v-model="note.content" @blur="saveNote" />
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
import '@kangc/v-md-editor/lib/theme/style/vuepress.css';
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
    };
  },
  methods: {
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
