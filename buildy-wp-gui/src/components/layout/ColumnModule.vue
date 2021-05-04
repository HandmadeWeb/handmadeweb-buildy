<template>
  <div
    @mouseover="hovering = true"
    @mouseleave="hovering = false"
    class="col px-4 pb-2 flex-col text-center column"
    :class="[columnClasses, moduleCount ? 'hasItems' : 'empty']"
  >
    <settings-modal
      :component="component"
      :customSlots="['Column Breakpoints']"
    >
      <div
        slot="Column Breakpoints"
        v-for="(number, size) in breakpoints"
        :key="component.id + size + number"
      >
        <div class="mt-0 flex items-center">
          <label class="pr-4 w-24">{{ `col-${size}` }}:</label>
          <input
            :id="component.id + size + number"
            class="text-gray-800 w-full border-2 p-2"
            :value="number"
            type="text"
            @blur="
              editColumnSize({
                size,
                val: $event.target.value,
              })
            "
          />
        </div>
      </div>
    </settings-modal>

    <div class="relative flex-1 h-full">
      <draggable class="w-full" :list="dragArray" v-bind="dragOptions">
        <transition-group name="fadeHeight" tag="div" class="col-inner flex-1">
          <module-base
            v-for="(bmcbModule, index) in dragArray"
            :class="[
              moduleCount > 1 && index !== moduleCount - 1 ? 'mb-2' : 'mb-0',
            ]"
            :column="component"
            :parent_array="component.content"
            :component="bmcbModule"
            :key="bmcbModule.id"
          ></module-base>
        </transition-group>
      </draggable>

      <transition name="fade">
        <div
          v-show="hovering || !moduleCount"
          class="col-options w-full absolute flex p-1 bg-gray-200 text-dark flex-1"
        >
          <div class="absolute left-1">
            <chevron-left-icon
              @click.prevent="decrementCol"
              class="cursor-pointer"
              size="1.5x"
            ></chevron-left-icon>
          </div>
          <div
            class="flex flex-1 justify-center text-center items-center flex-1"
          >
            <menu-icon
              @click="$modal.show(component.id)"
              class="cursor-pointer mr-2"
              size="1.5x"
            ></menu-icon>
            <a @click.prevent="openModal" href="#"
              ><plus-circle-icon
                v-show="showAddIcon"
                class="add-component w-full cursor-pointer mr-1"
                size="1.5x"
              ></plus-circle-icon
            ></a>
          </div>
          <div class="absolute is-right">
            <chevron-right-icon
              @click.prevent="incrementCol"
              class="cursor-pointer"
              size="1.5x"
            ></chevron-right-icon>
          </div>
        </div>
      </transition>
    </div>

    <modal :name="'column-' + component.id" :height="'auto'">
      <x-icon
        @click="closeModal"
        class="text-gray-800 cursor-pointer inset-y-0 m-2 absolute right-0"
        size="1.5x"
      ></x-icon>
      <div class="w-full bg-gray-400 px-12 py-6">
        <h2 class="mb-6 text-2xl">Choose Module:</h2>
        <div class="flex flex-wrap flex-grow -mx-4">
          <div
            v-for="options in validComponents"
            :key="options.type"
            class="flex items-center text-center w-1/4 p-2"
          >
            <label
              :for="options.type"
              class="flex items-center w-full py-2 justify-center cursor-pointer text-lg bg-gray-200 rounded border border-grey"
            >
              <input
                :id="options.type"
                @change="addModuleClick(options)"
                :value="options.type"
                type="radio"
                class="hidden"
              />
              <span
                class="inline-block flex-no-shrink flex flex-col items-center justify-center"
              >
                <component :is="options.icon"></component>
                {{ options.name }}
              </span>
            </label>
          </div>
        </div>
      </div>
      <select
        v-if="menuOpen"
        @change="addModuleClick(component)"
        v-model="newComponent.type"
        class="custom-select custom-select-lg mb-3"
      >
        <option
          v-for="options in validComponents"
          :key="options.type"
          :value="options.type"
        >
          {{ options.name }}
        </option>
      </select>
    </modal>
  </div>
</template>
<script>
import { EventBus } from "../../EventBus";
import { mapGetters } from "vuex";
import {
  BookIcon,
  MenuIcon,
  MinusIcon,
  MapIcon,
  PlusCircleIcon,
  Trash2Icon,
  CopyIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
  XIcon,
  AlignJustifyIcon,
  CoffeeIcon,
  PlayCircleIcon,
  ImageIcon,
  CodeIcon,
  SlidersIcon,
  ArrowRightIcon,
  BoldIcon,
  GridIcon,
} from "vue-feather-icons";

import { setDeep, getDeep } from "../../functions/objectHelpers";
import { debounce } from "../../functions/helpers";
import draggable from "vuedraggable";
import { Module } from "../../classes/ModuleClass";

