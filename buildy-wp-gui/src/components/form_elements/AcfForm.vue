<template>
  <div id="acf-form-container" ref="acf_form" v-if="formHTML" v-html="formHTML"></div>
</template>
<script>
import { stripTrailingSlash } from "../../functions/helpers";
import { EventBus } from "../../EventBus";
import { setDeep, getDeep } from "../../functions/objectHelpers";

export default {
  name: "acf-module",
  data: function() {
    return {
      formHTML: "",
    };
  },
  methods: {
    async checkForm() {
      const post_id = getDeep(this.component, "content.acfForm.post_id")
      const field_groups = getDeep(this.component, "content.acfForm.field_groups")
      if (post_id ?? false) {
        EventBus.$emit("showSelect", false)
        this.loadForm(post_id, field_groups)
      }
    },
    async loadForm(postID = null, fieldIDs) {
      EventBus.$emit("waitToSave", true);
      this.formHTML = ''
      if (window.global_vars && fieldIDs != 'None') {
        try {
          EventBus.$emit("isLoading", true);
          let res = await fetch(
            `${stripTrailingSlash(window.global_vars.rest_api_base)}/bmcb/v1/acf_form/post_id=${postID}/field_groups=${fieldIDs}`
          )
          let data = await res.json()
          this.formHTML = data
          this.$nextTick(()=>{
            window.acf.do_action('append', jQuery('#acf-form-container'))
            EventBus.$emit("isLoading", false)
          })
        } catch {
          this.formHTML = 'Something went wrong. Please try again!'
        }
      } else {
        this.formHTML = ''
      }
    },
    async submitForm() {
      EventBus.$emit("isLoading", true);
      var form = this.$refs.acf_form;
      var $form = jQuery(form).find('form');

      const self = this

      const args = {
        form: $form,
        // reset the form after submit
        reset: false,
                
        // loading callback
        loading: function ($form) {},
                
        // complete callback
        complete: function ($form) {},
                
        // failure callback
        failure: function ($form) {
          EventBus.$emit("isLoading", false);
          jQuery(".settings-modal").scrollTop(0);
        },

        success: function ($form) {
          var formData = new FormData( $form[0] );
          formData.append("action", "create_acf_module");   
          formData.append("nonce", window.global_vars.nonce);
          window.acf.lockForm( $form );

          jQuery.ajax({
              type: 'post',
              url: window.ajaxurl,
              data: formData,
              cache: false, 
              contentType: false,
              processData: false,
              success: (response) => {
                //console.log(response.data)
                if( !getDeep(self.component, "content.acfForm.post_id") ) {
                  setDeep(self.component, "content.acfForm", response.data);
                }
                window.acf.unlockForm( $form );
                EventBus.$emit("isLoading", false);
                EventBus.$emit("waitToSave", false);
                EventBus.$emit("doSave");
              },
              error: function (xhr, textStatus, error) {
                console.log('Error:' + xhr, textStatus, error);
              }
          })
        }          
      }
      window.acf.validateForm(args);
    }
  },
  mounted() {
    EventBus.$on('createForm', (e) => {
      this.loadForm(null, e)
    })
    EventBus.$on('saveAll', (e) => {
      if (e === this.component.id) {
        this.submitForm()
      }
    })
    this.checkForm()
  },
  inject: ["component"],
};
</script>

<style>
.acf-form-submit {
  display: none;
}
</style>