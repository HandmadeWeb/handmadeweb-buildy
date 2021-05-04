<template>
  <div class="attribute-editor module module-settings mt-0 flex items-center">
    <label
      :for="labelID"
      class="pr-4 setting-label setting-label--inline flex-shrink-0"
    >
      {{ label }}:
    </label>

    <label class="ui-switch" :class="{ checked: value }">
      <input
        @change="handleChange"
        :id="labelID"
        type="checkbox"
        class="ui-switch-input"
        v-model="value"
      />

      <div class="ui-switch-track"></div>
      <div class="ui-switch-thumb"></div>

      <div class="ui-switch-focus-ring"></div>
    </label>
  </div>
</template>

<script>
import { setDeep, getDeep } from "../../functions/objectHelpers";
import { spaceToDash } from "../../functions/helpers";
import { generateID } from "../../functions/idHelpers";

export default {
  name: "toggle-switch",
  props: {
    status: Boolean,
    label: String,
    path: String,
    attribute: String,
  },
  data() {
    return {
      value: false,
    };
  },
  computed: {
    labelID() {
      return `toggle-${spaceToDash(this.label)}-${generateID()}`;
    },
  },
  methods: {
    handleChange() {
      this.$emit("toggle", this.value);
      if (this.path && this.component) {
        setDeep(this.component, this.path, this.value, true);
      }
    },
  },
  mounted() {
    if (this.status) {
      this.value = this.status;
    }
    if (this.path && this.component) {
      let findVal = getDeep(this.component, this.path);

      if (typeof findVal !== "undefined") {
        this.value = getDeep(this.component, this.path);
      }
    }
  },
  inject: ["component"],
};
</script>

<style lang="scss">
$primary: #2196f3;
$thumb-size: 20px;
$thumb-bg-color: #fafafa;
$track-width: 34px;
$track-height: 14px;
$focus-ring-size: 42px;
$focus-ring-left: -(($focus-ring-size - $thumb-size) / 2);
$focus-ring-top: -(($focus-ring-size - $thumb-size) / 2);

.ui-switch {
  cursor: pointer;
  position: relative;
  top: 4px;

  &.checked {
    .ui-switch-track {
      background-color: rgba($primary, 0.5);
    }

    .ui-switch-thumb {
      left: 14px;
      background-color: $primary;
    }

    .ui-switch-focus-ring {
      left: $focus-ring-left + 14px;
      background-color: rgba($primary, 0.12);
    }
  }
}

.ui-switch-track {
  position: absolute;
  top: (($thumb-size - $track-height) / 2);

  height: $track-height;
  width: $track-width;

  background-color: rgba(black, 0.26);
  border-radius: 8px;

  transition: background-color 0.1s linear;
}

.ui-switch-thumb {
  position: absolute;
  top: 0;
  left: 0;

  width: $thumb-size;
  height: $thumb-size;

  border-radius: 50%;
  background-color: $thumb-bg-color;

  box-shadow: 0 1px 3px rgba(black, 0.4);

  transition: all 0.2s ease;
}

.ui-switch-focus-ring {
  position: absolute;
  top: $focus-ring-top;
  left: $focus-ring-left;
  z-index: -1;

  border-radius: 50%;
  width: $focus-ring-size;
  height: $focus-ring-size;

  background-color: rgba(black, 0.1);
  transform: scale(0);
  opacity: 0;

  transition: all 0.2s ease;
}

.ui-switch-input {
  opacity: 0;

  &:focus ~ .ui-switch-focus-ring {
    transform: scale(1);
    opacity: 1;
  }
}
</style>
