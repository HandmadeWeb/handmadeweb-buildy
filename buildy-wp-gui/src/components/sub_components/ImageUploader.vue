<template>
  <div class="image-uploader module module-settings">
    <label class="pb-2" v-if="label" v-text="label" />
    <div
      @click.prevent="openMediaLibrary"
      class="flex w-full relative cursor-pointer items-center justify-center image-selector mb-4"
      :class="[imageSrc ? 'hasImage' : 'empty']"
    >
      <img
        v-if="imageSrc"
        :id="'img-' + component.id"
        class="image-preview"
        :src="imageSrc"
      />
      <trash-2-icon
        v-if="imageSrc"
        @click.stop="removeImage"
        class="text-white absolute delete-image-icon cursor-pointer"
        size="1.5x"
      ></trash-2-icon>
      <span v-if="!imageSrc">Upload Image</span>
    </div>

    <div class="flex -mx-2">
      <attribute-editor
        class="px-2 flex-1"
        v-if="path === 'content.image'"
        label="Width"
        :path="`${path}.width`"
      />
      <attribute-editor
        class="px-2 flex-1"
        v-if="path === 'content.image'"
        label="Max Width"
        :path="`${path}.maxWidth`"
      />
    </div>

    <div v-if="path === 'content.image'" class="flex -mx-2">
      <attribute-editor
        class="px-2 flex-1"
        label="Height"
        :path="`${path}.height`"
      />
      <attribute-editor
        class="px-2 flex-1"
        label="Max Height"
        :path="`${path}.maxHeight`"
      />
    </div>

    <div
      v-if="path === 'content.image' || imageType === 'img'"
      class="flex -mx-2"
    >
      <select-box
        class="px-2 flex-1"
        label="Object Fit"
        :path="`${path}.objectFit`"
        options="auto, contain, cover, 100%"
      />
      <select-box
        class="px-2 flex-1"
        label="Object Position"
        :path="`${path}.objectPosition`"
        options="top, bottom, left, right"
      />
      <select-box
        class="px-2 flex-1"
        label="Image / Title Position"
        :path="`${path}.imageTitlePosition`"
        options="Image Above, Image Below"
      />
      <select-box
        class="px-2 flex-1"
        label="Image Size"
        :path="`${path}.imageSize`"
        defaultVal="full"
        :options="imageSizes"
      />
    </div>
    <div
      v-if="path === 'inline.backgroundImage' || imageType === 'bg'"
      class="flex -mx-2"
    >
      <select-box
        class="px-2 flex-1"
        label="BG Size"
        :path="`${path}.backgroundSize`"
        options="auto, contain, cover, 100%"
      />
      <select-box
        class="px-2 flex-1"
        label="BG Position"
        :path="`${path}.backgroundPosition`"
        options="center, center top, center bottom, center left, center right, top right, top left, bottom left, bottom right"
      />
      <select-box
        class="px-2 flex-1"
        label="BG Blend Mode"
        :path="`${path}.BlendMode`"
        options="normal, multiply, screen, overlay, darken, lighten, color-dodge, saturation, color, luminosity"
      />
    </div>
  </div>
</template>

<script>
import { setDeep, getDeep } from "../../functions/objectHelpers";
import { Trash2Icon } from "vue-feather-icons";
import mediaLibrary from "../../mixins/mediaLibrary";
import { mapGetters } from "vuex";
export default {
  computed: {
    ...mapGetters(["imageSizes"]),
    imageSrc() {
      return getDeep(this.component, `${this.path}.url`);
    },
  },
  mixins: [mediaLibrary],
  methods: {
    addImages(image) {
      setDeep(this.component, `${this.path}.url`, image[0].url);
      setDeep(this.component, `${this.path}.imageID`, image[0].id);
    },
    removeImage() {
      setDeep(this.component, `${this.path}.url`, "");
      setDeep(this.component, `${this.path}.imageID`, "");
    },
  },
  props: {
    bgImage: Boolean,
    path: {
      type: String || Array,
      default: "content.image",
    },
    imageType: String,
    label: String,
  },
  components: {
    Trash2Icon,
  },
  inject: ["component"],
  provide() {
    return {
      component: this.component,
    };
  },
};
</script>

<style>
.image-selector.empty {
  @apply bg-gray-300 h-12 border-2 border-dashed border-gray-600;
  transition: background-color 0.2s;
}

.image-selector.empty:hover {
  @apply bg-gray-200;
}

.image-preview {
  @apply w-full object-cover h-40;
}

.delete-image-icon {
  top: 3px;
  right: 3px;
  background: #00000069;
  padding: 6px;
  height: auto;
  width: 30px;
  opacity: 0;
  transition: opacity 0.2s;
  z-index: 10;
}

.image-selector:hover .delete-image-icon {
  opacity: 1;
}
</style>
