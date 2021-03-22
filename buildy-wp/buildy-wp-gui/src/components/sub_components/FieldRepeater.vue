<template>
  <div class="field-repeater module module-settings mt-0 flex flex-col">
    <div
      v-for="(att, index) in value"
      :key="`${component.id}-${att.name}-${index}`"
      class="field-repeater__field flex flex-col"
    >
      <label v-if="label === 'data-'" class="pr-4 setting-label">
        {{ formatLabel(att) }}:
      </label>
      <div class="flex -mx-2 items-center justify-between">
        <div class="w-1/2 px-2">
          <input
            class="bg-gray-300 p-2 w-full"
            @blur="handleChange"
            v-model.lazy="value[index].name"
            placeholder="Name"
          />
        </div>
        <div class="w-1/2 px-2" v-if="isInBrackets(value[index].value)">
          <select-box
            class="px-2 flex-1"
            label="Choose"
            :defaultVal="value[index].selected"
            @change="handleChange"
            :path="`${path}.${index}.selected`"
            :options="value[index].value"
          />
        </div>
        <div class="w-1/2 px-2" v-else>
          <input
            class="bg-gray-300 p-2 w-full"
            @blur="handleChange"
            v-model.lazy="value[index].value"
            placeholder="Value"
          />
        </div>
      </div>
    </div>
    <a
      class="text-gray-800 self-end py-2"
      @click.prevent="addDataAtt"
      href="#"
      >{{ label === "data-" ? "Add data attributes" : "Add fields" }}</a
    >
  </div>
</template>

<script>
import { EventBus } from "../../EventBus";
import { setDeep, getDeep } from "../../functions/objectHelpers";
import { stripTrailingSlash } from "../../functions/helpers";

export default {
  name: "field-repeater",
  data: function () {
    return {
      value: [],
    };
  },
  methods: {
    handleChange() {
      // Helper function to set values at any depth and bore a path to them if undefined
      setDeep(this.component, this.path, this.value, true);

      // Emit some events that could be useful
      this.$emit("change", { data: this.value, path: this.path });
    },
    addDataAtt() {
      let obj = {
        name: "",
        value: "",
      };
      this.value.push(obj);
    },
    formatLabel(el) {
      let label;
      if (this.label.includes("-")) {
        label = this.label + (el.name || "");
      } else {
        label = this.label;
      }
      return label;
    },
    async fetchOptions() {
      if (window.global_vars) {
        let res = await fetch(
          `${stripTrailingSlash(window.global_vars.rest_api_base)}/${
            this.endpoint
          }`
        );
        let data = await res.json();

        console.log({ data });

        if (data.body && typeof data.body === "object") {
          let res = data.body.find((el) => el.style_name === this.moduleStyle);
          if (res) {
            this.value = res.fields;
          }
        }
      }
    },
    isInBrackets(str) {
      return /\[(.*?)\]/g.test(str);
    },
  },
  computed: {
    sibblingLink() {
      return this.component.sibblingLink || false;
    },
    moduleStyle() {
      return this.component.options.moduleStyle;
    },
  },
  mounted() {
    EventBus.$on("saveAll", () => {
      this.handleChange();
    });

    console.log("path", this.path);

    // Get any current results
    this.value = getDeep(this.component, this.path) || this.value;

    console.log("Value", this.value);

    console.log("Style", this.moduleStyle);

    if (this.endpoint && !this.value.length) {
      this.fetchOptions();
    }

    // If we do have more than one, make sure none are empty
    if (this.value.length) {
      // Filter out any completely empty ones
      this.value = this.value.filter((value) => value.name || value.value);
      // Save / Cleanup the JSON (removing any empties)
      setDeep(this.component, this.path, this.value, true);
    }
  },
  props: {
    label: String,
    path: String,
    endpoint: String,
  },
  inject: ["component"],
};
</script>

<style lang="scss">
.field-repeater .field-repeater__field + .field-repeater__field {
  margin-top: 1rem;
}
</style>
