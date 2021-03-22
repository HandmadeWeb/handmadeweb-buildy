export default {
  data: function () {
    return {
      isWordpress: window.wp,
      customMediaLibrary: null,
    }
  },
  methods: {
    initUploader() {
      if (this.isWordpress && window.wp.media) {
        this.customMediaLibrary = window.wp.media({
          // Accepts [ 'select', 'post', 'image', 'audio', 'video' ]
          // Determines what kind of library should be rendered.
          frame: 'select',
          // Modal title.
          title: "'Select Images',",
          // Enable/disable multiple select
          multiple: true,
          // Library wordpress query arguments.
          library: {
            order: 'DESC',
            // [ 'name', 'author', 'date', 'title', 'modified', 'uploadedTo', 'id', 'post__in', 'menuOrder' ]
            orderby: 'date',
            // mime type. e.g. 'image', 'image/jpeg'
            type: 'image',
            // Searches the attachment title.
            search: null,
            // Includes media only uploaded to the specified post (ID)
            uploadedTo: null // wp.media.view.settings.post.id (for current post ID)
          },
          button: {
            text: 'Done'
          }
        });

        this.$emit('mediaSelect');
      }
      if (this.customMediaLibrary) {
        this.customMediaLibrary.on("select", () => {
          var selectedImages = this.customMediaLibrary.state().get("selection");
          let selection = selectedImages.map((attachment) => {
            attachment = attachment.toJSON();
            let imageData = {
              url: attachment.url,
              id: attachment.id,
            };

            return imageData;
          });
          this.addImages(selection);
        });

        this.customMediaLibrary.on("open", () => {
          let selection = this.customMediaLibrary.state().get("selection");
          let images = this.component.content.gallery?.images || [this.component.content.image] || null;
          if (images) {
            images.forEach((image) => {
              if (image && typeof image === 'object') {
                let id = image.id || image.imageID
                let attachment = id && window.wp.media.attachment(id);
                if (attachment) {
                  attachment.fetch();
                  selection.add(attachment ? [attachment] : []);
                }
              }
            });
          }
        });
      }
    },
    openMediaLibrary() {
      if (this.customMediaLibrary) {
        this.customMediaLibrary.open()
      } else {
        this.initUploader();
        this.customMediaLibrary.open()
      }
    }
  },
  mounted() {
    this.initUploader();
  },
};