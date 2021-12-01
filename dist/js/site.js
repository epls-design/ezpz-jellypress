// Note: These should match the values set in scss. Currently it is a manual process to keep them in sync
var breakPoints = {
  navBar: 900,
  sm: 600,
  md: 900,
  lg: 1200,
  xl: 1800,
};
;
/*! jellyfish-ui 3.2.0
 * © 2021-11 Matt Weet - https://www.mattweet.com */

function jfSetCookie(name,value,days){var expires;if(days){var date=new Date;date.setTime(date.getTime()+24*days*60*60*1e3),expires=";expires="+date.toUTCString()}else expires="";document.cookie=name+"="+encodeURIComponent(value)+expires+";path=/"}function jfGetCookie(cookiename){for(var name=cookiename+"=",ca=decodeURIComponent(document.cookie).split(";"),i=0;i<ca.length;i++){for(var c=ca[i];" "==c.charAt(0);)c=c.substring(1);if(0==c.indexOf(name))return c.substring(name.length,c.length)}return""}function jfDestroyCookie(name){document.cookie=name+"=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;"}function jfdebug(){document.getElementsByTagName("body")[0].classList.toggle("jf-debug")}function jfLazyLoadBackgroundImage(element){var bgImage=element.getAttribute("data-bg-img");element.style.backgroundImage="url("+bgImage+")",element.removeAttribute("data-bg-img")}!function($){$("button.font-size").click(function(){$(this).hasClass("font-size-sm")?($(document.body).removeClass("font-size-md font-size-lg").addClass("font-size-sm"),$("button.font-size").removeClass("active"),$(this).addClass("active"),jfSetCookie("fontsize","sm")):$(this).hasClass("font-size-md")?($(document.body).removeClass("font-size-sm font-size-lg").addClass("font-size-md"),$("button.font-size").removeClass("active"),$(this).addClass("active"),jfSetCookie("fontsize","md")):$(this).hasClass("font-size-lg")&&($(document.body).removeClass("font-size-sm font-size-md").addClass("font-size-lg"),$("button.font-size").removeClass("active"),$(this).addClass("active"),jfSetCookie("fontsize","lg"))}),$("button.toggle-contrast").click(function(){$(this).hasClass("active")?($(document.body).removeClass("dark-ui"),$(this).removeClass("active"),jfSetCookie("ui-mode","light")):($(document.body).addClass("dark-ui"),$(this).addClass("active"),jfSetCookie("ui-mode","dark"))}),$(document).ready(function($){switch(jfGetCookie("fontsize")){case"sm":$(document.body).removeClass("font-size-md font-size-lg").addClass("font-size-sm"),$("button.font-size").removeClass("active"),$("button.font-size-sm").addClass("active");break;case"md":$(document.body).removeClass("font-size-sm font-size-lg").addClass("font-size-md"),$("button.font-size").removeClass("active"),$("button.font-size-md").addClass("active");break;case"lg":$(document.body).removeClass("font-size-md font-size-md").addClass("font-size-lg"),$("button.font-size").removeClass("active"),$("button.font-size-lg").addClass("active")}switch(jfGetCookie("ui-mode")){case"dark":$(document.body).addClass("dark-ui"),$("button.toggle-contrast").addClass("active")}})}(jQuery),function($){$('a[href^="mailto:"]').click(function(){if("undefined"!=typeof gtag)return gtag("event","contact",{event_category:"Email Enquiry",event_action:"Mailto Click",event_label:$(this).attr("href")}),!0}),$('a[href^="tel:"]').click(function(){if("undefined"!=typeof gtag)return gtag("event","contact",{event_category:"Telephone Enquiry",event_action:"Tel Link Click",event_label:$(this).attr("href")}),!0}),$('a[href$=".pdf"]').click(function(){if("undefined"!=typeof gtag)return gtag("event","contact",{event_category:"PDF Download",event_action:"Download",event_label:$(this).attr("href")}),!0}),$("a:not([href*='"+document.domain+"'],[href*='mailto'],[href*='tel'],a[href$='.pdf'])").click(function(event){if("undefined"!=typeof gtag&&"#"!=$(this).attr("href").charAt(0)&&!$(this).attr("href").startsWith("javascript"))return gtag("event","contact",{event_category:"External Link",event_action:"Link Click",event_label:$(this).attr("href")}),!0})}(jQuery),document.addEventListener("DOMContentLoaded",function(){var lazyBackgroundElements=[].slice.call(document.querySelectorAll(".has-bg-img"));if("IntersectionObserver"in window){let lazyBackgroundObserver=new IntersectionObserver(function(entries,observer){entries.forEach(function(entry){entry.isIntersecting&&(jfLazyLoadBackgroundImage(entry.target),lazyBackgroundObserver.unobserve(entry.target))})},{rootMargin:"0px 0px 300px 0px"});lazyBackgroundElements.forEach(function(lazyBackground){lazyBackgroundObserver.observe(lazyBackground)})}else lazyBackgroundElements.forEach(function(lazyBackground){jfLazyLoadBackgroundImage(lazyBackground)})}),function($){$(".navbar .hamburger").on("click touch",function(e){var $navbar=$(this).parents(".navbar"),$navmenu=$navbar.find(".navbar-menu"),$navbarHamburgers=$navbar.find(".hamburger");$navmenu.length&&($navmenu.hasClass("is-off-canvas")?($navmenu.hasClass("is-active")&&($navmenu.addClass("closing"),setTimeout(function(){$navmenu.removeClass("closing")},550)),$("body").toggleClass("has-active-nav")):$navmenu.slideToggle(),$navmenu.toggleClass("is-active"),$navbarHamburgers.length&&($navmenu.hasClass("is-off-canvas")&&$navbarHamburgers.first().toggle(),$navbarHamburgers.each(function(){"true"===$(this).attr("aria-expanded")?($(this).attr("aria-expanded","false"),$(this).removeClass("is-active")):($(this).attr("aria-expanded","true"),$(this).addClass("is-active"))})))}),$(document).on("click",'li.has-children > a:not(".clicked"), li.menu-item-has-children > a:not(".clicked")',function(e){$(window).width()<breakPoints.navBar&&($(this).addClass("clicked"),e.preventDefault(),$(this).parent("li").toggleClass("drop-active").attr("aria-expanded","true"))})}(jQuery);;
  /**
   * forEach implementation for Objects/NodeLists/Arrays, automatic type loops and context options
   *
   * @private
   * @author Todd Motto
   * @link https://github.com/toddmotto/foreach
   * @param {Array|Object|NodeList} collection - Collection of items to iterate, could be an Array, Object or NodeList
   * @callback requestCallback      callback   - Callback function for each iteration.
   * @param {Array|Object|NodeList} scope=null - Object/NodeList/Array that forEach is iterating over, to use as the this value when executing callback.
   * @returns {}
   */
    var forEach=function(t,o,r){if("[object Object]"===Object.prototype.toString.call(t))for(var c in t)Object.prototype.hasOwnProperty.call(t,c)&&o.call(r,t[c],c,t);else for(var e=0,l=t.length;l>e;e++)o.call(r,t[e],e,t)};

    var hamburgers = document.querySelectorAll(".hamburger");
    if (hamburgers.length > 0) {
      forEach(hamburgers, function(hamburger) {
        hamburger.addEventListener("click", function() {
          this.classList.toggle("is-active");
        }, false);
      });
    }
