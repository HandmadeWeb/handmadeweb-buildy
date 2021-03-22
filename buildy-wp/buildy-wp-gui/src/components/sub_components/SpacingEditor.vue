<template>
  <div class="spacing-editor module module-settings mt-0 flex flex-col">
    <breakpoint-switcher path="spacingEditor" @change="swapBreakpoint"/>
    <div class="spacing-editor__margin flex items-center">
      <span class="w-24 font-semibold">Margin:</span>
      <div class="flex flex-grow">
        <space-select
          class="px-2 flex-grow"
          v-for="direction in directions"
          :key="`margin-${breakpoint}-${direction}`"
          :path="`inline.margin.${breakpoint}.${direction}`"
          :label="direction"
        />
      </div>
    </div>
    <div class="spacing-editor__padding flex items-center">
      <span class="w-24 font-semibold">Padding:</span>
      <div class="flex flex-grow">
        <space-select
          class="px-2"
          v-for="direction in directions"
          :key="`padding-${breakpoint}-${direction}`"
          :path="`inline.padding.${breakpoint}.${direction}`"
          :label="direction"
        />
      </div>
    </div>
  </div>
</template>

<script>
//import { setDeep, getDeep } from '../../functions/objectHelpers';
export default {
  name: "spacing-editor",
  data: function() {
    return {
      breakpoint: "xl",
      directions: ["top", "bottom", "left", "right"],
      value: ""
    };
  },
  spaceRanges() {},
  methods: {
    swapBreakpoint(breakpoint) {
      breakpoint ? (this.breakpoint = breakpoint) : (this.breakpoint = "xl");
    }
  },
  mounted() {
    //this.value = getDeep(this.component, this.path)
  },
  inject: ["component"]
};
</script>
