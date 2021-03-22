<template>
  <settings-modal>
    <iframe
    class="mb-6"
      v-if="location"
      width="100%"
      height="350"
      frameborder="0" style="border:0"
      :src="`https://www.google.com/maps/embed/v1/place?key=AIzaSyAPpSl8H3EEeGZyrOMPVqo0TsbSytksAvQ&q=${location}`" allowfullscreen>
    </iframe>     
    <attribute-editor path="content.map.location" label="Address"></attribute-editor>
    <attribute-editor label="Title" path="content.title"></attribute-editor>
    <radio-buttons label="H-Level" options="H1,H2,H3,H4,H5" path="content.radio.heading_level" />
    <rich-text path="content.body" />
  </settings-modal> 
</template>
<script>
import { getDeep } from '../functions/objectHelpers'
  export default { 
    name: 'map-module',
    data: function() {
      return {
        icon: 'MapIcon',
      }
    },
    props: {
      component: Object
    },
    computed: {
      location() {
        return getDeep(this.component, 'content.map.location') || null
      }
    },
  }
</script>