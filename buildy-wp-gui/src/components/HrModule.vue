<template>
  <settings-modal>
    <attribute-editor path="inline.height" label="Height"></attribute-editor>
    <color-picker label="Color" path="inline.backgroundColor" />
  </settings-modal>
</template>
<script>
export default {
  name: "hr-module",
  data: function () {
    return {
      alias: "Divider",
      icon: "MinusIcon",
    };
  },
};
</script>
