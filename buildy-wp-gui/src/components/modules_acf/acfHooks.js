import { searchJSON } from '../../functions/jsonSearch'

export const acfHooks = (vm) => {
  const cloneACFModule = async (resolve, reject, clone) => {
    const acfModules = searchJSON(clone, 'acf-module', 'type')
    if (acfModules !== null && acfModules !== undefined && acfModules.length) {
      // Loop over all acfModules that are found in any context (directly, row, section, column, etc)
      const promises = acfModules.map(async (acfModule) => {
        if (acfModule.content.acfForm.is_linked) {
          vm.$vToast.warning(
            'Cloning "linked" acf modules will not make a copy, instead it will clone them as a global, meaning they can overwrite each other.',
            { duration: 6000 }
          )
          return
        }
        if (!acfModule?.content?.acfForm) {
          return
        }

        // Get the postID of the current one
        const postID = acfModule.content.acfForm.post_id

        if (postID) {
          const form = new FormData()
          form.append('action', 'acf_duplicate_post')
          form.append('post_id', postID)
          form.append('nonce', window.global_vars.nonce)

          const params = new URLSearchParams(form)
          const res = await fetch(window.global_vars.admin_ajax_url, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: params,
          })

          const data = await res.json()

          if (data) {
            acfModule.content.acfForm.post_id = data
            if (acfModule.content.acfForm.hasOwnProperty('is_linked')) {
              acfModule.content.acfForm.is_linked = false
            }
            acfModule.options.admin_label = `Custom Fields - ${acfModule.content.acfForm.field_groups_title} - ${data}`
            return acfModule
          }
          reject(new Error('Something went wrong with cloning'))
        }

        // if postID isn't found, the clone will be bypassed and the module will clone as normal
        acfModule.content.acfForm.post_id = null
        acfModule.options.admin_label = 'Custom Fields'
        return acfModule
      })

      await Promise.all(promises)

      resolve(clone)
    }
  }

  const deleteACFModule = (resolve, reject, el) => {
    const acfModules = searchJSON(el, 'acf-module', 'type')
    if (acfModules !== null && acfModules !== undefined && acfModules.length) {
      acfModules.forEach((acfModule) => {
        if (acfModule.content.acfForm.is_linked) {
          vm.$vToast.warning(
            `You just deleted a global (linked) module, you can re-link it back at anytime by clicking "link existing" inside a new ACF Module and choosing module with ID: ${acfModule.content.acfForm.post_id}`,
            { duration: 6000 }
          )
        }
        if (acfModule.options.admin_label.toLowerCase().includes('protected')) {
          vm.$vToast.error(
            'This module is important for the design of this layout and cannot be deleted from here',
            { duration: 6000 }
          )
          reject(
            `ACF Module: ${acfModule.content.acfForm.post_id} cannot be deleted.`
          )
        }
      })
      resolve(true)
    }
    resolve(true)
  }

  // Example of delete hook
  vm.$hmw_hook.add('delete-module', deleteACFModule)

  // Handle cloning of ACF modules
  vm.$hmw_hook.add('clone-row-module', cloneACFModule)
  vm.$hmw_hook.add('clone-section-module', cloneACFModule)
  vm.$hmw_hook.add('clone-acf-module', cloneACFModule)
  vm.$hmw_hook.add('before-paste', cloneACFModule)
}
