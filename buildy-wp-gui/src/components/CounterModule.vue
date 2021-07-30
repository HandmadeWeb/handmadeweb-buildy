<template>
  <settings-modal>
    <title-editor label="Title" path="content.title" />
    <attribute-editor
      label="Start Time"
      placeholder="Default: 0"
      path="content.timer.start"
    />
    <attribute-editor
      label="End Time"
      placeholder="Default: 0"
      path="content.timer.end"
    />
    <attribute-editor
      label="Duration"
      placeholder="Default: 3000"
      path="content.timer.duration"
    />
  </settings-modal>
</template>
<script>
export default {
  name: "counter-module",
  data: function () {
    return {
      icon: "ClockIcon",
    };
  },
};
</script>