;
/**
 * File skip-link-focus-fix.js.
 *
 * Helps with accessibility for keyboard only users.
 *
 * Learn more: https://git.io/vWdr2
 */
( function() {
	var isIe = /(trident|msie)/i.test( navigator.userAgent );

	if ( isIe && document.getElementById && window.addEventListener ) {
		window.addEventListener( 'hashchange', function() {
			var id = location.hash.substring( 1 ),
				element;

			if ( ! ( /^[A-z0-9_-]+$/.test( id ) ) ) {
				return;
			}

			element = document.getElementById( id );

			if ( element ) {
				if ( ! ( /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) ) ) {
					element.tabIndex = -1;
				}

				element.focus();
			}
		}, false );
	}
}() );
;
(function($) {

  $('#searchform').submit(function(e) {
    var s = $( this ).find("#s");
          if (!s.val()) {
      e.preventDefault();
      alert("Please enter a search term");
      $('#s').focus();
    }
  });

})( jQuery );
;
function jfGetTimeRemaining(endtime) {
  const total = Date.parse(endtime) - Date.parse(new Date());
  const seconds = Math.floor((total / 1000) % 60);
  const minutes = Math.floor((total / 1000 / 60) % 60);
  const hours = Math.floor((total / (1000 * 60 * 60)) % 24);
  const days = Math.floor(total / (1000 * 60 * 60 * 24));

  return {
    total,
    days,
    hours,
    minutes,
    seconds
  };
}

