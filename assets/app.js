// import "./bootstrap.js";
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import "./styles/app.scss";

const $ = require("jquery");

require("bootstrap");

$(function () {
  console.log("using jquery!");
  AOS.init({
    duration: 1800,
  });

  document.querySelectorAll(".youtube-thumbnail").forEach(function (thumbnail) {
    thumbnail.addEventListener("click", function () {
      const videoId = thumbnail.getAttribute("data-video-id");
      const iframe = document.createElement("iframe");
      iframe.setAttribute("class", "h-100 w-100");
      iframe.setAttribute(
        "src",
        `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0showinfo=0`
      );
      iframe.setAttribute("frameborder", "0");
      iframe.setAttribute(
        "allow",
        "accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
      );
      iframe.setAttribute("allowfullscreen", "true");
      thumbnail.innerHTML = "";
      thumbnail.appendChild(iframe);
    });
  });

  $(".submit-contact-us").one("click", function () {
    const $btn = $(this);

    setTimeout(function () {
      $btn.find(".submit-loader.d-none").removeClass("d-none");
      $btn.find(".submit-text").addClass("d-none");
      $btn.attr("disabled", "true");
    }, 10); // allow native submit to proceed
  });
});
