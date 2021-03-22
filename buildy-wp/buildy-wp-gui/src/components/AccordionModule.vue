<template>
  <settings-modal>
    <title-editor label="Title" path="content.title" />
    <accordion path="content.accordion.items" />
    <toggle-switch
      class="mt-6"
      label="Allow only 1 active at a time?"
      path="options.isToggle"
    />
  </settings-modal>
</template>
<script>
export default {
  name: "accordion-module",
  data: function() {
    return {
      icon: "menuIcon"
    };
  }
};
</script>
