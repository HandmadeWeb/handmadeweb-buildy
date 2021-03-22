<template>
  <settings-modal>
    <title-editor label="Title" path="content.title" />
    <accordion path="content.slider.items" isSlider />
    <fieldset slot="options" class="field-group slider-custom-options mt-6">
      <legend class="uppercase text-sm px-4">Slider options</legend>
      <attribute-editor
        label="Interval"
        placeholder="Time between slide changes - Default: 5000"
        path="options.slider.interval"
      />
      <attribute-editor
        label="Duration"
        placeholder="Duration of the slide animation - Default: 200"
        path="options.slider.duration"
      />
      <attribute-editor
        label="Easing"
        placeholder="Default: ease-out"
        path="options.slider.easing"
      />
      <attribute-editor
        label="Per Page"
        placeholder="Default: 1"
        path="options.slider.perPage"
      />
      <attribute-editor
        label="Start Index"
        placeholder="Default: 0"
        path="options.slider.startIndex"
      />
      <attribute-editor
        label="Threshold"
        placeholder="Default: 20"
        path="options.slider.threshold"
      />
      <toggle-switch
        label="Autoplay"
        :status="
          component.options.slider &&
          typeof component.options.slider.autoplay !== 'undefined'
            ? component.options.slider.autoplay
            : true
        "
        path="options.slider.autoplay"
      ></toggle-switch>
      <toggle-switch
        label="Draggable"
        :status="
          component.options.slider &&
          typeof component.options.slider.draggable !== 'undefined'
            ? component.options.slider.draggable
            : true
        "
        path="options.slider.draggable"
      ></toggle-switch>
      <toggle-switch
        label="Loop"
        :status="
          component.options.slider &&
          typeof component.options.slider.loop !== 'undefined'
            ? component.options.slider.loop
            : true
        "
        path="options.slider.loop"
      ></toggle-switch>
      <toggle-switch
        label="Right to left?"
        :status="component.options.slider && component.options.slider.rtl"
        path="options.slider.rtl"
      ></toggle-switch>
      <toggle-switch
        label="Arrow Navigation"
        :status="
          component.options.slider &&
          typeof component.options.slider.arrow_nav !== 'undefined'
            ? component.options.slider.arrow_nav
            : true
        "
        path="options.slider.arrow_nav"
      ></toggle-switch>
      <toggle-switch
        label="Dot Navigation"
        :status="
          component.options.slider &&
          typeof component.options.slider.paginationDots !== 'undefined'
            ? component.options.slider.paginationDots
            : true
        "
        path="options.slider.paginationDots"
      ></toggle-switch>
    </fieldset>
  </settings-modal>
</template>
<script>
export default {
  name: "slider-module",
  data: function() {
    return {
      icon: "SlidersIcon"
    };
  },
  mounted() {
    console.log(typeof this.component.options.auto_play !== "undefined");
  },
  props: {
    component: Object
  }
};
</script>
