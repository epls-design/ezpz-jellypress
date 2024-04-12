// TODO: REFACTOR SO THIS CAN SHOW IN EDITOR TOO

var infowindow;
(function ($) {
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
  function initMap($el) {
    // Find marker elements within map.
    var $markers = $el.find(".marker");

    // Create generic map.
    var mapArgs = {
      zoom: $el.data("zoom") || 16,
      styles: [
        {
          featureType: "road",
          elementType: "geometry",
          stylers: [{ lightness: 100 }, { visibility: "simplified" }],
        },
        {
          featureType: "water",
          elementType: "geometry",
          stylers: [{ visibility: "on" }, { color: "#C6E2FF" }],
        },
        {
          featureType: "poi",
          elementType: "geometry.fill",
          stylers: [{ color: "#C5E3BF" }],
        },
        {
          featureType: "road",
          elementType: "geometry.fill",
          stylers: [{ color: "#D1D1B8" }],
        },
      ], // @link https://snazzymaps.com/style/77/clean-cut
      mapTypeId: google.maps.MapTypeId.ROADMAP,
    };
    var map = new google.maps.Map($el[0], mapArgs);

    infowindow = new google.maps.InfoWindow();

    // Add markers.
    map.markers = [];
    $markers.each(function () {
      initMarker($(this), map);
    });

    // Center map based on markers.
    centerMap(map);

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
  function initMarker($marker, map) {
    // Get position from marker.
    var lat = $marker.data("lat");
    var lng = $marker.data("lng");
    var latLng = {
      lat: parseFloat(lat),
      lng: parseFloat(lng),
    };
    var icon = $marker.attr("data-icon");

    // Create marker instance.
    var marker = new google.maps.Marker({
      position: latLng,
      map: map,
      icon: icon,
    });

    // Append to reference for later use.
    map.markers.push(marker);

    // If marker contains HTML, add it to an infoWindow.
    if ($marker.html()) {
      // Show info window when marker is clicked.
      google.maps.event.addListener(marker, "click", function () {
        infowindow.setOptions({
          content: $marker.html(),
        });

        infowindow.open(map, marker);
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
  function centerMap(map) {
    // Create map boundaries from all map markers.
    var bounds = new google.maps.LatLngBounds();
    map.markers.forEach(function (marker) {
      bounds.extend({
        lat: marker.position.lat(),
        lng: marker.position.lng(),
      });
    });

    // Case: Single marker.
    if (map.markers.length == 1) {
      map.setCenter(bounds.getCenter());

      // Case: Multiple markers.
    } else {
      map.fitBounds(bounds);
    }
  }

  $(document).ready(function () {
    // Render on document ready...
    //$('.google-map').each(function(){
    //   var map = initMap( $(this) );
    //});

    // Use IntersectionObserver to defer loading of maps until nearly in viewport.
    var googleMapElements = [].slice.call(
      document.querySelectorAll(".google-map")
    );

    if ("IntersectionObserver" in window) {
      let googleMapsObserver = new IntersectionObserver(
        function (entries, observer) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) {
              var map = initMap($(entry.target));
              //console.log('Map initialized');
              googleMapsObserver.unobserve(entry.target);
            }
          });
        },
        { rootMargin: "0px 0px 300px 0px" }
      ); // Pre-empt by initializing 300px early

      googleMapElements.forEach(function (lazyInitGoogleMaps) {
        //console.log(lazyInitGoogleMaps);
        googleMapsObserver.observe(lazyInitGoogleMaps);
      });
    } else {
      // For browsers that don't support intersection observer, load all images straight away
      googleMapElements.forEach(function (lazyInitGoogleMaps) {
        var map = initMap($(lazyInitGoogleMaps));
      });
    }
  });
})(jQuery);
