<template>
  <settings-modal>
    <title-editor label="Title" path="content.title" />
    <component :is="editorType" path="content.body"></component>
    <div slot="options" class="text-custom-options">
      <div class="flex">
        <toggle-switch
          label="Show Title Attributes?"
          path="options.titleAtts"
        />
      </div>
      <fieldset
        v-show="component.options.titleAtts"
        class="title-atts border p-4 mt-4"
      >
        <h3 class="text-xl font-bold text-center">Title Attributes</h3>
        <attribute-editor
          label="Title ID"
          path="content.title.id"
        ></attribute-editor>
        <attribute-editor
          label="Title Class"
          path="content.title.class"
        ></attribute-editor>
      </fieldset>
    </div>
  </settings-modal>
</template>
<script>
import { mapGetters } from "vuex";
import { moduleName } from "../functions/helpers";

export default {
  name: "text-module",
  props: {
    component: Object,
  },
  data: function() {
    return {
      icon: "AlignJustifyIcon",
    };
  },
  computed: {
    ...mapGetters(["isWP"]),
    editorType() {
      return this.isWP ? "rich-tiny" : "rich-text";
    },
  },
  methods: {
    moduleName,
  },
};
</script>
