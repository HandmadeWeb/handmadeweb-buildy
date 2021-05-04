<template>
  <settings-modal>
    <title-editor label="Title" path="content.title" />
    <code-editor path="content.body" header="Edit Content:" />
  </settings-modal>
</template>
<script>
export default {
  name: "code-module",
  data: function() {
    return {
      icon: "CodeIcon"
    };
  }
};
</script>
