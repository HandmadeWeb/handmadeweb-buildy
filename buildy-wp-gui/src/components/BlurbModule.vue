<template>
  <settings-modal>
    <title-editor label="Title" path="content.title" />
    <image-uploader label="Blurb Image:" path="content.image"></image-uploader>
    <component :is="editorType" path="content.body"></component>
    <div class="flex -mx-8">
      <toggle-switch
        class="px-8"
        label="Enable Button 1"
        path="options.buttonOneEnabled"
      />
      <toggle-switch
        class="px-2"
        label="Enable Button 2"
        path="options.buttonTwoEnabled"
      />
    </div>
    <transition name="fade">
      <Button
        :label="`${component.id}-1`"
        name="button one"
        path="content.button"
        :key="component.id + component.options.buttonOneEnabled"
        v-show="component.options.buttonOneEnabled"
      />
    </transition>
    <transition name="fade">
      <Button
        :label="`${component.id}-2`"
        name="button two"
        path="content.buttontwo"
        :key="component.id + component.options.buttonTwoEnabled"
        v-show="component.options.buttonTwoEnabled"
      />
    </transition>

    <div slot="options" class="blurb-custom-options">
      <div class="flex">
        <toggle-switch
          label="Show Title Attributes?"
          path="options.titleAtts"
        />
        <toggle-switch
          class="ml-10"
          label="Show Image Attributes?"
          path="options.imageAtts"
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
      <fieldset
        v-show="component.options.imageAtts"
        class="title-atts border p-4 mt-4"
      >
        <h3 class="text-xl font-bold text-center">Image Attributes</h3>
        <attribute-editor
          label="Image ID"
          path="content.image.id"
        ></attribute-editor>
        <attribute-editor
          label="Image Class"
          path="content.image.class"
        ></attribute-editor>
      </fieldset>
    </div>
  </settings-modal>
</template>
<script>
import { mapGetters } from "vuex";

export default {
  name: "blurb-module",
  data: function() {
    return {
      icon: "CoffeeIcon",
      keepInBounds: true,
      type: "blurb-module",
    };
  },
  computed: {
    ...mapGetters(["isWP"]),
    editorType() {
      return this.isWP ? "rich-tiny" : "rich-text";
    },
  },
  props: {
    hidecontrols: Boolean,
    component: Object,
  },
};
</script>
