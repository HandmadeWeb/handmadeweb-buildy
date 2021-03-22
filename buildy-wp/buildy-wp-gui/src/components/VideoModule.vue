<template>
  <settings-modal>
    <title-editor label="Title" path="content.title" />
    <div
      v-if="imageSrc"
      class="flex w-full pb-6 pl-32 pt-2 relative cursor-pointer items-center justify-center image-selector"
      :class="[imageSrc ? 'hasImage' : 'empty']"
    >
      <img
        :id="'img-' + component.id"
        class="image-preview pl-4 object-cover"
        :src="imageSrc"
      />
    </div>
    <attribute-editor
      path="content.youtube.video_url"
      label="Video Url"
      @attributeChange="getThumb"
    ></attribute-editor>
    <attribute-editor
      path="content.youtube.video_width"
      label="Video Width"
    ></attribute-editor>
    <attribute-editor
      path="content.youtube.video_height"
      label="Video Height"
    ></attribute-editor>
    <attribute-editor
      path="content.youtube.allow_params"
      label="Allow Params"
    ></attribute-editor>
  </settings-modal>
</template>
<script>
import { getDeep } from "../functions/objectHelpers";
export default {
  name: "video-module",
  data: function() {
    return {
      icon: "PlayCircleIcon",
      keepInBounds: true,
      type: "blurb-module",
      imageSrc: null
    };
  },
  props: {
    hidecontrols: Boolean
  },
  methods: {
    getQueryString(field, url) {
      var href = url ? url : window.location.href;
      var reg = new RegExp("[?&]" + field + "=([^&#]*)", "i");
      var string = reg.exec(href);
      return string ? string[1] : null;
    },
    getThumb(val) {
      const url = val || getDeep(this.component, "content.youtube.video_url");
      if (!url) {
        return false;
      }
      const video_id = this.getQueryString("v", url);
      const thumbnail = `https://img.youtube.com/vi/${video_id}/hqdefault.jpg`;
      this.imageSrc = thumbnail;
      return video_id ? thumbnail : null;
    }
  },
  mounted() {
    this.imageSrc = this.getThumb();
  }
};
</script>
