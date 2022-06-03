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
import { searchJSON } from './functions/jsonSearch'
import { stripTrailingSlash } from './functions/helpers'
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

    const cloneACFModule = (clone) => {
      const acfModules = searchJSON(clone, 'acf-module', 'type')
      if (
        acfModules !== null &&
        acfModules !== undefined &&
        acfModules.length
      ) {
        // Loop over all acfModules that are found in any context (directly, row, section, column, etc)
        acfModules.forEach(async (acfModule) => {
          if (
            !acfModule?.content?.acfForm ||
            acfModule?.content?.acfForm.is_linked
          ) {
            return
          }

          // Get the postID of the current one
          const postID = acfModule.content.acfForm.post_id

          // Instantiate var
          let res

          try {
            res = await fetch(
              `${stripTrailingSlash(
                window.global_vars.rest_api_base
              )}/bmcb/v1/acf_duplicate_post/post_id=${postID}`,
              {
                headers: {
                  'X-WP-Nonce': window.global_vars.rest_nonce,
                },
              }
            )
          } catch (error) {
            console.log(error)
          }

          const data = await res.json()

          if (data.body) {
            acfModule.content.acfForm.post_id = data.body.id
            acfModule.options.admin_label = `Custom Fields - ${acfModule.content.acfForm.title} - ${data.body.id}`
            // set is_linked to false??
            return acfModule
          }

          acfModule.content.acfForm.post_id = null
          acfModule.options.admin_label = 'Custom Fields'

          return acfModule
        })
      }
    }

    this.$hmw_hook.add('clone-acf-module', cloneACFModule)
    this.$hmw_hook.add('clone-row-module', cloneACFModule)
    this.$hmw_hook.add('clone-section-module', cloneACFModule)
    this.$hmw_hook.add('before-paste', cloneACFModule)
  },
}
</script>

<style lang="scss">
img {
  border: none;
  border-style: none;
}
</style>
