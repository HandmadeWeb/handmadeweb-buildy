<template>
  <settings-modal :width="1000">
    <div
      class="form-type absolute mt-2 top-0 right-0 text-right"
      v-if="showSelect"
    >
      <a
        class="flex bg-green-700 rounded-tl-full rounded-bl-full text-white link-existing-trigger items-center gap-2"
        href="#"
        title="Select an already existing module to re-link it to this field"
        @click.prevent="toggleExisting"
      >
        <plus-icon
          v-if="showExisting"
          size="1x"
          class="box-content text-white p-2"
        ></plus-icon>
        <link-icon
          v-else
          size="1x"
          class="box-content text-white p-2"
        ></link-icon>
        <span class="block text-white pr-4">{{ formTypeLabel }}</span>
      </a>
    </div>
    <acf-module-select
      :label="`${showExisting ? 'Existing' : 'Create'} Module`"
      class="items-center"
      :key="showExisting"
      :action="`${showExisting ? 'load' : 'create'}`"
      :endpoint="showExisting ? 'bmcb/v1/acf_posts' : 'bmcb/v1/acf_modules'"
      v-show="showSelect && !isLoading"
    />
    <v-spinner v-if="isLoading" />
    <acf-form />
  </settings-modal>
</template>
<script>
import { EventBus } from "../EventBus";
import VSpinner from "./shared/VSpinner.vue";
import { LinkIcon, PlusIcon } from "vue-feather-icons";

export default {
  components: { VSpinner, LinkIcon, PlusIcon },
  name: "acf-module",
  data: function () {
    return {
      icon: "CodeIcon",
      showExisting: false,
      defaultformType: "Link Existing",
      formTypeLabel: "",
      showSelect: true,
      isLoading: false,
    };
  },
  methods: {
    // Toggle between 'Link Existing' and 'Create New' dropdowns
    toggleExisting() {
      // Clear form HTML in preparation for new data
      EventBus.$emit("clearFormHTML");
      // Toggle the dropdowns to display different data
      this.showExisting = !this.showExisting;
      // Update the button label
      if (this.showExisting) {
        this.formTypeLabel = "Create New";
      } else {
        this.formTypeLabel = this.defaultformType;
      }
    },
  },
  mounted() {
    // Listen for 'isLoading' event and render loading spinner as required
    EventBus.$on("isLoading", (e) => {
      this.isLoading = e;
    });
    // Listen for 'showSelect' event and show/hide the select options as required
    EventBus.$on("showSelect", (e) => {
      this.showSelect = e;
    });
    // Update the button label on load to default value
    this.formTypeLabel = this.defaultformType;
  },
  destroyed() {
    EventBus.$off("isLoading");
    EventBus.$off("showSelect");
  },
  inject: ["component"],
};
</script>

<style lang="scss">
.link-existing-trigger {
  // The second param of calc is the size of the icon
  transform: translateX(calc(100% - 32px));
  transition: transform 0.2s ease-out;
  &:hover {
    transform: translateX(0);
  }
}
</style>
