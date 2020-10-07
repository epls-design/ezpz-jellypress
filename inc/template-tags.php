<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'jellypress_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function jellypress_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">(Updated: %4$s)</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x( 'Posted on %s', 'post date', 'jellypress' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>';

	}
endif;

if ( ! function_exists( 'jellypress_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function jellypress_posted_by() {
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'jellypress' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>';

	}
endif;

if ( ! function_exists( 'jellypress_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function jellypress_entry_footer() { ?>
    <footer class="entry-footer">
      <div class="container">
        <div class="row">
          <div class="col">
            <?php
            // Hide category and tag text for pages.
            if ( 'post' === get_post_type() ) {
              /* translators: used between list items, there is a space after the comma */
              $categories_list = get_the_category_list( esc_html__( ', ', 'jellypress' ) );
              if ( $categories_list ) {
                /* translators: 1: list of categories. */
                printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'jellypress' ) . '</span>', $categories_list );
              }

              /* translators: used between list items, there is a space after the comma */
              $tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'jellypress' ) );
              if ( $tags_list ) {
                /* translators: 1: list of tags. */
                printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'jellypress' ) . '</span>', $tags_list );
              }
            }

            // If the post type is a post (ie. not a page), it is not single, is not private and has comments open or at least one comment, show a link to the comments...
            if ( 'post' === get_post_type() && ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
              echo '<span class="comments-link">';
              comments_popup_link(
                sprintf(
                  wp_kses(
                    /* translators: %s: post title */
                    __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'jellypress' ),
                    array(
                      'span' => array(
                        'class' => array(),
                      ),
                    )
                  ),
                  get_the_title()
                )
              );
              echo '</span>';
            }
            // Show the edit post button for logged in Admins
            if ( get_edit_post_link() ) :
              edit_post_link(
                sprintf(
                  wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                    __( 'Edit <span class="screen-reader-text">%s</span>', 'jellypress' ),
                    array(
                      'span' => array(
                        'class' => array(),
                      ),
                    )
                  ),
                  wp_kses_post( get_the_title() )
                ),
                '<span class="edit-link">',
                '</span>'
              );
            endif;
          ?>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container -->
  </footer><!-- /.entry-footer -->
	<?php } // jellypress_entry_footer
endif;

if ( ! function_exists( 'jellypress_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function jellypress_post_thumbnail($size = 'post-thumbnail') {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}
		if ( is_singular() ) :
			?>

    <figure class="post-thumbnail">
      <?php the_post_thumbnail($size); ?>
    </figure><!-- /.post-thumbnail -->

    <?php else : ?>

    <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
      <?php
          the_post_thumbnail( $size, array(
            'alt' => the_title_attribute( array(
              'echo' => false,
            ) ),
          ) );
          ?>
    </a>

<?php
		endif; // End is_singular().
	}
endif;

if ( ! function_exists( 'wp_body_open' ) ) :
	/**
	 * Shim for sites older than 5.2.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12563
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
endif;

if ( ! function_exists( 'jellypress_copyright' ) ) :
  /**
   * Creates a more dynamic copyright notice using first and last post date.
   * @link https://www.wpbeginner.com/wp-tutorials/how-to-add-a-dynamic-copyright-date-in-wordpress-footer/
   */
  function jellypress_copyright() {
    global $wpdb;
    $copyright_dates = $wpdb->get_results("
    SELECT
    YEAR(min(post_date_gmt)) AS firstdate,
    YEAR(max(post_date_gmt)) AS lastdate
    FROM
    $wpdb->posts
    WHERE
    post_status = 'publish'
    ");
    $output = '';
    if($copyright_dates) {
    $copyright = "&copy; " . get_bloginfo( 'name' ) . ' ' . $copyright_dates[0]->firstdate;
    if($copyright_dates[0]->firstdate != $copyright_dates[0]->lastdate) {
    $copyright .= '-' . $copyright_dates[0]->lastdate;
    }
    $output = $copyright;
    }
    return $output;
    }
endif;

/**
 * A function which takes an ACF field and if that field exists,
 * uses it for the_excerpt instead of the WP native excerpt.
 * Provides the_excerpt as a fallback if the field does not exist or is empty.
 *
 * @param [string] $custom_field to pass
 * @return void
 */
if ( ! function_exists( 'jellypress_excerpt' ) ) :
  function jellypress_excerpt($custom_field = null) {
    // TODO: Extend this function so that if neither the excerpt or custom field exists, it will try to find some text on the page and use this
    // Could possibly be improved with https://www.charlyanderson.co.uk/blog/acf-snippets/
    if($custom_field) {
      // TODO: Rewrite so it is no longer using get_field but instead takes a passed value. See Oasis for some fall back stuff
      $custom_field_excerpt = get_field( $custom_field ); // Get the field from ACF
      $trimmed_content = wp_trim_words($custom_field_excerpt); // Strip images and tags
      $clean_excerpt = apply_filters('the_excerpt', $trimmed_content); // Apply the excerpt filter
      echo $clean_excerpt; // Output the result
    }
    else {
      // If the field is empty, show the normal excerpt from Wordpress
      the_excerpt(); // Can I hook into the excerpt filter, eg. to use this for Yoast Desc?
    }
  }
endif;

// TODO: Hook into featured image and replace with a ACF image? Eg. for Yoast SEO

/**
 * This function effectively does the same job as the_post_navigation() but wraps the navigation in Jellyfish compliant classes.
 * It will only display the_post_navigation() if there are posts to show.
 *
 * @return void
 */
if ( ! function_exists( 'jellypress_post_navigation' ) ) :
  function jellypress_post_navigation() {
    $prev_post = get_previous_post();
    $prev_id = $prev_post->ID;
    $prev_permalink = get_permalink($prev_id);
    $next_post = get_next_post();
    $next_id = $next_post->ID;
    $next_permalink = get_permalink($next_id);
    $postType = get_post_type_object(get_post_type());

    if($prev_id || $next_id):
      echo '<nav class="post-navigation section bg-white"><div class="container"><div class="row"><div class="col">';
      _e('<h2 class="screen-reader-text">'.$postType->labels->singular_name.' navigation</h2>', 'jellypress');
      echo '<div class="nav-links">';
      if($prev_id)
        _e('<div class="nav-previous">Previous<span class="screen-reader-text"> '.$postType->labels->singular_name.'</span>: <a href="'.$prev_permalink.'" rel="prev">'.$prev_post->post_title.'</a></div>','jellypress');
      if($next_id)
        _e('<div class="nav-next">Next<span class="screen-reader-text"> '.$postType->labels->singular_name.'</span>: <a href="'.$next_permalink.'" rel="next">'.$next_post->post_title.'</a></div>','jellypress');
      echo '</div></div></div></div></nav>';
    endif;
  }
endif;

// TODO: Add a function to get a list of terms attached to a post. Option to pass an array of term names or get all terms https://developer.wordpress.org/reference/functions/get_the_terms/

if ( ! function_exists( 'jellypress_content' ) ) :
  /* @Recreate the default filters on the_content so we can pull formatted content with get_post_meta and get_all_custom_field_meta */
  add_filter( 'meta_content', 'wptexturize'        );
  add_filter( 'meta_content', 'convert_smilies'    );
  add_filter( 'meta_content', 'convert_chars'      );
  add_filter( 'meta_content', 'wpautop'            );
  add_filter( 'meta_content', 'shortcode_unautop'  );
  add_filter( 'meta_content', 'prepend_attachment' );

  function jellypress_content($unformatted_content) {
    echo apply_filters('meta_content', $unformatted_content);
  }
endif;
