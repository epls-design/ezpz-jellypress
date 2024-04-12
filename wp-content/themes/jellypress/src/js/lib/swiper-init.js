/**
 * SwiperJS Initialization
 * This Javascript file initialises any element with .swiper class using SwiperJS
 * If the element has a data-swiper-options attribute, it will merge the default options with the data-swiper-options values
 * If the element has a .swiper-pagination elem, it will add pagination options
 * If the element has .swiper-button-prev and .swiper-button-next elems, it will add navigation options
 * If the element has a .swiper-scrollbar elem, it will add scrollbar options
 *
 * @see https://swiperjs.com/swiper-api
 */

function ezpzInitializeSwiper() {
  // Get all elems with .swiper
  const swipers = document.querySelectorAll(".swiper");
  if (swipers.length > 0) {
    swipers.forEach((swiper) => {
      // Default Options
      // NOTE: You may need to adjust these to suit your project, particularly the breakpoints
      const swiperOpts = {
        autoplay: {
          delay: 3000,
          pauseOnMouseEnter: true,
        },
        speed: 400,
        slidesPerView: 1,
        spaceBetween: 16,
        loop: true,
        focusableElements:
          "a, input, select, option, textarea, button, video, label",
        breakpoints: {
          600: {
            slidesPerView: 1,
            spaceBetween: 24,
          },
          900: {
            slidesPerView: 2,
            spaceBetween: 32,
          },
          1200: {
            slidesPerView: 3,
            spaceBetween: 32,
          },
          1800: {
            slidesPerView: 4,
            spaceBetween: 32,
          },
        },
      };

      /**
       * Check if the element has a data-swiper-options attribute, if so merge into the
       * swiperDefaults replacing the defaults with the data-swiper-options values
       */
      let hasDataOpts = swiper.getAttribute("data-swiper-options")
        ? true
        : false;

      let swiperProtectedKeys = [
        "slidesPerView",
        "slidesSM",
        "slidesMD",
        "slidesLG",
        "slidesXL",
        "delay",
      ];

      if (hasDataOpts) {
        const swiperDataOpts = JSON.parse(
          swiper.getAttribute("data-swiper-options")
        );
        for (const key in swiperDataOpts) {
          if (swiperProtectedKeys.includes(key)) {
            // If key = delay add it to autoplay object
            if (key === "delay") {
              swiperOpts.autoplay.delay = swiperDataOpts[key];
            } else if (key === "slidesPerView") {
              swiperOpts.slidesPerView = swiperDataOpts[key];
            } else if (key === "slidesSM") {
              swiperOpts.breakpoints[600].slidesPerView = swiperDataOpts[key];
            } else if (key === "slidesMD") {
              swiperOpts.breakpoints[900].slidesPerView = swiperDataOpts[key];
            } else if (key === "slidesLG") {
              swiperOpts.breakpoints[1200].slidesPerView = swiperDataOpts[key];
            } else if (key === "slidesXL") {
              swiperOpts.breakpoints[1800].slidesPerView = swiperDataOpts[key];
            }

            continue;
          }

          if (swiperDataOpts.hasOwnProperty(key)) {
            swiperOpts[key] = swiperDataOpts[key];
          } else {
            swiperOpts[key] = swiperDataOpts[key];
          }
        }
      }

      // If container is not found set the container to be the swiper element
      let closestContainer = swiper.closest(".swiper-container") || swiper;

      /**
       * If the swiper has a .swiper-pagination elem, add pagination options
       * @see https://swiperjs.com/swiper-api#pagination
       */

      const swiperPagination =
        closestContainer.querySelector(".swiper-pagination");
      if (swiperPagination) {
        swiperOpts.pagination = {
          el: swiperPagination,
          type: "bullets",
          clickable: true,
          dynamicBullets: true,
          keyboard: {
            enabled: true,
            onlyInViewport: true,
          },
        };
      }

      /**
       * If the swiper has .swiper-button-prev and .swiper-button-next elems, add navigation options
       * @see https://swiperjs.com/swiper-api#navigation
       */
      const swiperButtonPrev = closestContainer.querySelector(
        ".swiper-button-prev"
      );
      const swiperButtonNext = closestContainer.querySelector(
        ".swiper-button-next"
      );
      if (swiperButtonPrev && swiperButtonNext) {
        swiperOpts.navigation = {
          nextEl: swiperButtonNext,
          prevEl: swiperButtonPrev,
        };
      }

      /**
       * If the swiper has .swiper-scrollbar elem, add scrollbar options
       * @see https://swiperjs.com/swiper-api#scrollbar
       */
      const swiperScrollbar =
        closestContainer.querySelector(".swiper-scrollbar");
      if (swiperScrollbar) {
        swiperOpts.scrollbar = {
          el: swiperScrollbar,
          draggable: true,
        };
      }

      /**
       * If swiperOpts.effect == 'fade' add fade effect
       * @see https://swiperjs.com/swiper-api#fade-effect
       */
      if (swiperOpts.effect === "fade") {
        swiperOpts.fadeEffect = {
          crossFade: true,
        };
      }

      /**
       * If swiperOpts.effect == 'flip' remove slideShadows
       */
      if (swiperOpts.effect === "flip") {
        swiperOpts.flipEffect = {
          slideShadows: false,
        };
      }

      // If vertical, force to one slide per view. Note: all slides need to be the same height - enforced by CSS
      if (swiperOpts.direction === "vertical") {
        swiperOpts.slidesPerView = 1;
        swiperOpts.spaceBetween = 0;
        swiperOpts.autoHeight = true;
        swiperOpts.breakpoints[600].slidesPerView = 1;
        swiperOpts.breakpoints[900].slidesPerView = 1;
        swiperOpts.breakpoints[1200].slidesPerView = 1;
        swiperOpts.breakpoints[1800].slidesPerView = 1;
      }

      // instantiate swiper
      const swiperInstance = new Swiper(swiper, swiperOpts);
    });
  }
}

// If we are in the editor, run on render_block_preview
if (window.acf) {
  window.acf.addAction("render_block_preview", function ($elem, blockDetails) {
    // Check if $elem[0].innerHTML contains "swiper-container" and if it does reinitialize Swiper
    if ($elem[0].innerHTML.includes("swiper-container")) {
      ezpzInitializeSwiper();
    }
  });
} else {
  // Otherwise run on DOMContentLoaded for the front end
  document.addEventListener("DOMContentLoaded", function () {
    ezpzInitializeSwiper();
  });
}
