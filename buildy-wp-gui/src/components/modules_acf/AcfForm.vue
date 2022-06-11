<template>
  <div ref="acf_module">
    <div
      id="acf-form-container"
      class="bg-white shadow rounded mb-6 p-4"
      v-if="formHTML">
      <div v-if="isLinked">
        <div
          class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4"
          role="alert">
          <p class="font-bold mb-1">WARNING!</p>
          <p class="mb-0">
            This module is linked as a global. This means it is displayed in
            another location. Be careful when editing this form as it will
            overwrite other modules with the same ID.
          </p>
        </div>
      </div>
      <div ref="acf_form" v-html="formHTML"></div>
      <div class="mt-4 pt-4 border-t" v-if="fieldGroups">
        <div>
          <strong>Post ID: </strong>
          <a
            class="underline"
            :href="`/wp-admin/post.php?post=${postID}&action=edit`"
            target="_blank"
            v-text="postID" />
        </div>
        <div>
          <strong>Template File: </strong>
          <a
            class="underline"
            :href="`/wp-admin/theme-editor.php?file=buildy-views/modules/acf-${parseInt(
              fieldGroups
            )}.blade.php`"
            target="_blank"
            v-text="
              `/buildy-views/modules/acf-${parseInt(fieldGroups)}.blade.php`
            " />
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { stripTrailingSlash } from '../../functions/helpers'
import { EventBus } from '../../EventBus'
import { setDeep, getDeep } from '../../functions/objectHelpers'
import { mapGetters } from 'vuex'

export default {
  name: 'acf-form',
  data: function () {
    return {
      original_post_id: '',
      postID: '',
      fieldGroups: '',
      isLinked: false,
      formHTML: '',
      setTitle:
        getDeep(this.component, 'content.acfForm.field_groups_title') ?? '',
    }
  },
  methods: {
    // On mounted(), check to see if existing form has been set and load data
    checkForm() {
      this.postID = getDeep(this.component, 'content.acfForm.post_id')
      this.fieldGroups = getDeep(this.component, 'content.acfForm.field_groups')
      if (this.fieldGroups) {
        EventBus.$emit('showSelect', false)
        this.loadForm(this.postID, this.fieldGroups, null)
      }
      // Prevent submission on 'enter' keybind
      var $formContainer = this.$el
      window
        .jQuery($formContainer)
        .on('keypress keydown keyup', 'form', function (e) {
          if (e.keyCode == 13) {
            e.preventDefault()
          }
        })
    },
    // Function to load new / existing form into module
    async loadForm(postID = null, fieldIDs, isLinked = false) {
      // Remove existing form HTML
      this.formHTML = ''
      // Check that form is ready to be loaded and data is available
      if (window.global_vars && fieldIDs != 'None') {
        try {
          EventBus.$emit('isLoading', true)
          // Rest API endpoint to load form data
          let res = await fetch(
            `${stripTrailingSlash(
              window.global_vars.rest_api_base
            )}/bmcb/v1/acf_form/post_id=${postID}/field_groups=${fieldIDs}/is_linked=${isLinked}`,
            {
              headers: {
                'X-WP-Nonce': window.global_vars.rest_nonce,
              },
            }
          )
          let data = await res.json()
          // Update formHTML with returned data
          this.formHTML = data.body.form
          this.isLinked = data.body.is_linked ?? false

          setDeep(this.component, 'content.acfForm.is_linked', this.isLinked)
          setDeep(
            this.component,
            'icon',
            this.isLinked ? 'LockIcon' : 'LayoutIcon'
          )

          if (this.isLinked) {
            EventBus.$emit('moduleLinked', this.postID, this.isLinked)
          }

          // On nextTick, trigger ACF for validation and rendering
          this.$nextTick(() => {
            window.acf.do_action('append', window.jQuery('#acf-form-container'))
            EventBus.$emit('isLoading', false)
          })
        } catch {
          this.formHTML = 'Something went wrong. Please try again!'
        }
      } else {
        this.formHTML = ''
      }
    },

    // Function to create formData and submit form for processing
    submitForm(resolve, reject) {
      // Convert to window.jQuery for ACF validation
      let form = this.$refs.acf_form.querySelector('form')

      // If form does not exist, do not continue and close modal
      if (!form) {
        // Close modal
        this.$modal.hide(this.component.id)
        return
      }

      EventBus.$emit('isLoading', true)

      const args = {
        form: window.jQuery(form),
        reset: false,
        failure: function () {
          EventBus.$emit('isLoading', false)
          window.jQuery('.settings-modal').scrollTop(0)
          resolve()
        },
        success: async (form) => {
          // Create form data
          const formData = new FormData(form[0])
          // Append WP AJAX action
          formData.append('action', 'create_acf_module')
          // Append WP AJAX nonce
          formData.append('nonce', window.global_vars.nonce)
          // Append linked status - Used to prevent globals from displaying form
          formData.append('is_linked', this.isLinked)
          // Append the current post ID (wordpress editing page) to form data so we can save it to post meta of module
          formData.append('current_post_id', this.post_id)

          // Append post title (for new posts)
          if (this.setTitle) {
            formData.append('acf[_post_title]', this.setTitle)
          }
          // Lock ACF form (prevents updates whilst AJAX is running)
          window.acf.lockForm(form)
          const params = new URLSearchParams(formData)

          try {
            const res = await fetch(window.ajaxurl, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-WP-Nonce': window.global_vars.nonce,
              },
              body: params,
            })

            const { data, success } = await res.json()

            if (!success) {
              reject('YOU SHALL NOT PASS!')
              throw new Error('Issue with ACF form submission')
            }
            const postID = getDeep(this.component, 'content.acfForm.post_id')
            // If no post ID has been set (new module), set content (post ID and field groups) on ACF module
            if (!postID && postID !== false) {
              setDeep(this.component, 'content.acfForm', {
                field_groups_title:
                  this.component.content.acfForm.field_groups_title,
                ...data,
                original_post_id: this.post_id,
                is_linked: false,
              })

              // Update admin label
              this.updateAdminLabel(data.post_id)
            }
            // Unlock ACF form
            window.acf.unlockForm(form)
            EventBus.$emit('isLoading', false)

            // Close modal
            resolve()
          } catch (error) {
            reject(error)
            console.log(error)
          }
        },
      }
      // Validate ACF Form
      window.acf.validateForm(args)
    },
    // Function to update Admin Label
    updateAdminLabel(postID) {
      if (this.component.options.admin_label === 'Custom Fields') {
        this.component.options.admin_label =
          'Custom Fields - ' + this.setTitle + ' - ' + postID
      }
    },
  },
  computed: {
    ...mapGetters(['post_id']),
  },
  mounted() {
    this.$hmw_hook.add('modal-save', this.submitForm)
    // Event to prompt user to close modal
    EventBus.$on('before-close', (e) => {
      confirm(
        'Are you sure you want to close this module? If you have made any changes to the form remember to click Save.'
      )
        ? true
        : e.cancel()
    })

    // Event to load existing form into ACF module
    EventBus.$on('loadExisting', (data) => {
      this.postID = data.post_id
      this.fieldGroups = data.field_groups

      // When linking an existing one, original post id becomes the current post id
      setDeep(this.component, 'content.acfForm', {
        ...data,
        original_post_id: this.post_id,
      })

      // Load in existing form, the "true" parameter will flag the form as linked
      this.loadForm(this.postID, this.fieldGroups, true)
      this.updateAdminLabel(this.postID)
    })
    // Event to create new form
    EventBus.$on('createForm', (fieldIDs) => {
      this.loadForm(null, fieldIDs, null)
    })
    // Event to set title for post title and admin label
    EventBus.$on('setTitle', (postTitle) => {
      setDeep(this.component, 'content.acfForm.field_groups_title', postTitle)
      this.setTitle = postTitle
    })
    // Clear form HTML
    EventBus.$on('clearFormHTML', () => {
      this.formHTML = ''
    })
    // On mounted(), check to see if existing form has been set and load data
    this.checkForm()
  },
  destroyed() {
    this.$hmw_hook.remove('modal-save', this.submitForm)
    EventBus.$off('before-close')
    EventBus.$off('loadExisting')
    EventBus.$off('createForm')
    EventBus.$off('setTitle')
    EventBus.$off('clearFormHTML')
  },
  inject: ['component'],
}
</script>

<style>
.acf-form-submit {
  display: none;
}
</style>
