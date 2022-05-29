<template>
  <settings-modal>
    <acf-module-select
      label="Custom Module"
      class="items-center"
      :endpoint="`bmcb/v1/acf_modules`"
      v-if="showSelect"
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
      showSelect: true,
      isLoading: false
    };
  },
  methods: {
  },
  mounted() {
    EventBus.$on('isLoading', (e) => {
      this.isLoading = e
    })
    EventBus.$on('showSelect', (e) => {
      this.showSelect = e
    })
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