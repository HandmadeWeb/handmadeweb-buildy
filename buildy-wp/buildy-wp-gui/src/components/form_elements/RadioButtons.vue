<template>
  <div
    class="attribute-editor module module-settings mt-0 flex items-center"
  >
    <label v-show="!hideLabel" class="pr-2 pb-0 setting-label"
      >{{ label }}:</label
    >
    <div class="radio-buttons">
      <div
        v-for="(option, i) in optionsArr"
        :key="option + i"
        class="radio-button"
      >
        <label :for="`${label}-${option}-${i}`">
          <input
            @click="handleClick(option)"
            @change="handleChange"
            type="radio"
            :id="`${label}-${option}-${i}`"
            v-model="selection"
            :value="option"
          />
          <span>{{ option }}</span>
        </label>
      </div>
    </div>
  </div>
</template>

<script>
// import { EventBus } from '../../../EventBus';
import { getDeep, setDeep } from "../../functions/objectHelpers";
export default {
  props: {
    label: String,
    options: String,
    attribute: String,
    path: String,
    selected: String,
    hideLabel: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      selection: ""
    };
  },
  computed: {
    optionsArr() {
      return this.options.split(",").map(el => el.trim());
    }
  },
  methods: {
    handleClick(option) {
      // If the same one was clicked, set the value back to nothing
      if (this.selection === option) {
        this.selection = "";
        setDeep(this.component, this.path, this.selection);
      }
      this.$emit("change", this.selection);
    },
    handleChange() {
      this.$emit("change", this.selection || null);
      if (this.component && this.path) {
        setDeep(this.component, this.path, `${this.selection}`);
      }
    }
  },
  mounted() {
    if (this.selected) {
      this.selection = this.selected.trim();
    }

    if (!this.selected && this.path) {
      this.selection = getDeep(this.component, this.path) || "";
    }

    if (
      !this.options.includes(this.selection) &&
      this.options.includes("Other") &&
      this.selection !== ""
    ) {
      this.selection = "Other";
    }

    this.$emit("change", this.selection);
  },
  inject: ["component"]
};
</script>

<style lang="scss" scoped>
.radio-button {
  display: inline-block;
}

label {
  padding-bottom: 0;

  span {
    display: flex;
    font-size: 12px;
    align-items: center;
    justify-content: center;
    margin: 0 0.5rem 0 0;
    cursor: pointer;
    border-radius: 5px;
    // box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.33);
    background-repeat: no-repeat;
    background-position: center;
    text-align: center;
    padding: 0.2rem 0.5rem;
    @apply bg-gray-300;
    transition: all 0.2s;
  }
  &:hover span {
    background: #e2e8f0;
  }
  input[type="radio"] {
    display: none;
    &:checked ~ span {
      @apply bg-gray-600 text-white;
    }
  }
}
</style>
