import { Controller } from "@hotwired/stimulus";
import tinymce from "tinymce/tinymce";
import "tinymce/icons/default";

// Optional: Plugins
import "tinymce/plugins/link";
import "tinymce/plugins/lists";
import "tinymce/plugins/code";
/*
 * The following line makes this controller "lazy": it won't be downloaded until needed
 * See https://github.com/symfony/stimulus-bridge#lazy-controllers
 */
/* stimulusFetch: 'lazy' */
export default class extends Controller {
  connect() {
    console.log("Tinymce controller connected", this.element);
    requestAnimationFrame(() => {
      this.createEditor();
    });
  }

  createEditor() {
    requestAnimationFrame(() => {
      const currentElementId = this.element.getAttribute("id");

      setTimeout(() => {
        tinymce.init({
          selector: "textarea#" + currentElementId,
          license_key: "gpl",
          menubar: false,
          plugins: "link image code paste table lists",
          toolbar:
            "styles | bold italic | bullist numlist | alignleft aligncenter alignright alignjustify | link image media  | outdent indent | table | undo redo ",
          image_title: true, // shows the title (alt) field
          automatic_uploads: true,
          file_picker_types: "image",

          // Optional: force showing dialog when inserting images
          images_reuse_filename: true,
          paste_data_images: true,
          branding: false,
          setup: (editor) => {
            editor.on("change", () => {
              editor.save(); // Syncs content back to the <textarea>
            });
          },
        });
      }, 200);
    });
  }
}
