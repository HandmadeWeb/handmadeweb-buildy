<template>
  <settings-modal>
    <div class="form-type text-right" v-if="showSelect">
      <a href="#" @click.prevent="toggleExisting">{{ formTypeLabel }}</a>
    </div>
    <acf-module-select
      label="Existing Module"
      class="items-center"
      key="load"
      :endpoint="`bmcb/v1/acf_posts`"
      v-if="showSelect && showExisting"
    />
    <acf-module-select
      label="Custom Module"
      class="items-center"
      key="create"
      :endpoint="`bmcb/v1/acf_modules`"
      v-if="showSelect && !showExisting"
    />
    <acf-form />
    <div class="loading" v-show="isLoading"><div></div><div></div><div></div><div></div></div>
  </settings-modal>
</template>
<script>

import { EventBus } from "../EventBus";

export default {
  name: "acf-module",
  data: function() {
    return {
      icon: "CodeIcon",
      showExisting: false,
      defaultformType: "Link Existing",
      formTypeLabel: '',
      showSelect: true,
      isLoading: false
    };
  },
  methods: {
    // Toggle between 'Link Existing' and 'Create New' dropdowns
    toggleExisting() {
      // Clear form HTML in preparation for new data
      EventBus.$emit("clearFormHTML");
      // Toggle the dropdowns to display different data
      this.showExisting = !this.showExisting
      // Update the button label 
      if( this.showExisting ) {
        this.formTypeLabel = 'Create New'
      } else {
        this.formTypeLabel = this.defaultformType
      }
    }
  },
  mounted() {
    // Listen for 'isLoading' event and render loading spinner as required
    EventBus.$on('isLoading', (e) => {
      this.isLoading = e
    })
    // Listen for 'showSelect' event and show/hide the select options as required 
    EventBus.$on('showSelect', (e) => {
      this.showSelect = e
    })
    // Update the button label on load to default value
    this.formTypeLabel = this.defaultformType
  },
  destroyed() {
    EventBus.$off('isLoading')
    EventBus.$off('showSelect')
  },
  inject: ["component"],
};
</script>

<style>
.loading {
  display: inline-block;
  position: relative;
  width: 100%;
  height: 40px;
}
.loading div {
  box-sizing: border-box;
  display: block;
  position: absolute;
  left: calc(50% - 16px);
  width: 32px;
  height: 32px;
  margin: 4px;
  border: 4px solid #666;
  border-radius: 50%;
  animation: loading 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
  border-color: #666 transparent transparent transparent;
}
.loading div:nth-child(1) {
  animation-delay: -0.45s;
}
.loading div:nth-child(2) {
  animation-delay: -0.3s;
}
.loading div:nth-child(3) {
  animation-delay: -0.15s;
}
@keyframes loading {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>