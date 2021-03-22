<template>
  <radio-buttons
    :label="`breakpoint-${path}`"
    hideLabel
    class="border-2 justify-center border-dashed py-2 pl-2 rounded"
    :selected="breakpoint"
    :key="`${component.id}-${path}-breakpoint-switcher-${breakpoint}`"
    @change="(val) => $emit('change', val)"
    options="xs, sm, md, lg, xl"
  />
</template>

<script>
export default {
  name: "breakpoint-switcher",
  props: {
    breakpoint: {
      type: String,
      default: "xs",
    },
    path: {
      type: String,
      required: true,
    },
  },
  inject: ["component"],
};
</script>
