<template>
  <select-box
    v-if="moduleType"
    path="options.moduleStyle"
    slot="design"
    label="Module Style"
    class="items-center"
    inline
    :endpoint="`bmcb/v1/module_styles=${moduleType}`"
  />
</template>

<script>
export default {
  name: "module-styles",
  data: function() {
    return {
      moduleType: null
    };
  },
  inject: ["component"],
  mounted() {
    if (this.component) {
      this.moduleType = this.component.type.split("-")[0];
    }
  }
};
</script>

<style></style>
