<template>
  <div v-if="pageBuilder" id="buildy-root" class="page-wrap">
    <textarea
      id="buider"
      class="mt-1 mb-4 w-full hidden"
      name="content"
      v-model="pageBuilderOutput" />
    <container-module
      :pageBuilder="pageBuilder"
      adminClass="w-full"></container-module>
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      pageBuilder: [],
      output: [],
    }
  },
  computed: {
    pageBuilderOutput() {
      let output = []
      if (this.pageBuilder.length) {
        output = JSON.stringify(this.pageBuilder)
      }
      return output
    },
  },
  props: {
    msg: String,
    config: String,
    content: Array,
    validComponents: Array,
  },
  mounted() {
    if (this.validComponents.length) {
      this.$store.dispatch('validComponents', this.validComponents)
    }

    if (this.config.length) {
      this.$store.dispatch('config', JSON.parse(this.config))
    }

    if (this.content) {
      this.pageBuilder.push(...this.content)
    }

    // This code will be moved into plugin when plugins are enabled
    // Looks for columns, if found, it will filter out all "custom-fields" modules from inside them.
    // const filterACFModulesOnClone = (clone = {}) => {
    //   const columns = searchJSON(clone, 'column-module', 'type')
    //   if (columns.length && Array.isArray(columns)) {
    //     columns.forEach((column) => {
    //       if (!column.content || !Array.isArray(column.content)) return
    //       return (column.content = column.content.filter(
    //         (item) => item.type !== 'custom-fields-module'
    //       ))
    //     })
    //   }
    //   return clone
    // }
    // this.$hmw_hook.add('clone-row-module', filterACFModulesOnClone)
    // this.$hmw_hook.add('clone-section-module', filterACFModulesOnClone)
    // this.$hmw_hook.add('before-paste', filterACFModulesOnClone)
  },
}
</script>

<style lang="scss">
img {
  border: none;
  border-style: none;
}
</style>
