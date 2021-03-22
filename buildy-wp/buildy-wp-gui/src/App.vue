<template>
  <div v-if="pageBuilder" id="buildy-root" class="page-wrap">
    <textarea
      id="buider"
      class="mt-1 mb-4 w-full hidden"
      name="content"
      v-model="pageBuilderOutput"
    />
    <container-module
      :pageBuilder="pageBuilder"
      :globalAPI="configAPI"
      adminClass="w-full"
    ></container-module>
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      pageBuilder: [],
      output: [],
    };
  },
  computed: {
    pageBuilderOutput() {
      let output = [];
      if (this.pageBuilder.length) {
        output = JSON.stringify(this.pageBuilder);
      }
      return output;
    },
    configAPI() {
      if (this.config.length) {
        const configObj = JSON.parse(this.config);
        return configObj.global_api;
      }
      return "";
    },
  },
  props: {
    msg: String,
    config: Array,
    content: Array,
    validComponents: Array,
  },
  mounted() {
    if (this.validComponents.length) {
      this.$store.dispatch("validComponents", this.validComponents);
    }

    if (this.config.length) {
      this.$store.dispatch("config", JSON.parse(this.config));
    }

    if (this.content) {
      this.pageBuilder.push(...this.content);
    }
  },
};
</script>

<style lang="scss">
img {
  border: none;
  border-style: none;
}
</style>
