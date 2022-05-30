<template>
  <modal
    class="settings-modal"
    draggable=".dragHandler"
    :scrollable="true"
    :resizable="true"
    :name="component.id"
    :height="'auto'"
    :click-to-close="clickToClose"
    :max-height="600"
    @opened="modalOpened"
    @closed="modalClosed"
    @waitToSave="waitToSave"
    @before-close="beforeClose"
  >
    <div class="bg-gray-700 modal-controls absolute w-full flex">
      <div class="dragHandler cursor-move flex-1">
        <span class="block text-gray-400 pl-6 h-full flex items-center">{{
          header ? header : component.options && component.options.admin_label
        }}</span>
      </div>
      <x-icon
        @click="$modal.hide(component.id)"
        class="text-gray-400 cursor-pointer inset-y-0 m-2 right-0"
        size="1.5x"
      ></x-icon>
    </div>
    <div @click="modalClick" class="p-0 pt-10 bg-gray-300 text-gray-800">
      <vue-tabs :id="component.id">
        <vue-tab
          name="Content"
          v-if="hasDefaultSlot"
          :selected="hasDefaultSlot"
        >
          <!-- Custom Content Tab Slot -->
          <slot ref="module" />

          <!-- Accordion Modules Need Different Paths -->
          <custom-fields v-if="component.type !== 'accordion-module'" />

          <!-- Admin label moved to content tab -->
          <attribute-editor
            label="Admin Label"
            path="options.admin_label"
          ></attribute-editor>
        </vue-tab>
        <vue-tab class="design-tab" name="Design">
          <!-- Design Options -->
          <module-styles />

          <fieldset class="field-group">
            <legend class="uppercase text-sm px-4">Spacing:</legend>
            <spacing-editor class="pb-0 mb-0" />
          </fieldset>

          <fieldset class="field-group">
            <legend class="uppercase text-sm px-4">Text:</legend>
            <text-settings />
          </fieldset>

          <color-picker
            label="Background Color:"
            path="inline.backgroundColor"
          />
          <image-uploader
            path="inline.backgroundImage"
            :key="'background-image-' + component.id"
            label="Background Image:"
          ></image-uploader>

          <!-- Add things to design tab -->
          <slot name="design"></slot>
        </vue-tab>
        <vue-tab name="Options" :selected="!hasDefaultSlot">
          <!-- Global Options -->
          <attribute-editor
            label="ID"
            path="attributes.id"
            attribute="id"
          ></attribute-editor>
          <attribute-editor
            label="Class"
            path="attributes.class"
            attribute="class"
            sibblingLink
          ></attribute-editor>
          <field-repeater label="data-" path="attributes.data"></field-repeater>

          <attribute-editor
            v-if="
              !component.attributes ||
              !component.attributes.in_page_link_enabled
            "
            label="External Link"
            path="options.module_link.url"
            attribute="Module LinkLink"
            sibblingLink
          ></attribute-editor>

          <attribute-editor
            v-if="
              component.attributes && component.attributes.in_page_link_enabled
            "
            label="Internal Link Text"
            path="attributes.in_page_link_text"
          ></attribute-editor>
          <div class="flex items-center">
            <toggle-switch
              label="In-page Link instead?"
              :status="
                component.attributes &&
                component.attributes.in_page_link_enabled
              "
              path="attributes.in_page_link_enabled"
            ></toggle-switch>

            <toggle-switch
              class="ml-10"
              label="Open link in new tab?"
              v-if="
                !component.attributes ||
                !component.attributes.in_page_link_enabled
              "
              :status="
                component.options &&
                component.options.module_link &&
                component.options.module_link.new_tab
              "
              path="options.module_link.new_tab"
            ></toggle-switch>
          </div>

          <toggle-switch
            label="Disable on front end"
            :status="
              component.attributes && component.attributes.renderDisabled
            "
            path="attributes.renderDisabled"
          ></toggle-switch>

          <!-- Custom Options Tab Slot -->
          <slot name="options"></slot>
        </vue-tab>
        <template v-if="customSlots">
          <vue-tab
            v-for="customSlot in customSlots"
            :key="customSlot"
            :name="UCFirst(customSlot)"
          >
            <!-- Custom Content Tab Slot -->
            <slot :name="customSlot" />
          </vue-tab>
        </template>
      </vue-tabs>
      <a
        @click.prevent="saveAll"
        class="py-2 uppercase text-sm block bg-blue-800 text-white rounded-none w-full text-center font-bold"
        href="#"
        >Save</a
      >
    </div>
  </modal>
</template>

<script>
import { EventBus } from "../../EventBus";
import { UCFirst } from "../../functions/helpers";
import { XIcon } from "vue-feather-icons";
export default {
  name: "settings-modal",
  components: {
    XIcon,
  },
  props: {
    header: String,
    component: Object,
    clickToClose: {
      type: Boolean,
      default: true
    },
    customSlots: {
      type: Array,
    },
  },
  data: function() {
    return { 
      closeable: true, 
      waitToSave: false,
    }
  },
  computed: {
    hasDefaultSlot() {
      return !!this.$slots.default;
    },
  },
  methods: {
    beforeClose(e) {
      EventBus.$emit('before-close', e)
    },
    modalOpened() {
      this.$store.dispatch("dragToggle", true);
      this.$emit("modalOpened");
    },
    modalClosed() {
      this.$store.dispatch("dragToggle", false);
    },
    modalClick() {
      EventBus.$emit("modalClick");
    },
    saveAll() {
      EventBus.$emit("saveAll", this.component.id);
      if( this.waitToSave ) {
        return;
      }
      this.$modal.hide(this.component.id);
    },
    UCFirst,
  },
  mounted() {
    EventBus.$on('waitToSave', (e) => {
      this.waitToSave = e
    })
    EventBus.$on('doSave', (e) => {
      this.saveAll()
    })
  },
  destroyed() {
    EventBus.$off('waitToSave')
    EventBus.$off('doSave')
  },
  inject: ["component"],
  provide() {
    return {
      component: this.component,
    };
  },
};
</script>

<style>
.settings-modal.vm--container {
  padding-top: 40px;
}

.settings-modal.vm--container .v--modal {
  overflow: visible;
}
</style>
