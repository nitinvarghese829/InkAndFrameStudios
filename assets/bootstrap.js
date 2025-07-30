import { startStimulusApp } from "@symfony/stimulus-bridge";
import HelloController from "./controllers/hello_controller.js";
import TinyMce from "./controllers/tinymce_controller.js";

const app = startStimulusApp();

console.log("Stimulus app started", app);
app.register("hello", HelloController);
app.register("tinymce", TinyMce);
