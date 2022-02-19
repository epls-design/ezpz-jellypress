/**
 * Looks for a button with class .button-loadmore and uses AJAX to load next posts into
 * an archive feed.
 * Modified from @link https://rudrastyh.com/wordpress/load-more-posts-ajax.html
 */

jQuery(function ($) { // use jQuery code inside this to avoid "$ is not defined" error
	$('.button-loadmore').click(function () {

		var button = $(this),
			data = {
				'action': 'loadmore',
				'query': jellypress_loadmore_params.posts, // that's how we get params from wp_localize_script() function
				'page': jellypress_loadmore_params.current_page
			},
			initial_button_text = button.html(),
			ajaxContainer = $('.archive-feed'),
			scrollTo = ajaxContainer.offset().top + ajaxContainer.height(); // Bottom of the container, so that we can scroll back to it when new content loaded

		$.ajax({ // you can also use $.post here
			url: jellypress_loadmore_params.ajaxurl, // AJAX handler
			data: data,
			type: 'POST',
			beforeSend: function (xhr) {
				button.text('Loading...'); // change the button text
			},
			success: function (data) {
				if (data) {
					button.text(initial_button_text).blur(); // Reset the button text and remove focus on the button

					// IMPORTANT! This next line determines where to load the data to. Any changes to the template structure must be reflected here.
					ajaxContainer.find('article:last-of-type').after(data);

					jellypress_loadmore_params.current_page++;

					// Scroll to top of newly injected content
					$([document.documentElement, document.body]).animate({
						scrollTop: scrollTo - 20 // Add 20px padding from top of window
					}, 50);

					if (jellypress_loadmore_params.current_page == jellypress_loadmore_params.max_page)
						button.remove(); // if last page, remove the button

					// you can also fire the "post-load" event here if you use a plugin that requires it
					// $( document.body ).trigger( 'post-load' );
				} else {
					button.remove(); // if no data, remove the button as well
				}
			}
		});
	});
});
