/**
 * Looks for a button with class .button-loadmore and uses AJAX to load next posts into
 * an archive feed.
 * Modified from @link https://rudrastyh.com/wordpress/load-more-posts-ajax.html
 */

jQuery(function($){
  var canBeLoaded = true, // this param allows to initiate the AJAX call only if necessary
      ajaxContainer = $('.archive-feed');

	$(window).scroll(function(){
		var data = {
			'action': 'loadmore',
			'query': jellypress_loadmore_params.posts,
			'page' : jellypress_loadmore_params.current_page
      },
      containerPosFromTop = ajaxContainer.offset().top,
      containerHeight = ajaxContainer.height(),
      windowHeight = $(window).height(),
      windowScrollTop = $(window).scrollTop();

		if( ((windowHeight+windowScrollTop)>(containerPosFromTop+containerHeight)) && canBeLoaded == true ){
			$.ajax({
				url : jellypress_loadmore_params.ajaxurl,
				data:data,
				type:'POST',
				beforeSend: function( xhr ){
					// you can also add your own preloader here
					// you see, the AJAX call is in process, we shouldn't run it again until complete
					canBeLoaded = false;
				},
				success:function(data){
					if( data ) {

						// IMPORTANT! This next line determines where to load the data to. Any changes to the template structure must be reflected here.
            ajaxContainer.find('article:last-of-type').after( data );

						canBeLoaded = true; // the ajax is completed, now we can run it again
						jellypress_loadmore_params.current_page++;
					}
				}
			});
		}
	});
});

// TODO: Add a throttler to prevent excess calls?
