/* global wp, jQuery */
/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title, .site-description' ).css( {
					clip: 'rect(1px, 1px, 1px, 1px)',
					position: 'absolute',
				} );
			} else {
				$( '.site-title, .site-description' ).css( {
					clip: 'auto',
					position: 'relative',
				} );
				$( '.site-title a, .site-description' ).css( {
					color: to,
				} );
			}
		} );
	} );
}( jQuery ) );
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
jQuery(document).ready(function ($) {
  var navPoint = '900'; // px value at which the navigation should change from a burger menu to inline list

  // Expand and Collapse .navbar-menu when clicking .hamburger
  $(".hamburger").on('click touch', function (e) {
    // Search the parent .navbar for the .navbar-menu and store as a variable
    var navmenu = $(this).parents('.navbar').find('.navbar-menu');
    // Slide the navmenu into view
    $(navmenu).slideToggle();
    // Toggle the state of the aria-expanded attribute for screen readers
    var menuItem = $(e.currentTarget);
      if (menuItem.attr( 'aria-expanded') === 'true') {
        $(this).attr( 'aria-expanded', 'false');
      }
      else {
        $(this).attr( 'aria-expanded', 'true');
      }
  });

  // If a menu item with children is clicked...
  $(document).on("click", 'li.has-children > a:not(".clicked"), li.menu-item-has-children > a:not(".clicked")', function (e) {
    // ...and the window width is smaller than the navPoint
    if ($(window).width() < (navPoint)) {
      // add .clicked class to the anchor element
      ($(this).addClass("clicked"));
      // prevent the link from firing
      e.preventDefault();
      // add .drop-active class and aria-expanded to parent li
      $(this).parent("li").toggleClass("drop-active").attr( 'aria-expanded', 'true');
    }
  });
});

function jfdebug() {
  // Trigger debug mode by applying .jf-debug to document
  var docBody = document.getElementsByTagName('body')[0];
  docBody.classList.toggle('jf-debug');
}

// TODO: Can I remove dependency on jQuery?
// FIX: Menu stopped working - probably an issue with jQuery(document) - check old version
