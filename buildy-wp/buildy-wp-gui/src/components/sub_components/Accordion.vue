<template>
  <section class="faq mt-4" ref="rootEl">
    <transition name="accordion-fade-slide" mode="out-in">
      <div v-if="items.length" class="faq-wrapper">
        <transition name="accordion-fade-slide" mode="out-in">
          <div v-if="showAccordion" class="accordion">
            <draggable
              :list="dragArray"
              v-bind="dragOptions"
              @change="updateListOrder"
            >
              <div
                class="accordion__item"
                v-for="(item, i) in items"
                :key="`accordion-item-${i}`"
              >
                <div
                  class="accordion__title-text p-3"
                  :class="generateModuleClasses(i)"
                  @click.prevent="toggleActive(i, item)"
                >
                  <p class="mb-0">
                    {{
                      item.editMode
                        ? "..."
                        : item[titleProperty] || "Click to enter content"
                    }}
                  </p>
                  <div class="accordion-controls flex items-center">
                    <copy-icon @click.stop="cloneItem(item)" class="mr-4" />
                    <trash-icon @click.stop="removeItem(item)" class="mr-4" />
                    <chevron-right-icon :class="generateButtonClasses(i)" />
                  </div>
                </div>
                <collapse-transition>
                  <div
                    class="accordion__body px-3 pb-3"
                    v-if="i === activeItemIndex"
                  >
                    <input
                      @change="updateItem(item, i, 'title', $event)"
                      type="text"
                      class="px-4 py-2 w-full mb-2 border"
                      :value="item[titleProperty]"
                      placeholder="Enter title content"
                    />
                    <slot v-bind:item="item">
                      <component
                        :is="editorType"
                        :index="i"
                        :path="`${path}.${i}.body`"
                      >
                      </component>
                      <!-- <p class="accordion__value" v-html="item[answerProperty]"></p> -->
                      <!-- <label class="mt-3 block"> Add to tab:
                                    <input :value="item.category" class="py-2 px-3 w-full" @change="updateItem(item, 'category', $event)" type='text' />
                                </label> -->
                    </slot>
                    <image-uploader
                      v-if="isSlider"
                      :path="`${path}.${i}.image`"
                      :key="`image-${i}`"
                      imageType="img"
                      label="Slide Image:"
                    ></image-uploader>

                    <custom-fields :path="`${path}.${i}.customFields`" />
                  </div>
                </collapse-transition>
              </div>
            </draggable>
          </div>
        </transition>
      </div>
    </transition>
    <a class="mt-4 flex" @click.prevent="addItem" href="#"
      ><plus-circle-icon class="mr-2" /> Add
      {{ isSlider ? "slide" : "item" }}</a
    >
  </section>
</template>

