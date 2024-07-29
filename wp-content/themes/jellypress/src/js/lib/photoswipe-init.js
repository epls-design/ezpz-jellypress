/**
 * Photoswipe JS
 * @see https://photoswipe.com/getting-started/
 */

import PhotoSwipeLightbox from "/wp-content/themes/jellypress/lib/photoswipe-lightbox.esm.min.js";
import PhotoSwipe from "/wp-content/themes/jellypress/lib/photoswipe.esm.min.js";

let galleries = document.querySelectorAll(".gallery");

// If there are singleGalleries on the page, initialize PhotoSwipe
if (galleries.length > 0) {
  // FORCIBLY HIDE THE WORDPRESS LIGHTBOX STYLES
  // This is because we use our own, and Wordpress does not provide a way to disable their lightbox styles
  let lightboxOverlay = document.querySelector(".wp-lightbox-overlay");
  if (lightboxOverlay) {
    lightboxOverlay.remove();
  }

  galleries.forEach((item) => {
    let id = item.id;
    id = "#" + id;
    new PhotoSwipeLightbox({
      gallery: id,
      childSelector: "a",
      pswpModule: PhotoSwipe,
      bgOpacity: 0.9,
      wheelToZoom: true,
    }).init();
  });
}
