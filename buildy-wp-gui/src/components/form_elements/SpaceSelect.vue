<template>
  <div class="select-box-module module-settings mt-0 flex flex-col relative">
    <div
      v-if="preview.show"
      ref="previewWindow"
      class="previewWindow relative z-50 flex"
      :class="[labelClass === 'bottom' ? 'flex-col' : '']"
      :style="
        preview.width > 0
          ? `transform: translateX(-${preview.width / 2}px)`
          : null
      "
    >
      <span
        class="line-1"
        :class="label"
        :style="`margin-${labelClass}:${preview.value}rem`"
      ></span>
      <span class="line-2" :class="label"></span>
    </div>
    <label class="pr-4 pb-0">{{ label }}:</label>
    <div @click="toggleMenu" class="wrap-drop" :class="active ? 'active' : ''">
      <span>{{ value }}</span>
      <ul class="drop" @mouseleave="active = false">
        <li @click="handleChange('None')" class="p-2 text-center">None</li>
        <li
          v-for="(option, i) in optionsArr"
          @click="handleChange(option)"
          @mouseover="previewSpacing(), updatePreview(option)"
          @mouseout="previewSpacing()"
          :key="option + i"
          class="select-choice text-center"
          :class="[option === value ? 'selected' : '']"
        >
          <a>{{ option }}</a>
        </li>
      </ul>
    </div>
    <!-- <select @mouseover="previewSpacing" @mouseleave="previewSpacing" class="select-box rounded p-2" v-model="value">
            <option value="None">None</option>
            <option v-for="(option, i) in optionsArr" :key="option+i" class="select-choice flex" :value="option+1">{{ option+1 }}</option>
        </select> -->
  </div>
</template>

<script>
// import { EventBus } from '../../../EventBus';
import { getDeep, setDeep } from "../../functions/objectHelpers";
import { EventBus } from "../../EventBus";
export default {
  props: {
    label: String,
    range: Number,
    options: String,
    path: String,
    selected: String
  },
  data() {
    return {
      value: "None",
      preview: {
        show: false,
        width: "",
        value: "",
        timeout: null
      },
      active: false
    };
  },
  computed: {
    optionsArr() {
      let range = this.$store.getters.spacers || 13;
      return Array.isArray(range) ? range : Array.from(Array(range).keys());
    },
    previewClass() {
      return this.path.includes("margin") ? "margin" : "padding";
    },
    labelClass() {
      if (this.label === "top" || this.label === "bottom") {
        return "bottom";
      } else if (this.label === "left" || this.label === "right") {
        return "right";
      }
      return this.label;
    }
  },
  methods: {
    handleChange(option) {
      this.value = option;
      this.$emit("change", option || null);
      if (this.component && this.path) {
        if (
          typeof this.value === "string" &&
          this.value.toLowerCase() === "none"
        ) {
          setDeep(this.component, this.path, "");
        } else {
          setDeep(this.component, this.path, option);
        }
      }
    },
    toggleMenu(e) {
      e.preventDefault();
      e.stopPropagation();
      this.active = !this.active;
    },
    previewSpacing() {
      if (this.path.includes("margin") || this.path.includes("padding")) {
        this.preview.show = !this.preview.show;
      }
    },
    updatePreview(option) {
      this.preview.value = option;
      this.$nextTick(() => {
        this.preview.width = this.$refs.previewWindow.clientWidth;
      });
    }
  },
  mounted() {
    if (this.selected) {
      this.value = this.selected.trim();
    }

    EventBus.$on("modalClick", () => {
      this.active = false;
    });

    if (!this.selected && this.path) {
      let value = getDeep(this.component, this.path);

      if (!value && value !== 0) {
        return (this.value = "None");
      }

      this.value = value;
    }

    this.$emit("change", this.value);
  },
  inject: ["component"]
};
</script>

<style lang="scss" scoped>
.select-box-module,
.wrap-drop {
  width: 100%;
}

.wrap-drop {
  background: #edf0f5;
  border-radius: 0.3rem;
  cursor: pointer;
  margin: 0 auto;
  flex-grow: 1;
  padding: 0.5rem;
  position: relative;
}

.wrap-drop::after {
  border-color: #695d52 transparent;
  border-style: solid;
  border-width: 5px 5px 0;
  content: "";
  height: 0;
  margin-top: -4px;
  position: absolute;
  right: 1rem;
  top: 50%;
  width: 0;
}

.wrap-drop .drop {
  background: #e9e9e9;
  box-shadow: 3px 3px 3px rgba(0, 0, 0, 0.2);
  display: none;
  left: 0;
  list-style: none;
  margin-top: 0;
  opacity: 0;
  padding-left: 0;
  pointer-events: none;
  position: absolute;
  right: 0;
  top: 100%;
  z-index: 2;
}

.wrap-drop .drop li a {
  color: #695d52;
  display: block;
  padding: 0.5rem;
  text-decoration: none;
}

.wrap-drop span {
  color: #928579;
}

.wrap-drop .drop li:hover a {
  background-color: #695d52;
  color: #e7ded5;
}

.wrap-drop.active::after {
  border-width: 0 10px 10px;
}

.wrap-drop.active .drop {
  display: block;
  opacity: 1;
  pointer-events: auto;
}

.previewWindow {
  background: white;
  padding: 2rem;
  box-shadow: 0 0 10px 5px rgba(0, 0, 0, 0.2);
  position: absolute;
  bottom: 100%;
  left: 50%;
}

.line-1,
.line-2 {
  @apply bg-red-500;
  display: block;
  &.top,
  &.bottom {
    width: 60px;
    height: 6px;
  }

  &.left,
  &.right {
    width: 6px;
    height: 60px;
  }
}
</style>
