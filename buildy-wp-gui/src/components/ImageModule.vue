<template>
  <settings-modal>
    <attribute-editor label="Title" path="content.title"></attribute-editor>
    <image-uploader label="Image:" path="content.image"></image-uploader>
    <div slot="options" class="image-custom-options">
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
export default {
  name: "image-module",
  props: {
    component: Object
  },
  data: function() {
    return {
      icon: "ImageIcon"
    };
  }
};
</script>
