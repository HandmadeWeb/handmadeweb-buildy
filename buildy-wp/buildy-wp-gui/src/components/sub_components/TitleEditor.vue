<template>
  <div>
    <attribute-editor
      :label="label"
      :placeholder="placeholder"
      :path="`${path}.value`"
    />
    <div class="flex">
      <radio-buttons
        label="Level"
        options="H1,H2,H3,H4,H5"
        :class="[colorPicker ? 'flex-grow-1' : '']"
        :path="`${path}.level`"
      />
      <radio-buttons
        v-if="!colorPicker"
        label="Color"
        @change="colorSelect"
        class="ml-auto"
        options="Light,Dark,Other"
        :path="`${path}.color`"
      />
      <div
        class="flex-col"
        :class="[colorPicker ? 'flex-1 pl-6' : 'flex ml-auto']"
      >
        <color-picker v-if="colorPicker" :path="`${path}.color`" />
        <small
          v-if="colorPicker"
          @click="resetColor"
          class="cursor-pointer -mt-2 block"
          >Back to toggles</small
        >
      </div>
    </div>
  </div>
</template>

<script>
import { setDeep } from "../../functions/objectHelpers";
export default {
  data() {
    return {
      colorPicker: false
    };
  },
  props: {
    path: {
      type: String,
      required: true
    },
    label: {
      type: String,
      required: true
    },
    placeholder: {
      type: String
    }
  },
  methods: {
    colorSelect(val) {
      val === "Other" || val.includes("#")
        ? (this.colorPicker = true)
        : (this.colorPicker = false);
    },
    resetColor() {
      setDeep(this.component, `${this.path}.color`, "");
      this.colorPicker = false;
    }
  },
  inject: ["component"]
};
</script>
