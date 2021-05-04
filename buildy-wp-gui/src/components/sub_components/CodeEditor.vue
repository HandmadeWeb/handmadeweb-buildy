<template>
  <div class="module module-settings">
    <span v-if="header" class="text-2xl block mb-4" v-text="header" />
    <textarea
      v-model="value"
      @change="change"
      class="code-input w-full p-4"
      rows="3"
    />
    <!-- <prism-editor :code="value" language="html" @change="change"></prism-editor> -->
  </div>
</template>
<script>
import { setDeep, getDeep } from "../../functions/objectHelpers";
// import PrismEditor from "vue-prism-editor";

// @ts-ignore
export default {
  name: "code-editor",
  data: function () {
    return {
      value: "",
    };
  },
  props: {
    path: String,
    header: String,
  },
  methods: {
    change(e) {
      this.value = e.target.value;
      setDeep(this.component, this.path, this.value);
      this.$emit("change", { data: this.value, path: this.path });
    },
  },
  components: {
    // PrismEditor
  },
  mounted() {
    let data = getDeep(this.component, this.path);

    if (data) {
      return (this.value = data);
    }
  },
  inject: ["component"],
};
</script>
