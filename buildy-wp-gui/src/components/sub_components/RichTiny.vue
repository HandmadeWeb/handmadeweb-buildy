<template>
  <div class="module module-settings">
    <span class="text-2xl block mb-4" v-if="header" v-text="header" />
    <textarea :id="editorID" cols="30" rows="10" v-model="value"></textarea>
  </div>
</template>
<script>
// @ts-ignore
// import { VueEditor } from "vue2-editor";
import { setDeep, getDeep } from "../../functions/objectHelpers";

export default {
  name: "rich-text",
  components: {
    // VueEditor
  },
  data: function () {
    return {
      cursorLocation: Number,
      editClassOpen: false,
      keepInBounds: true,
      activeEditor: false,
      value: "",
      customToolbar: [
        [{ header: [1, 2, 3, 4, 5, 6, false] }],
        ["bold", "italic", "underline"],
        [{ list: "ordered" }, { list: "bullet" }],
        ["code-block"],
      ],
    };
  },
  computed: {
    editor() {
      return this.$refs.editorEl.quill;
    },
    editorID() {
      return this.index !== undefined && this.index !== null
        ? `${this.component.id}_${this.index}_tiny_mce`
        : `${this.component.id}_tiny_mce`;
    },
    wp() {
      return window.wp || false;
    },
    wpEditor() {
      return this.isWordpress && window.wp.editor;
    },
  },
  props: {
    header: String,
    path: String,
    imagePath: {
      type: String,
      default: "content.imageSrc",
    },
    index: {
      type: Number,
    },
    media: {
      type: Boolean,
      default: true,
    },
  },
  methods: {
    updateCursorLocation({ index }) {
      if (index) {
        this.cursorLocation = index;
      }
    },
    editorInit() {
      if (this.wp) {
        this.wp.oldEditor.initialize(this.editorID, {
          tinymce: {
            wpautop: true,
            theme: "modern",
            skin: "lightgray",
            language: "en",
            formats: {
              alignleft: [
                {
                  selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li",
                  styles: { textAlign: "left" },
                },
                {
                  selector: "img,table,dl.wp-caption",
                  classes: "alignleft",
                },
              ],
              aligncenter: [
                {
                  selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li",
                  styles: { textAlign: "center" },
                },
                {
                  selector: "img,table,dl.wp-caption",
                  classes: "aligncenter",
                },
              ],
              alignright: [
                {
                  selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li",
                  styles: { textAlign: "right" },
                },
                {
                  selector: "img,table,dl.wp-caption",
                  classes: "alignright",
                },
              ],
              strikethrough: { inline: "del" },
            },
            relative_urls: false,
            remove_script_host: false,
            convert_urls: false,
            browser_spellcheck: true,
            fix_list_elements: true,
            entities: "38,amp,60,lt,62,gt",
            entity_encoding: "raw",
            keep_styles: false,
            paste_webkit_styles: "font-weight font-style color",
            preview_styles:
              "font-family font-size font-weight font-style text-decoration text-transform",
            tabfocus_elements: ":prev,:next",
            plugins:
              "charmap,hr,media,paste,tabfocus,lists,textcolor,fullscreen,wordpress,wpeditimage,wpgallery,wplink,wpdialogs,wpview",
            resize: "vertical",
            menubar: false,
            indent: false,
            toolbar1:
              "bold,italic,strikethrough,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,wp_more,spellchecker,fullscreen,wp_adv",
            toolbar2:
              "formatselect,underline,alignjustify,forecolor,pastetext,removeformat,charmap,,undo,redo,wp_help",
            toolbar3: "",
            toolbar4: "",
            body_class:
              "id post-type-post post-status-publish post-format-standard",
            wpeditimage_disable_captions: false,
            wpeditimage_html5_captions: true,
          },
          mediaButtons: true,
          quicktags: true,
        });

        window.tinymce.activeEditor.on("change", (e) => {
          this.value = e.level.content;
        });

        // window.tinymce.activeEditor.on("PreProcess", (e) => {
        //   alert(e);
        // });

        this.activeEditor = true;
      }
    },
  },
  watch: {
    value: function () {
      setDeep(this.component, this.path, this.value);
    },
  },
  async mounted() {
    this.value = getDeep(this.component, this.path) || "";
    this.$nextTick(() => {
      this.editorInit();
    });
  },
  beforeDestroy() {
    this.wp.oldEditor.remove(this.editorID);
  },
  inject: ["component"],
};
</script>

<style lang="scss">
.editor__content {
  max-height: 300px;
  overflow-y: scroll;
  overflow-x: hidden;
}

#buildy-root .ql-editor p {
  margin-bottom: 15px;
}
</style>