<script>
import { setDeep, getDeep } from "../../functions/objectHelpers";
import {
  EditIcon,
  XIcon,
  TrashIcon,
  CopyIcon,
  PlusCircleIcon,
  ChevronRightIcon,
  PlusIcon
} from "vue-feather-icons";
import { CollapseTransition } from "vue2-transitions";
import draggable from "vuedraggable";
import { mapGetters } from "vuex";
export default {
  name: "VueFaqAccordion",
  components: {
    CollapseTransition,
    draggable,
    EditIcon,
    XIcon,
    PlusCircleIcon,
    PlusIcon,
    ChevronRightIcon,
    TrashIcon,
    CopyIcon
  },
  data() {
    return {
      activeTab: "",
      activeItemIndex: null,
      showAccordion: true,
      disableDrag: false,
      items: []
    };
  },
  props: {
    /**
     * Array of items
     * Object style {titleProperty: string, answerProperty: string, tabName: string}
     * You can change object keys names using other props (titleProperty, answerProperty, tabName)
     */
    itemPayload: {
      type: Array,
      required: false
    },
    /**
     * Key name of object in items array for specifying title of title
     */
    titleProperty: {
      type: String,
      default: "title"
    },
    /**
     * Path on the component where this module stores the content
     */
    path: {
      type: String,
      default: "content.accordion.items"
    },
    /**
     * Key name of object in items array for specifying content text of open title
     */
    answerProperty: {
      type: String,
      default: "body"
    },
    /**
     * Color for hover and active tab/title
     * possible values: 'red', '#F00', 'rgb(255, 0, 0)'
     */
    activeColor: {
      type: String,
      default: "#D50000"
    },
    /**
     * Color for borders
     */
    borderColor: {
      type: String,
      default: "#9E9E9E"
    },
    /**
     * Color for fonts
     */
    fontColor: {
      type: String,
      default: "#000000"
    },
    /**
     * Enable Image
     */
    isSlider: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    ...mapGetters(["isWP"]),
    editorType() {
      return this.isWP ? "rich-tiny" : "rich-text";
    },
    htmlTag() {
      if (this.componentType === "input") {
        return "input";
      }
      return this.tag;
    },
    dragArray() {
      return this.items;
    },
    dragOptions() {
      return {
        group: "accordion_items",
        ghostClass: "ghost",
        disabled: this.disableDrag
      };
    }
  },
  methods: {
    toggleActive(itemIndex, item) {
      this.activeItemIndex =
        this.activeItemIndex === itemIndex ? null : itemIndex;

      // Set any previous ones back to false
      this.items = this.items.map(item => {
        item.editMode = false;
        return item;
      });

      // Set the current index one to true
      if (this.activeItemIndex === itemIndex) {
        item.editMode = true;
      } else {
        item.editMode = false;
      }
    },
    addItem() {
      let newItem = { title: "", body: "", editMode: false };
      this.items.push(newItem);
      this.toggleActive(this.items.length - 1, newItem);
    },
    cloneItem(item) {
      let newItem = JSON.parse(JSON.stringify(item));
      this.items.push(newItem);
      this.toggleActive(this.items.length - 1, newItem);
    },
    updateItem(item, index, prop, val) {
      this.items[index][prop] = val.target.value;
      setDeep(this.component, this.path, this.items);
    },
    removeItem(item) {
      const index = this.items.findIndex(el => el.title === item.title);
      let confirm = window.confirm(
        "Are you sure you want to delete this item?"
      );
      if (confirm) {
        this.items.splice(index, 1);
      }
    },
    generateButtonClasses(buttonIndex) {
      return [
        "accordion__toggle-button",
        this.activeItemIndex === buttonIndex
          ? "accordion__toggle-button_active"
          : null
      ];
    },
    generateModuleClasses(titleIndex) {
      return [
        "accordion__title",
        this.activeItemIndex === titleIndex ? "accordion__title_active" : null
      ];
    },
    updateListOrder() {
      setDeep(this.component, this.path, this.dragArray);
      // this.updateStoreComponent({ path: 'content.accordion.items', prop: this.dragArray})
    }
  },
  mounted() {
    let currentItems = getDeep(this.component, this.path);
    this.items = currentItems || [];
    this.$refs.rootEl.style.setProperty("--active-color", this.activeColor);
    this.$refs.rootEl.style.setProperty("--border-color", this.borderColor);
    this.$refs.rootEl.style.setProperty("--font-color", this.fontColor);
  },
  inject: ["component"],
  provide() {
    return {
      component: this.component
    };
  }
};
</script>

<style lang="scss" scoped>
button {
  border: none;
  background: none;
  outline: none;
}
.faq {
  width: 100%;
  padding: 0 10px;
  &-wrapper {
    max-width: 825px;
  }
  &__title {
    text-align: center;
    margin-bottom: 25px;
  }
  &__nav {
    display: flex;
    justify-content: space-between;
    border: 2px solid var(--border-color);
    border-radius: 5px;
  }
  &__nav-item {
    height: 60px;
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    border-right: 2px solid var(--border-color);
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s;
    text-align: center;
    user-select: none;
    color: var(--font-color);
    &_active {
      color: var(--active-color);
    }
    &:hover {
      color: var(--active-color);
    }
    &:last-child {
      border-right: none;
    }
  }
  &__accordion {
    min-height: 250px;
  }
}
.accordion-fade-slide {
  &-enter-active,
  &-leave-active {
    transition: all 0.3s;
  }
  &-enter {
    transform: translateY(-25px);
    opacity: 0;
  }
  &-leave-to {
    transform: translateY(25px);
    opacity: 0;
  }
}
.accordion {
  border: 2px solid var(--border-color);
  border-radius: 5px;
  &__item {
    border-bottom: 2px solid var(--border-color);
    &:last-child {
      border-bottom: none;
    }
  }
  &__title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    transition: all 0.3s;
    color: var(--font-color);
    &_active {
      color: var(--active-color);
    }
    &:hover {
      color: var(--active-color);
      .accordion__toggle-button {
        &::before,
        &::after {
          background: var(--active-color);
        }
      }
    }
  }
  &__body {
    text-align: left;
    color: var(--font-color);
  }
  &__toggle-button {
    transition: all 0.3s;
    cursor: pointer;
    &::before,
    &::after {
      transition: all 0.3s;
    }
    &::before {
      transform: rotate(90deg);
    }
    &_active {
      transform: rotate(90deg);
      &::before,
      &::after {
        background: var(--active-color);
      }
    }
  }
}
</style>
