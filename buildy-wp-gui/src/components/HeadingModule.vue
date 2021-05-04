<template>
  <settings-modal>
    <title-editor label="Title" path="content.title" />
  </settings-modal>
</template>
<script>
export default {
  name: "heading-module",
  data: function () {
    return {
      icon: "BoldIcon",
    };
  },
};
</script>
