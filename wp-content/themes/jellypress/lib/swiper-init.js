function ezpzInitializeSwiper(){var e=document.querySelectorAll(".swiper");0<e.length&&e.forEach(e=>{var i={autoplay:{delay:3e3,pauseOnMouseEnter:!0},speed:400,slidesPerView:1,spaceBetween:16,loop:!0,focusableElements:"a, input, select, option, textarea, button, video, label",breakpoints:{600:{slidesPerView:1,spaceBetween:24},900:{slidesPerView:2,spaceBetween:32},1200:{slidesPerView:3,spaceBetween:32},1800:{slidesPerView:4,spaceBetween:32}}},s=["slidesPerView","slidesSM","slidesMD","slidesLG","slidesXL","delay"];if(!!e.getAttribute("data-swiper-options")){var t=JSON.parse(e.getAttribute("data-swiper-options"));for(const a in t)s.includes(a)?"delay"===a?i.autoplay.delay=t[a]:"slidesPerView"===a?i.slidesPerView=t[a]:"slidesSM"===a?i.breakpoints[600].slidesPerView=t[a]:"slidesMD"===a?i.breakpoints[900].slidesPerView=t[a]:"slidesLG"===a?i.breakpoints[1200].slidesPerView=t[a]:"slidesXL"===a&&(i.breakpoints[1800].slidesPerView=t[a]):(t.hasOwnProperty(a),i[a]=t[a])}var r=e.closest(".swiper-container")||e,l=r.querySelector(".swiper-pagination"),l=(l&&(i.pagination={el:l,type:"bullets",clickable:!0,dynamicBullets:!0,keyboard:{enabled:!0,onlyInViewport:!0}}),r.querySelector(".swiper-button-prev")),n=r.querySelector(".swiper-button-next"),n=(l&&n&&(i.navigation={nextEl:n,prevEl:l}),r.querySelector(".swiper-scrollbar"));n&&(i.scrollbar={el:n,draggable:!0}),"fade"===i.effect&&(i.fadeEffect={crossFade:!0}),"flip"===i.effect&&(i.flipEffect={slideShadows:!1}),new Swiper(e,i)})}window.acf?window.acf.addAction("render_block_preview",window.acf.addAction("render_block_preview",function(e,i){e[0].innerHTML.includes("swiper-container")&&ezpzInitializeSwiper()})):document.addEventListener("DOMContentLoaded",function(){ezpzInitializeSwiper()});