<template>
  <settings-modal>
    <title-editor label="Title" path="content.title"></title-editor>
    <div class="gallery-wrapper">
      <ul class="gallery-module">
        <draggable :list="images" v-bind="dragOptions">
          <li
            @click.prevent="openMediaLibrary"
            v-for="image in images"
            :key="image.id"
            class="gallery-module__item"
          >
            <trash-2-icon
              @click.stop="removeImage(image.id)"
              class="text-white absolute delete-image-icon cursor-pointer"
              size="1.5x"
            ></trash-2-icon>
            <img :src="image.url" />
          </li>
          <li class="gallery-module__item">
            <div class="image-uploader h-full module module-settings">
              <div
                @click.prevent="openMediaLibrary"
                class="flex w-full h-full border-2 border-dashed border-gray-600 relative cursor-pointer items-center justify-center image-selector"
              >
                <span
                  ><label
                    class="pb-2"
                    v-text="images.length ? 'Add More' : 'Add Images'"
                  />
                  +</span
                >
              </div>
            </div>
          </li>
        </draggable>
      </ul>
    </div>

    <div class="flex -mx-2">
      <attribute-editor
        class="px-2 flex-1"
        label="Gallery Columns"
        path="content.gallery.columnCount"
      />
      <attribute-editor
        class="px-2 flex-1"
        label="Column Gap"
        path="content.gallery.columnGap"
      />
    </div>

    <div class="flex mb-3">
      <select-box
        class="mb-2 flex-1"
        label="Image Size"
        path="content.gallery.imageSize"
        defaultVal="full"
        :options="imageSizes"
      />
    </div>

    <div class="flex flex-col">
      <div class="flex-1">
        <toggle-switch
          v-if="!isMasonry"
          label="Convert to slider?"
          path="content.gallery.isSlider"
        />
      </div>
      <div class="flex-1">
        <toggle-switch
          v-if="!isSlider"
          label="Enable Masonry Layout?"
          path="content.gallery.isMasonry"
        />
      </div>
    </div>

    <div class="flex flex-col" v-if="isSlider">
      <attribute-editor
        label="Interval"
        placeholder="Time between slide changes - Default: 5000"
        path="options.slider.interval"
      />
      <attribute-editor
        label="Duration"
        placeholder="Duration of the slide animation - Default: 200"
        path="options.slider.duration"
      />
      <attribute-editor
        label="Easing"
        placeholder="Default: ease-out"
        path="options.slider.easing"
      />
      <attribute-editor
        label="Per Page"
        placeholder="Default: 1"
        path="options.slider.perPage"
      />
      <attribute-editor
        label="Start Index"
        placeholder="Default: 0"
        path="options.slider.startIndex"
      />
      <attribute-editor
        label="Threshold"
        placeholder="Default: 20"
        path="options.slider.threshold"
      />
      <toggle-switch
        label="Autoplay"
        :status="
          component.options.slider &&
          typeof component.options.slider.autoplay !== 'undefined'
            ? component.options.slider.autoplay
            : true
        "
        path="options.slider.autoplay"
      ></toggle-switch>
      <toggle-switch
        label="Draggable"
        :status="
          component.options.slider &&
          typeof component.options.slider.draggable !== 'undefined'
            ? component.options.slider.draggable
            : true
        "
        path="options.slider.draggable"
      ></toggle-switch>
      <toggle-switch
        label="Loop"
        :status="
          component.options.slider &&
          typeof component.options.slider.loop !== 'undefined'
            ? component.options.slider.loop
            : true
        "
        path="options.slider.loop"
      ></toggle-switch>
      <toggle-switch
        label="Right to left?"
        :status="component.options.slider && component.options.slider.rtl"
        path="options.slider.rtl"
      ></toggle-switch>
      <toggle-switch
        label="Arrow Navigation"
        :status="
          component.options.slider &&
          typeof component.options.slider.arrow_nav !== 'undefined'
            ? component.options.slider.arrow_nav
            : true
        "
        path="options.slider.arrow_nav"
      ></toggle-switch>
      <toggle-switch
        label="Dot Navigation"
        :status="
          component.options.slider &&
          typeof component.options.slider.paginationDots !== 'undefined'
            ? component.options.slider.paginationDots
            : true
        "
        path="options.slider.paginationDots"
      ></toggle-switch>
    </div>

    <div class="flex flex-col" v-if="isMasonry">
      <div class="flex -px-2">
        <attribute-editor
          class="mx-2"
          label="Margin X"
          placeholder="Spacing X"
          path="options.masonry.marginX"
        />
        <attribute-editor
          class="mx-2"
          label="Margin Y"
          placeholder="Spacing Y"
          path="options.masonry.marginY"
        />
      </div>
    </div>

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
import { mapGetters } from "vuex";
import draggable from "vuedraggable";
import mediaLibrary from "../mixins/mediaLibrary";
import { Trash2Icon } from "vue-feather-icons";
import { setDeep, getDeep } from "../functions/objectHelpers";

export default {
  name: "gallery-module",
  props: {
    component: Object,
  },
  computed: {
    ...mapGetters(["dragDisabled", "imageSizes"]),
    dragOptions() {
      return {
        group: "gallery-images",
        ghostClass: "ghost",
      };
    },
    isSlider() {
      return this.component.content.gallery?.isSlider;
    },
    isMasonry() {
      return this.component.content.gallery?.isMasonry;
    },
  },
  mixins: [mediaLibrary],
  data: function() {
    return {
      icon: "ImageIcon",
      images: [],
    };
  },
  components: {
    draggable,
    Trash2Icon,
  },
  methods: {
    addImages(images) {
      if (images) {
        this.images = images;
        setDeep(this.component, "content.gallery.images", this.images);
      }
    },
    removeImage(id) {
      this.images = this.images.filter((el) => el.id !== id);
      setDeep(this.component, "content.gallery.images", this.images);
    },
  },
  mounted() {
    this.images = getDeep(this.component, "content.gallery.images") || [];
  },
};
</script>
<style scoped lang="scss">
// Gallery module styles
.gallery-wrapper {
  max-height: 400px;
  overflow-y: auto;
  margin-bottom: 1rem;
}
.gallery-module {
  padding: 1rem;
  background: #e2e8f0;
  > div {
    display: grid;
    gap: 1rem;
    grid-template-columns: repeat(3, 1fr);
    grid-auto-rows: 100px;
  }
  &__item {
    position: relative;
    cursor: pointer;
    img {
      object-fit: cover;
      width: 100%;
      height: 100%;
    }
  }
  .delete-image-icon {
    opacity: 1;
  }
}
</style>
