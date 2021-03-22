<template>
  <settings-modal>
    <title-editor label="Title" path="content.title" />
    <accordion path="content.tabs.items" />
  </settings-modal>
</template>
<script>
export default {
  name: "tab-module",
  data: function() {
    return {
      icon: "menuIcon"
    };
  }
};
</script>
