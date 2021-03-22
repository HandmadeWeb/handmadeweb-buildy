<template>
  <transition name="fadeHeight">
    <div
      class="module component-module rounded text-module relative flex flex-wrap items-center text-gray-400 p-1"
      :class="[
        isValidComponent ? 'bg-gray-800' : 'bg-gray-600',
        renderDisabled ? 'border-8 border-b-0 border-gray-500' : ''
      ]"
      :id="component.id"
    >
      <module-settings-bar :parent_array="parent_array"></module-settings-bar>

      <p
        ref="adminLabel"
        contenteditable="true"
        @blur="setDeep(component, 'options.admin_label', $el.innerText)"
        class="mx-auto my-0 py-2"
      >
        {{ admin_label }}
      </p>

      <span class="mr-2 mb-0" :title="component.type">
        <component v-if="component" :is="component.icon" />
      </span>

      <component :component="component" :is="moduleType"> </component>

      <span
        v-if="renderDisabled"
        class="w-full text-center py-1 text-sm italic bg-gray-500"
        >Frontend Output Disabled
      </span>
    </div>
  </transition>
</template>

<script>
import { setDeep } from "../../functions/objectHelpers";
import { mapGetters } from "vuex";
import {
  MenuIcon,
  MinusIcon,
  MapIcon,
  CoffeeIcon,
  PlayCircleIcon,
  ImageIcon,
  CodeIcon,
  SlidersIcon,
  ArrowRightIcon,
  AlignJustifyIcon,
  BoldIcon,
  GridIcon
} from "vue-feather-icons";

export default {
  name: "module-base",
  components: {
    MenuIcon,
    MinusIcon,
    MapIcon,
    CoffeeIcon,
    PlayCircleIcon,
    ImageIcon,
    CodeIcon,
    SlidersIcon,
    ArrowRightIcon,
    AlignJustifyIcon,
    BoldIcon,
    GridIcon
  },
  props: {
    component: Object,
    parent_array: Array
  },
  computed: {
    ...mapGetters(["validComponents"]),
    admin_label() {
      if (this.component.options && this.component.options.admin_label) {
        return this.component.options.admin_label;
      } else {
        return this.component.type
          ? this.component.type
          : "Placeholder for unknown component";
      }
    },
    isValidComponent() {
      return this.componentMap(this.component.type);
    },
    moduleType() {
      return this.isValidComponent ? this.component.type : "ShellModule";
    },
    renderDisabled() {
      return this.component.attributes?.renderDisabled || false;
    }
  },
  methods: {
    componentMap(type) {
      return !!this.validComponents.find(el => el.type === type);
    },
    setDeep
  },
  provide() {
    return {
      component: this.component,
      parent_array: this.parent_array
    };
  }
};
</script>
<style>
.component-module {
  transition: all 0.2s;
}

.list-enter-active,
.list-leave-active {
  transition: all 1s;
}
.list-enter, .list-leave-to /* .list-leave-active below version 2.1.8 */ {
  opacity: 0;
  transform: translateY(30px);
}
</style>
