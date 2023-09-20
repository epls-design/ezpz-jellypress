/**
 * Photoswipe JS
 * @see https://photoswipe.com/getting-started/
 */

import PhotoSwipeLightbox from "/wp-content/themes/jellypress/lib/photoswipe-lightbox.esm.min.js";
import PhotoSwipe from "/wp-content/themes/jellypress/lib/photoswipe.esm.min.js";

let galleries = document.querySelectorAll(".gallery");

// If there are singleGalleries on the page, initialize PhotoSwipe
if (galleries.length > 0) {
  galleries.forEach((item) => {
    let id = item.id;
    id = "#" + id;
    console.log(id);
    new PhotoSwipeLightbox({
      gallery: id,
      childSelector: "a",
      pswpModule: PhotoSwipe,
      bgOpacity: 0.9,
      wheelToZoom: true,
    }).init();
  });
}
