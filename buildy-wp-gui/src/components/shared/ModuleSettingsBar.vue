<template>
  <div
    class="settings-bar rounded rounded-r-none rounded-b-none module"
    :class="[`bg-${colorClasses}`]">
    <ul class="list-unstyled flex py-1" :class="[`flex-${direction}`]">
      <li
        v-for="setting in settings"
        :key="component.id + setting.name"
        class="flex px-3 items-center m-0"
        :title="setting.title">
        <component
          :is="setting.icon"
          @click="setting.action"
          class="text-white cursor-pointer"
          size="1.5x"></component>
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
  ClipboardIcon,
  ExternalLinkIcon,
} from 'vue-feather-icons'
import { recursifyID } from '../../functions/idHelpers'
export default {
  name: 'module-settings-bar',
  components: {
    MenuIcon,
    Trash2Icon,
    CopyIcon,
    ColumnsIcon,
    ClipboardIcon,
    ExternalLinkIcon,
  },
  computed: {
    colorClasses() {
      switch (this.component.type) {
        case 'row-module':
          return 'green-500'
        case 'section-module':
          return 'blue-500'
        default:
          return 'none'
      }
    },
    settings() {
      return Object.values({
        menu: {
          name: 'Menu',
          icon: 'MenuIcon',
          title: 'Open settings modal',
          action: this.openModal,
          order: 10,
        },
        clone: {
          name: 'Clone',
          icon: 'CopyIcon',
          title: 'Clone Module',
          action: this.cloneModule,
          order: 20,
        },
        delete: {
          name: 'Delete',
          icon: 'Trash2Icon',
          title: 'Delete Module',
          action: this.deleteModule,
          order: 30,
        },
        ...(this?.component?.customSettings || {}),
        ...this.customSettings,
      })
        .filter((el) => el)
        .sort((a, b) => a.order - b.order)
    },
  },
  methods: {
    async cloneModule() {
      // Stringify is a deep-clone
      let clone = JSON.parse(JSON.stringify(this.component))

      // Generate ID's recursively
      recursifyID(clone)

      // Find index of current item in parent
      let index = this.parent_array.findIndex(
        (el) => el.id === this.component.id
      )

      try {
        // Fire off a hook that allows altering of the clone result before it is saved to the JSON
        // You can hook into this event which fires on any module type
        clone = await this.$hmw_hook.run(`clone-module`, clone)

        // Check if any specific callbacks back have been registered for this module type and run those too
        if (this.$hmw_hook.getCallbacks(`clone-${this.component.type}`)) {
          clone = await this.$hmw_hook.run(`clone-${clone.type}`, clone)
        }

        // Add clone into page (after teh current item)
        this.parent_array.splice(index + 1, 0, clone)
      } catch (error) {
        console.log(error)
      }
    },

    async deleteModule() {
      // Find index of current item in parent
      let index = this.parent_array.findIndex(
        (el) => el.id === this.component.id
      )

      try {
        // Fire off a hook that allows running some code before a the module is deleted
        let continueDelete = await this.$hmw_hook.run(
          `delete-module`,
          this.component
        )

        // Check if any specific callbacks back have been registered for this module type and run those too
        if (this.$hmw_hook.getCallbacks(`delete-${this.component.type}`)) {
          continueDelete = await this.$hmw_hook.run(
            `delete-${this.component.type}`,
            this.component
          )
        }

        // if the hook returns false, we won't delete the module
        if (!continueDelete) return

        // Remove module from page
        this.parent_array.splice(index, 1)
      } catch (error) {
        console.log(error)
      }
    },
    openModal() {
      this.$modal.show(this.component.id)
    },
  },
  props: {
    customSettings: {
      type: Object,
      default: () => {},
    },
    direction: {
      type: String,
      default: 'row',
    },
    parent_array: Array,
  },
  inject: ['component'],
}
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