function jfInitializeClock(id, endtime) {
  const clock = document.getElementById(id);
  const daysSpan = clock.querySelector('.days');
  const hoursSpan = clock.querySelector('.hours');
  const minutesSpan = clock.querySelector('.minutes');
  const secondsSpan = clock.querySelector('.seconds');

  function jfUpdateClock() {
    const t = jfGetTimeRemaining(endtime);

    if (t.total >= 0) {
      daysSpan.innerHTML = ('0' + t.days).slice(-2);
      hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
      minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
      secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);
    }
    if (t.total <= 0) {
      clock.classList.add("complete");
      clearInterval(timeinterval);
    }
  }

  jfUpdateClock();

  const timeinterval = setInterval(jfUpdateClock, 1000);
}
;
(function( $ ) {

/**
 * initMap
 *
 * Renders a Google Map onto the selected jQuery element
 *
 * @date    22/10/19
 * @since   5.8.6
 *
 * @param   jQuery $el The jQuery element.
 * @return  object The map instance.
 */
function initMap( $el ) {

    // Find marker elements within map.
    var $markers = $el.find('.marker');

    // Create generic map.
    var mapArgs = {
        zoom        : $el.data('zoom') || 16,
        styles: [{featureType:"road",elementType:"geometry",stylers:[{lightness:100},{visibility:"simplified"}]},{"featureType":"water","elementType":"geometry","stylers":[{"visibility":"on"},{"color":"#C6E2FF",}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"color":"#C5E3BF"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"color":"#D1D1B8"}]}], // @link https://snazzymaps.com/style/77/clean-cut
        mapTypeId   : google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map( $el[0], mapArgs );

    // Add markers.
    map.markers = [];
    $markers.each(function(){
        initMarker( $(this), map );
    });

    // Center map based on markers.
    centerMap( map );

    // Return map instance.
    return map;
}

/**
 * initMarker
 *
 * Creates a marker for the given jQuery element and map.
 *
 * @date    22/10/19
 * @since   5.8.6
 *
 * @param   jQuery $el The jQuery element.
 * @param   object The map instance.
 * @return  object The marker instance.
 */
function initMarker( $marker, map ) {

    // Get position from marker.
    var lat = $marker.data('lat');
    var lng = $marker.data('lng');
    var latLng = {
        lat: parseFloat( lat ),
        lng: parseFloat( lng )
    };
    var icon = $marker.attr('data-icon');


    // Create marker instance.
    var marker = new google.maps.Marker({
        position : latLng,
        map: map,
        icon: icon
    });

    // Append to reference for later use.
    map.markers.push( marker );

    // If marker contains HTML, add it to an infoWindow.
    if( $marker.html() ){

        // Create info window.
        var infowindow = new google.maps.InfoWindow({
            content: $marker.html()
        });

        // Show info window when marker is clicked.
        google.maps.event.addListener(marker, 'click', function() {
            infowindow.open( map, marker );
        });
    }
}

/**
 * centerMap
 *
 * Centers the map showing all markers in view.
 *
 * @date    22/10/19
 * @since   5.8.6
 *
 * @param   object The map instance.
 * @return  void
 */
function centerMap( map ) {

    // Create map boundaries from all map markers.
    var bounds = new google.maps.LatLngBounds();
    map.markers.forEach(function( marker ){
        bounds.extend({
            lat: marker.position.lat(),
            lng: marker.position.lng()
        });
    });

    // Case: Single marker.
    if( map.markers.length == 1 ){
        map.setCenter( bounds.getCenter() );

    // Case: Multiple markers.
    } else{
        map.fitBounds( bounds );
    }
}

$(document).ready(function(){

    // Render on document ready...
    //$('.google-map').each(function(){
    //   var map = initMap( $(this) );
    //});

    // Use IntersectionObserver to defer loading of maps until nearly in viewport.
    var googleMapElements = [].slice.call(document.querySelectorAll(".google-map"));

    if("IntersectionObserver" in window) {
      let googleMapsObserver = new IntersectionObserver(function(entries, observer) {
        entries.forEach(function(entry) {
          if (entry.isIntersecting) {
            var map = initMap( $(entry.target));
            //console.log('Map initialized');
            googleMapsObserver.unobserve(entry.target);
          }
        });
      }, {rootMargin: "0px 0px 300px 0px"}); // Pre-empt by initializing 300px early

      googleMapElements.forEach(function(lazyInitGoogleMaps) {
        //console.log(lazyInitGoogleMaps);
        googleMapsObserver.observe(lazyInitGoogleMaps);
      });
    }
    else {
      // For browsers that don't support intersection observer, load all images straight away
      googleMapElements.forEach(function(lazyInitGoogleMaps){
        var map = initMap( $(lazyInitGoogleMaps));
      });
    }

});

})(jQuery);
;
(function($) {
  /**
   * For each element with class .count-to, this function looks for the following data attributes:
   * data-count, data-prefix, data-suffix
   * If the element is visible in the viewport, the function will use the data-count attribute to
   * count up to the number, appending the prefix/suffix and adding commas where necessary
   */

  // @link https://stackoverflow.com/questions/49877610/modify-countup-jquery-functions-to-include-commas
  function addCommas(nStr) {
    return nStr.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
  }

  // Only run if IntersectionObserver supported
  if(!!window.IntersectionObserver){
    // @link https://css-tricks.com/a-few-functional-uses-for-intersection-observer-to-know-when-an-element-is-in-view/

  let countUpObserver = new IntersectionObserver(
    (entries, countUpObserver) => {
    entries.forEach(entry => {
      if(entry.isIntersecting){
        countUp(entry.target);
       // countUpObserver.unobserve(entry.target); // Comment in to turn off observing (animate only once)
      }
      });
    }, {rootMargin: "0px 0px -20px 0px"}); // Wait until a slither of the number is visible
    document.querySelectorAll('.count-to').forEach(number => countUpObserver.observe(number));
  }

  function countUp(number) {
      var $this = $(number),
          countTo = $this.attr('data-count'),
          prefix = '', // Defaults
          suffix = '', // Defaults
          countDuration = 3000; // Defaults

      $this.text('0'); // Reset count to 0

      if (number.hasAttribute('data-prefix')) prefix = '<span class="small">'+$this.attr('data-prefix')+'</span>';
      if (number.hasAttribute('data-suffix')) suffix = '<span class="small">'+$this.attr('data-suffix')+'</span>';
      if (number.hasAttribute('data-duration')) countDuration = Number($this.attr('data-duration'));

      $this.removeClass('count-to'); // Prevents event firing in future.

      $({ countNum: $this.text() }).animate(
        {
          countNum: countTo
        },
        {
          duration: countDuration,
          easing: 'linear',
          step: function () {
            $this.html(prefix + addCommas(Math.floor(this.countNum)) + suffix);
          },
          complete: function () {
            $this.html(prefix + addCommas(this.countNum) + suffix);
          }
        });

  };

})( jQuery );
;
/**
 * Function to play youTube embedded video
 */
