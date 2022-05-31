<template>
  <div
    id="acf-form-container"
    class="bg-white shadow rounded mb-6 p-4"
    v-if="formHTML"
  >
    <div ref="acf_form" v-html="formHTML"></div>
    <div class="mt-4 pt-4 border-t" v-if="fieldGroups">
      <div>
        <strong>Post ID:</strong>
        <a
          :href="`http://default.local/wp-admin/post.php?post=${postID}&action=edit`"
          target="_blank"
          v-text="postID"
        />
      </div>
      <div>
        <strong>Template File:</strong> /buildy-views/modules/acf-{{
          parseInt(fieldGroups)
        }}.blade.php
      </div>
    </div>
  </div>
</template>
<script>
import { stripTrailingSlash } from "../../functions/helpers";
import { EventBus } from "../../EventBus";
import { setDeep, getDeep } from "../../functions/objectHelpers";

export default {
  name: "acf-module",
  data: function () {
    return {
      postID: "",
      fieldGroups: "",
      formHTML: "",
      setTitle: "",
    };
  },
  methods: {
    // On mounted(), check to see if existing form has been set and load data
    checkForm() {
      this.postID = getDeep(this.component, "content.acfForm.post_id");
      this.fieldGroups = getDeep(
        this.component,
        "content.acfForm.field_groups"
      );
      if (this.postID) {
        EventBus.$emit("showSelect", false);
        this.loadForm(this.postID, this.fieldGroups);
      }
    },
    // Function to load new / existing form into module
    async loadForm(postID = null, fieldIDs) {
      // Disables modal from closing prior to AJAX submission
      EventBus.$emit("waitToSave", true);
      // Remove existing form HTML
      this.formHTML = "";
      // Check that form is ready to be loaded and data is available
      if (window.global_vars && fieldIDs != "None") {
        try {
          EventBus.$emit("isLoading", true);
          // Rest API endpoint to load form data
          let res = await fetch(
            `${stripTrailingSlash(
              window.global_vars.rest_api_base
            )}/bmcb/v1/acf_form/post_id=${postID}/field_groups=${fieldIDs}`
          );
          let data = await res.json();
          // Update formHTML with returned data
          this.formHTML = data;
          // On nextTick, trigger ACF for validation and rendering
          this.$nextTick(() => {
            window.acf.do_action("append", jQuery("#acf-form-container"));
            EventBus.$emit("isLoading", false);
          });
        } catch {
          this.formHTML = "Something went wrong. Please try again!";
        }
      } else {
        this.formHTML = "";
      }
    },

    // Function to create formData and submit form for processing
    submitForm() {
      EventBus.$emit("isLoading", true);
      // Target the current form
      var form = this.$refs.acf_form;
      // Convert to jQuery for ACF validation
      var $form = jQuery(form).find("form");

      const self = this;

      const args = {
        form: $form,
        reset: false,
        loading: function ($form) {},
        complete: function ($form) {},
        failure: function ($form) {
          EventBus.$emit("isLoading", false);
          jQuery(".settings-modal").scrollTop(0);
        },
        success: function ($form) {
          // Create form data
          var formData = new FormData($form[0]);
          // Append WP AJAX action
          formData.append("action", "create_acf_module");
          // Append WP AJAX nonce
          formData.append("nonce", window.global_vars.nonce);
          // Append post title (for new posts)
          if (self.setTitle) {
            formData.append("acf[_post_title]", self.setTitle);
          }
          // Lock ACF form (prevents updates whilst AJAX is running)
          window.acf.lockForm($form);
          // Begin AJAX Request
          jQuery.ajax({
            type: "post",
            url: window.ajaxurl,
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: (response) => {
              // If no post ID has been set (new module), set content (post ID and field groups) on ACF module
              if (!getDeep(self.component, "content.acfForm.post_id")) {
                setDeep(self.component, "content.acfForm", response.data);
                // Update admin label
                self.updateAdminLabel(response.data.post_id);
              }
              // Unlock ACF form
              window.acf.unlockForm($form);
              EventBus.$emit("isLoading", false);
              // Re-enable modal closing
              EventBus.$emit("waitToSave", false);
              // Close modal
              EventBus.$emit("doSave");
            },
            error: function (xhr, textStatus, error) {
              console.log({ Error: xhr, textStatus, error });
            },
          });
        },
      };
      // Validate ACF Form
      window.acf.validateForm(args);
    },
    // Function to update Admin Label
    updateAdminLabel(postID) {
      if (this.component.options.admin_label === "Acf") {
        this.component.options.admin_label =
          "ACF - " + this.setTitle + " - " + postID;
      }
    },
  },
  mounted() {
    // Event to prompt user to close modal
    EventBus.$on("before-close", (e) => {
      confirm(
        "Are you sure you want to close this module? If you have made any changes to the form remember to click Save."
      )
        ? true
        : e.cancel();
    });
    // Event to load existing form into ACF module
    EventBus.$on("loadExisting", (data) => {
      this.postID = data.post_id;
      this.fieldGroups = data.field_groups;
      setDeep(this.component, "content.acfForm", data);
      this.loadForm(this.postID, this.fieldGroups);
      this.updateAdminLabel(this.postID);
    });
    // Event to create new form
    EventBus.$on("createForm", (fieldIDs) => {
      this.loadForm(null, fieldIDs);
    });
    // Event to set title for post title and admin label
    EventBus.$on("setTitle", (postTitle) => {
      this.setTitle = postTitle;
    });
    // Event to save all - Check if ID matches, disable prompt and submit form
    EventBus.$on("saveAll", (e) => {
      if (e === this.component.id) {
        EventBus.$off("before-close");
        this.submitForm();
      }
    });
    // Clear form HTML
    EventBus.$on("clearFormHTML", () => {
      this.formHTML = "";
    });
    // On mounted(), check to see if existing form has been set and load data
    this.checkForm();
  },
  destroyed() {
    EventBus.$off("before-close");
    EventBus.$off("loadExisting");
    EventBus.$off("createForm");
    EventBus.$off("setTitle");
    EventBus.$off("saveAll");
    EventBus.$off("clearFormHTML");
  },
  inject: ["component"],
};
</script>

<style>
.acf-form-submit {
  display: none;
}
</style>
