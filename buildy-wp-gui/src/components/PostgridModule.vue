<template>
  <settings-modal>
    <title-editor label="Title" path="content.title" />
    <div class="flex -mx-2 items-center">
      <attribute-editor
        class="px-2 flex-grow"
        path="content.post.postType"
        placeholder="Default is post"
        label="Post Type"
      ></attribute-editor>
      <attribute-editor
        class="px-2 flex-grow"
        path="content.post.offset"
        placeholder="1, 2 skip a few..."
        label="Post Offset"
      ></attribute-editor>
    </div>
    <div class="flex -mx-2 items-center">
      <attribute-editor
        class="px-2 flex-grow"
        path="content.post.perPage"
        placeholder="How many to display per page"
        label="Per Page"
      ></attribute-editor>
      <attribute-editor
        class="px-2 flex-grow"
        path="content.post.columns"
        placeholder="How many columns per row"
        label="Column Count"
      ></attribute-editor>
    </div>
    <div class="flex -mx-2 items-center">
      <attribute-editor
        class="px-2 flex-grow"
        path="content.post.includeCats"
        placeholder="32, 20, 21"
        label="Include Categories"
      ></attribute-editor>
      <attribute-editor
        class="px-2 flex-grow"
        path="content.post.excludeCats"
        placeholder="54, 30, 19"
        label="Exclude Categories"
      ></attribute-editor>
    </div>
    <div class="flex -mx-2 items-center">
      <attribute-editor
        class="px-2 flex-grow"
        path="content.post.orderby"
        placeholder="default menu_order"
        label="Order By"
      ></attribute-editor>
      <attribute-editor
        class="px-2 flex-grow"
        path="content.post.order"
        placeholder="default ASC"
        label="Order"
      ></attribute-editor>
    </div>
    <div class="flex -mx-2 items-center">
      <attribute-editor
        class="px-2 flex-grow"
        path="content.post.featuredKey"
        placeholder="The meta_key e.g featured_service"
        label="Featured Key (if only wanting to show featured posts)"
      ></attribute-editor>
    </div>
    <toggle-switch
      path="content.post.paginationEnabled"
      label="Enable Pagination"
    ></toggle-switch>
    <select-box
      v-if="pagination"
      class="px-2 flex-1"
      label="Pagination Type"
      path="content.post.paginationType"
      options="default, loadmore"
    />
    <div v-if="pagination && isLoadmore">
      <attribute-editor
        path="content.post.contentTemplate"
        placeholder="E.g for template-parts/content-services = just: 'content-services'"
        label="Template for load more"
      ></attribute-editor>
      <select-box
        class="px-2 flex-1"
        label="Pagination Trigger"
        path="content.post.paginationTrigger"
        options="click, scroll"
      />
    </div>
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
  computed: {
    pagination() {
      return this.component.content.post?.paginationEnabled;
    },
    isLoadmore() {
      return this.component.content.post?.paginationType === "loadmore";
    },
  },
  inject: ["component"],
};
</script>
