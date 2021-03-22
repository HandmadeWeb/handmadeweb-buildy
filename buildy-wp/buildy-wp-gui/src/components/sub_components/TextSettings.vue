<template>
  <div>
    <breakpoint-switcher path="textSettings" @change="swapBreakpoint" />
    <div class="flex -mx-2 items-center">
      <radio-buttons
      class="px-2 mb-0"
        label="Text Align"
        options="left, center, right"
        :key="`textAlign-${breakpoint}`"
        :path="`inline.textAlign.${breakpoint}`"
      />
      <select-box
        class="px-2 flex-grow"
        label="Text Color"
        :options="textColours"
        :key="`textColor-${breakpoint}`"
        :path="`inline.color.${breakpoint}`"
      />
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
//import { setDeep, getDeep } from '../../functions/objectHelpers';
export default {
  name: "text-settings",
  data: function() {
    return {
      breakpoint: "xl",
    };
  },
  computed: {
    ...mapGetters(["colours"]),
    textColours() {
        let defaultColours = ['light', 'dark']
        return [...defaultColours, ...this.colours].join()
    }
  },
  methods: {
    swapBreakpoint(breakpoint) {
      breakpoint ? (this.breakpoint = breakpoint) : (this.breakpoint = "xl");
    }
  },
  inject: ["component"]
};
</script>

<style>

</style>
