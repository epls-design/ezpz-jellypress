/**
 * Looks for a button with class .button-loadmore and uses AJAX to load next posts into
 * an archive feed.
 * Modified from @link https://rudrastyh.com/wordpress/load-more-posts-ajax.html
 */

jQuery(function($){
	var canBeLoaded = true, // this param allows to initiate the AJAX call only if necessary
      bottomOffset = 2000; // the distance (in px) from the page bottom when you want to load more posts
      // TODO: change bottomOffset to be position of .archive-feed from bottom of window - otherwise if there is a large sidebar the posts will not LazyLoad on scroll

	$(window).scroll(function(){
		var data = {
			'action': 'loadmore',
			'query': jellypress_loadmore_params.posts,
			'page' : jellypress_loadmore_params.current_page
		};
		if( $(document).scrollTop() > ( $(document).height() - bottomOffset ) && canBeLoaded == true ){
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
            $('.archive-feed').find('article:last-of-type').after( data );

						canBeLoaded = true; // the ajax is completed, now we can run it again
						jellypress_loadmore_params.current_page++;
					}
				}
			});
		}
	});
});
