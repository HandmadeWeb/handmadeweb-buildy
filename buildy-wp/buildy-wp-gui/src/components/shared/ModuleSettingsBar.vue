<template>
  <div
    class="settings-bar rounded rounded-r-none rounded-b-none module"
    :class="[`bg-${colorClasses}`]"
  >
    <ul class="list-unstyled flex py-1" :class="[`flex-${direction}`]">
      <li
        v-for="setting in settings"
        :key="component.id + setting.name"
        class="flex px-3 items-center m-0"
      >
        <component
          :is="setting.icon"
          @click="setting.action"
          class="text-white cursor-pointer"
          size="1.5x"
        ></component>
      </li>
    </ul>
  </div>
</template>

<script>
import {
  MenuIcon,
  Trash2Icon,
  CopyIcon,
  ColumnsIcon,
  ClipboardIcon
} from "vue-feather-icons";
import { recursifyID } from "../../functions/idHelpers";
export default {
  name: "module-settings-bar",
  components: {
    MenuIcon,
    Trash2Icon,
    CopyIcon,
    ColumnsIcon,
    ClipboardIcon
  },
  computed: {
    colorClasses() {
      switch (this.component.type) {
        case "row-module":
          return "green-500";
        case "section-module":
          return "blue-500";
        default:
          return "none";
      }
    },
    settings() {
      return [
        {
          name: "Menu",
          icon: "MenuIcon",
          action: this.openModal,
          order: 10
        },
        {
          name: "Clone",
          icon: "CopyIcon",
          action: this.cloneModule,
          order: 20
        },
        {
          name: "Delete",
          icon: "Trash2Icon",
          action: this.deleteModule,
          order: 30
        },
        ...this.customSettings
      ].sort((a, b) => a.order - b.order);
    }
  },
  methods: {
    cloneModule() {
      // Stringify is a deep-clone
      let clone = JSON.parse(JSON.stringify(this.component));

      // Generate ID's recursively
      recursifyID(clone);

      // Find index of current item in parent
      let index = this.parent_array.findIndex(
        el => el.id === this.component.id
      );

      // Add clone directly after it
      this.parent_array.splice(index + 1, 0, clone);
    },
    deleteModule() {
      // Find index of current item in parent
      let index = this.parent_array.findIndex(
        el => el.id === this.component.id
      );

      // Delete it
      this.parent_array.splice(index, 1);
    },
    openModal() {
      this.$modal.show(this.component.id);
    }
  },
  props: {
    customSettings: {
      type: Array,
      default: () => []
    },
    direction: {
      type: String,
      default: "row"
    },
    parent_array: Array
  },
  inject: ["component"]
};
</script>

<style lang="scss" scoped>
.flex-col {
  li {
    @apply py-2;
  }
}
.settings-bar ul {
  position: sticky;
  top: 40px;
}
</style>
