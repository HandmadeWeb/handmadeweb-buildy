<template>
  <settings-modal>
    <title-editor label="Title" path="content.title" />
    <attribute-editor
      path="content.post.postType"
      placeholder="Default is post"
      label="Post Type"
    ></attribute-editor>
    <attribute-editor
      path="content.post.perPage"
      placeholder="How many to display per page"
      label="Per Page"
    ></attribute-editor>
    <attribute-editor
      path="content.post.columns"
      placeholder="How many columns per row"
      label="Column Count"
    ></attribute-editor>
    <attribute-editor
      path="content.post.offset"
      placeholder="If you want to skip past a few posts"
      label="Post Offset"
    ></attribute-editor>
    <attribute-editor
      path="content.post.includeCats"
      placeholder="32, 20, 21"
      label="Include Categories"
    ></attribute-editor>
    <toggle-switch
      path="content.post.enablePagination"
      label="Enable Pagination"
    ></toggle-switch>
  </settings-modal>
</template>
<script>
export default {
  name: "postgrid-module",
  data: function () {
    return {
      icon: "GridIcon",
      keepInBounds: true,
      type: "blurb-module",
      imageSrc: null,
    };
  },
};
</script>
