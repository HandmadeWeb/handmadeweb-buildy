<template>
  <div class="cta-button module module-settings mt-0">
    <div class="flex flex-wrap items-center mb-2">
      <div class="flex-grow">
        <div class="flex flex-col mb-2">
          <label
            :for="label + '-button-text-' + index"
            class="pr-4 capitalize setting-label flex-shrink-0"
            >{{ name || "Button" }} Text:</label
          >
          <input
            :id="label + '-button-text-' + index"
            class="text-gray-800 w-full border-2 p-2"
            @blur="change"
            v-model="payload.text"
          />
        </div>
        <div class="flex flex-col mb-4">
          <label
            :for="label + '-button-url-' + index"
            class="pr-4 setting-label flex-shrink-0"
            >URL:</label
          >
          <input
            :id="label + '-button-url-' + index"
            class="text-gray-800 w-full border-2 p-2"
            @blur="change"
            v-model="payload.url"
          />
        </div>

        <div class="flex -mx-2 mb-4">
          <div v-if="!payload.manualStyle" class="px-2 flex-grow">
            <select
              class="w-full p-2"
              :id="label + '-button-style-' + index"
              @blur="change"
              v-model="payload.buttonStyle"
            >
              <option
                v-for="(style, i) in buttonStyles"
                :value="style.val"
                :key="component.id + label + i + '-buttonStyle-' + style"
              >
                {{ style.key }}
              </option>
            </select>
          </div>
          <div class="px-2 flex-grow">
            <div class="flex mr-6 flex-col items-center justify-center">
              <select
                class="w-full p-2"
                :id="label + '-button-size-' + index"
                @change="change"
                v-model="payload.size"
              >
                <option
                  v-for="size in sizes"
                  :key="component.id + label + '-border-' + size"
                >
                  {{ size }}
                </option>
              </select>
            </div>
          </div>
        </div>
        <div
          v-if="component.type === 'blurb-module'"
          class="flex flex-col mb-2"
        >
          <label
            :for="label + '-button-class-' + index"
            class="pr-4 capitalize setting-label flex-shrink-0"
            >{{ name || "Button" }} Class:</label
          >
          <input
            :id="label + '-button-class-' + index"
            class="text-gray-800 w-full border-2 p-2"
            @blur="change"
            v-model="payload.class"
          />
        </div>
      </div>
      <div v-if="payload.text" class="preview py-4 max-w-xs pl-10">
        <p
          ref="buttonText"
          class="button-text btn mx-auto rounded mb-0"
          :class="[manualStyle, styleClass, `btn--${payload.size}`]"
          contenteditable="true"
        >
          {{ payload.text }}
        </p>
      </div>
    </div>
    <div v-if="payload.manualStyle" class="flex items-center mb-4">
      <div class="flex flex-col items-center justify-center mr-6">
        <label :for="label + '-button-colour-' + index" class="pb-1"
          >Text Color</label
        >
        <select
          class="w-full p-2"
          :id="label + '-button-colour-' + index"
          @blur="change"
          v-model="payload.colour"
        >
          <option
            v-for="colour in colours"
            :key="component.id + label + '-colour-' + colour"
          >
            {{ colour }}
          </option>
        </select>
      </div>
      <div class="flex mr-6 flex-col items-center justify-center">
        <label :for="label + '-button-bg-' + index" class="pb-1"
          >Background Color</label
        >
        <select
          class="w-full p-2"
          :id="label + '-button-bg-' + index"
          @blur="change"
          v-model="payload.backgroundColor"
        >
          <option
            v-for="colour in colours"
            :key="component.id + label + '-bg-' + colour"
          >
            {{ colour }}
          </option>
        </select>
      </div>
    </div>

    <div class="flex">
      <toggle-switch
        class="flex-col items-baseline"
        label="Manual Styles"
        :status="payload.manualStyle"
        :path="`${path}.manualStyle`"
        @toggle="handleToggle('manualStyle', $event)"
      ></toggle-switch>
      <toggle-switch
        class="flex-col items-baseline"
        label="New window?"
        :path="`${path}.target`"
        :status="payload.target"
        @toggle="handleToggle('target', $event)"
      ></toggle-switch>
    </div>
  </div>
</template>

<script>
import { setDeep, getDeep } from "../../functions/objectHelpers";
import { mapGetters } from "vuex";

export default {
  name: "cta-button",
  data: function () {
    return {
      sizes: ["Size", "Initial", "sm", "lg"],
      buttonStyles: [
        {
          key: "Primary",
          val: "primary",
        },
        {
          key: "Secondary",
          val: "secondary",
        },
        {
          key: "Outlined",
          val: "outlined",
        },
        {
          key: "Unstyled",
          val: "unstyled",
        },
        {
          key: "Style 1",
          val: "style-1",
        },
        {
          key: "Style 2",
          val: "style-2",
        },
        {
          key: "Style 3",
          val: "style-3",
        },
      ],
      payload: {
        text: "",
        url: "",
        colour: "",
        borderColor: "",
        backgroundColor: "",
        buttonStyle: "primary",
        target: false,
        unStyled: false,
        showBackground: false,
        size: "Size (auto)",
        class: "",
        manualStyle: false,
      },
    };
  },
  computed: {
    ...mapGetters(["colours", "sizeModifiers"]),
    manualStyle() {
      if (!this.payload.manualStyle) {
        return "";
      }

      let cls = "";

      if (this.payload.backgroundColor) {
        cls += ` bg-${this.payload.backgroundColor} border-${this.payload.backgroundColor}`;
      }

      if (this.payload.colour) {
        cls += ` text-${this.payload.colour}`;
      }

      return cls;
    },
    styleClass() {
      if (this.payload.manualStyle) {
        return "";
      }
      return `btn--${this.payload.buttonStyle}`;
    },
  },
  props: {
    label: {
      type: String,
      default: "button",
    },
    enabled: {
      type: Boolean,
      default: true,
    },
    index: {
      type: Number,
      default: 0,
    },
    path: {
      type: String,
      default: "content.button",
    },
    name: String,
  },
  methods: {
    handleToggle() {
      setDeep(this.component, this.path, this.payload);
    },
    change() {
      setDeep(this.component, this.path, this.payload);
    },
  },
  mounted() {
    console.log(this.component);
    this.payload = getDeep(this.component, this.path) || this.payload;
  },
  inject: ["component"],
};
</script>

<style scoped>
.preview {
  overflow: hidden;
}
.btn {
  max-width: 165px;
  text-overflow: ellipsis;
  white-space: nowrap;
}
select.w-full {
  max-width: none;
}
</style>