function playYouTubeVideo() {
  event.target.playVideo();
}

function playVimeoVideo() {
  event.target.play();
  //vimeoPlayer.play();
}

(function ($) {

  $(document).on('click touch', '.video-wrapper', function () {
    var $wrapper = $(this),
        $button = $wrapper.find('.play'),
        $iframe = $wrapper.find('iframe'),
        iframe = $iframe[0];

    $wrapper.addClass('playing'); // Fades out the overlay

    if ($button.hasClass('platform-vimeo')) {
      if (typeof $iframe.attr('src') === 'undefined') {
        $iframe.attr('src', $button.data('src')); // Insert the src from the button's data-attr
        vimeoPlayer = new Vimeo.Player(iframe); // Create Vimeo Player
        vimeoPlayer.on('loaded', playVimeoVideo); // Play when loaded
      } else {
        // If the video already has a src, play it
        vimeoPlayer = new Vimeo.Player(iframe);
        playVimeoVideo();
      }
      vimeoPlayer.play();
    }
    else if ($button.hasClass('platform-youtube')) {
      if (typeof $iframe.attr('src') === 'undefined') {
        $iframe.attr('src', $button.data('src')); // Insert the src from the button's data-attr

        var youTubePlayer = new YT.Player(iframe, {
          // Use the YouTube API to create a new player
          events: {
            "onReady": playYouTubeVideo,
            //"onError": function (e) {
            //  console.log(e);
            //}
          },
        });

        // Fallback if the API doesn't work
        if (typeof youTubePlayer.playVideo === 'undefined') {
          youTubePlayer = $iframe;
          $iframe.attr('src', $iframe.attr('src').replace('autoplay=0', 'autoplay=1'));
        }
      }
      else {
        // If the video already has a src, play it
        var youTubePlayer = new YT.Player(iframe);
        playYouTubeVideo;
      }
  }
  });
})(jQuery);