export default {
  name: "column-module",
  components: {
    draggable,
    BookIcon,
    MenuIcon,
    MinusIcon,
    MapIcon,
    PlusCircleIcon,
    Trash2Icon,
    CopyIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
    XIcon,
    AlignJustifyIcon,
    CoffeeIcon,
    PlayCircleIcon,
    ImageIcon,
    CodeIcon,
    SlidersIcon,
    ArrowRightIcon,
    BoldIcon,
    GridIcon,
  },
  data: function () {
    return {
      dragArray: this.component.content,
      hovering: false,
      bootcols: "",
      windowWidth: Number,
      breakpoints: getDeep(this.component, "options.columns"),
      menuOpen: false,
      dragging: false,
    };
  },
  computed: {
    ...mapGetters(["dragDisabled", "validComponents"]),
    selfID() {
      return this.component.id;
    },
    dragOptions() {
      return {
        group: "components",
        ghostClass: "ghost",
        disabled: this.dragDisabled,
      };
    },
    curBreakpoint() {
      if (this.windowWidth < 480) {
        return "xs";
      } else if (this.windowWidth >= 480 && this.windowWidth < 768) {
        return "sm";
      } else if (this.windowWidth >= 768 && this.windowWidth < 980) {
        return "md";
      } else if (this.windowWidth >= 980 && this.windowWidth < 1170) {
        return "lg";
      } else {
        return "xl";
      }
    },
    moduleCount() {
      return this.dragArray.length;
    },
    showAddIcon() {
      return (
        (!this.menuOpen && this.hovering) ||
        (!this.emptyCheck && !this.menuOpen)
      );
    },
    columnClasses() {
      const breakpoints = this.component.options.columns;
      // const col = parseInt(this.component.options.columns.md)
      const classes = [];

      for (let breakpoint in breakpoints) {
        if (breakpoints[breakpoint]) {
          classes.push(`col-${breakpoint}-${String(breakpoints[breakpoint])}`);
        }
        // If xs isn't set, give it a 12-col layout (in-builder-only) to it never looks broken.
        if (breakpoint === "xs" && !breakpoints[breakpoint]) {
          classes.push("col-xs-12");
        }
      }

      return classes.join(" ");
    },
  },
  methods: {
    openModal() {
      this.$modal.show("column-" + this.component.id);
    },
    closeModal() {
      this.$modal.hide("column-" + this.component.id);
    },
    incrementCol() {
      let curVal =
        parseInt(this.component.options.columns[this.curBreakpoint]) ||
        parseInt(this.component.options.columns.lg) ||
        12;
      if (curVal < 12) {
        setDeep(
          this.component,
          `options.columns.${this.curBreakpoint}`,
          curVal + 1
        );
      }
    },
    decrementCol() {
      let curVal =
        parseInt(this.component.options.columns[this.curBreakpoint]) ||
        parseInt(this.component.options.columns.lg) ||
        12;
      if (curVal > 1) {
        setDeep(
          this.component,
          `options.columns.${this.curBreakpoint}`,
          curVal - 1
        );
      }
    },
    editColumnSize({ size, val = "" }) {
      if (val !== "") {
        val = parseInt(val);
        val < 1 ? (val = "") : val > 12 ? (val = 12) : val;
      }
      setDeep(this.component, `options.columns.${size}`, val);
    },
    async addModuleClick(options) {
      // const type = options.type
      let newObj = new Module(options);

      let newComponent;

      if (!options.atts) {
        newComponent = newObj.newModule();
      } else {
        newComponent = newObj.customAtts(options.atts);
      }

      this.component.content.push(newComponent);

      this.closeModal();
      this.menuOpen = false;
      return newComponent;
    },
  },
  props: {
    columns: Number,
    component: Object,
    parent_id: String,
  },
  provide() {
    return {
      component: this.component,
      column: this.component,
    };
  },
  async mounted() {
    EventBus.$on("dragToggle", (val) => {
      this.dragging = val;
    });

    this.$nextTick(() => {
      const vm = this;

      vm.windowWidth = window.outerWidth;

      window.addEventListener(
        "resize",
        debounce(function (e) {
          vm.windowWidth = e.target.outerWidth;
        }, 100)
      );
    });
  },
  beforeDestroy() {
    const vm = this;
    window.removeEventListener(
      "resize",
      debounce(function (e) {
        vm.windowWidth = e.target.outerWidth;
      }, 100)
    );
  },
};
</script>

<style lang="scss" scoped>
.col-inner {
  min-height: 48px;
}
.col.empty .relative {
  @apply rounded rounded-l-none items-center p-0 content-center justify-center flex bg-gray-200 border-2 border-dashed flex-1 border-gray-600;
  .add-component {
    @apply text-gray-800;
  }
}
.col > div div:last-of-type .col-inner > div {
  @apply mb-0;
}

.col .col-options {
  @apply z-20;
}

.col.empty .col-options {
  @apply z-10;
}

.is-right {
  right: 0.25rem;
}

input[type="radio"] + label span {
  transition: background 0.2s, transform 0.2s;
}

.hidden {
  display: none;
}

input[type="radio"] + label span:hover,
input[type="radio"] + label:hover span {
  transform: scale(1.04);
}

input[type="radio"]:checked + label span {
  background-color: #3490dc; //bg-blue
  box-shadow: 0px 0px 0px 2px white inset;
}

input[type="radio"]:checked + label {
  color: #3490dc; //text-blue
}
</style>
