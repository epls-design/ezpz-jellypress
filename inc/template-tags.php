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

/**
 * Adds a function to display SVGs from the spritesheet.
 */
function jellypress_icon($icon) {
	// Define SVG sprite file.
	$icon_path = get_theme_file_path( '/dist/icons/'.$icon.'.svg' );
  // If it exists, include it.
  if ( file_exists( $icon_path ) ) {
    $use_link = get_template_directory_uri().'/dist/icons/icons.svg#icon-'.$icon;
    return '<svg class="icon"><use xlink:href="'.$use_link.'" /></use></svg>';
  }
  else {
    return '';
  }
}

if ( ! function_exists( 'jellypress_hide_email' ) ) {
  /**
   * Obscure email addresses from spam bots
   * Spam bots will only be able to read the email address if they are capable of executing javascript
   * @link http://www.maurits.vdschee.nl/php_hide_email/
   */
  function jellypress_hide_email($email) {
    $character_set = '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz';
    $key = str_shuffle($character_set); $cipher_text = ''; $id = 'e'.rand(1,999999999);
    for ($i=0;$i<strlen($email);$i+=1) $cipher_text.= $key[strpos($character_set,$email[$i])];
    $script = 'var a="'.$key.'";var b=a.split("").sort().join("");var c="'.$cipher_text.'";var d="";';
    $script.= 'for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));';
    $script.= 'document.getElementById("'.$id.'").innerHTML="<a href=\\"mailto:"+d+"\\">"+d+"</a>"';
    $script = "eval(\"".str_replace(array("\\",'"'),array("\\\\",'\"'), $script)."\")";
    $script = '<script type="text/javascript">/*<![CDATA[*/'.$script.'/*]]>*/</script>';
    return '<span id="'.$id.'">[javascript protected email address]</span>'.$script;
  }
}

if ( ! function_exists( 'jellypress_copyright' ) ) {
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
  }
