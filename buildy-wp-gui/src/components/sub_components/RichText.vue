<template>
  <div class="module module-settings">
    <span class="text-2xl block mb-4" v-if="header" v-text="header" />
    <a
      v-if="customMediaLibrary"
      class="border-b-2 border-gray-500 mb-2"
      @click.prevent="openMediaLibrary"
      href="#"
      >Media Library</a
    >
    <vue-editor
      class="bg-gray-200"
      ref="editorEl"
      v-model="value"
      @selection-change="updateCursorLocation"
      @text-change="change"
      :editorToolbar="customToolbar"
    ></vue-editor>
  </div>
</template>
<script>
// @ts-ignore
import { VueEditor } from "vue2-editor";
import mediaLibrary from "../../mixins/mediaLibrary";
import { setDeep, getDeep } from "../../functions/objectHelpers";

export default {
  name: "rich-text",
  components: {
    VueEditor,
  },
  data: function () {
    return {
      cursorLocation: Number,
      editClassOpen: false,
      keepInBounds: true,
      value: "",
      isWordpress: window.wp,
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
  },
  mixins: [mediaLibrary],
  props: {
    header: String,
    path: String,
    imagePath: {
      type: String,
      default: "content.imageSrc",
    },
    media: {
      type: Boolean,
      default: true,
    },
  },
  methods: {
    updateCursorLocation(index) {
      if (index) {
        this.cursorLocation = index;
      }
    },
    change() {
      setDeep(this.component, this.path, this.value);
    },
  },
  mounted() {
    this.value = getDeep(this.component, this.path);
    if (this.customMediaLibrary) {
      this.customMediaLibrary.on("select", () => {
        var selectedImages = this.customMediaLibrary.state().get("selection");
        selectedImages.map((attachment) => {
          attachment = attachment.toJSON();
          this.editor.insertEmbed(this.cursorLocation, "image", attachment.url);
          setDeep(this.component, this.imagePath, attachment.url);
        });
      });
    }
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
