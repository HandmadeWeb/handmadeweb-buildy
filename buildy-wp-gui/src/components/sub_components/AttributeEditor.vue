<template>
  <div class="attribute-editor module module-settings mt-0 flex flex-col">
    <div class="flex flex-col">
      <label v-if="label" :for="component.id + path" class="pr-2 setting-label"
        >{{ formatLabel }}:</label
      >
      <input
        :id="component.id + path"
        @change="handleChange"
        class="w-full p-2"
        type="text"
        :placeholder="placeHolder"
        v-model="value"
      />
    </div>
    <a
      class="text-gray-800 self-end py-2"
      v-if="hasSibblingLink"
      @click.prevent="sibblingLink"
      href="#"
      >Apply {{ label }} to siblings</a
    >
  </div>
</template>

<script>
import { setDeep, getDeep } from "../../functions/objectHelpers";
import { UCFirst } from "../../functions/helpers";
export default {
  name: "attribute-editor",
  data: function() {
    return {
      value: ""
    };
  },
  methods: {
    handleChange() {
      // Helper function to set values at any depth and bore a path to them if undefined
      setDeep(this.component, this.path, this.value, true);

      // Emit some events that could be useful
      this.$emit("change", { data: this.value, path: this.path });
    }
    // sibblingLink() {
    //   this.parent_array.forEach(child => {
    //     setDeep(child, this.path, this.value)
    //   });
    // getLocalState() {
    //   return Object.keys(this.$data).forEach(key => {
    //     (this.component && this.component[key]) ? this.$data[key] = this.component[key] : null
    //   })
    // }
  },
  computed: {
    hasSibblingLink() {
      return this.component.sibblingLink || false;
    },
    formatLabel() {
      return UCFirst(this.label);
    },
    placeHolder() {
      return this.placeholder || this.formatLabel;
    }
  },
  mounted() {
    this.value = getDeep(this.component, this.path);
  },
  props: {
    label: String,
    placeholder: String,
    path: String
  },
  inject: ["component"]
};
</script>
