<?php
/**
 * Template part for displaying hero content on search.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?>
<header class="block hero hero-search bg-white">
  <div class="container">
    <div class="row">
      <div class="col">
        <header class="page-header">
          <h1 class="page-title">
            <?php
              /* translators: %s: search query. */
              printf( esc_html__( 'Search Results for: %s', 'jellypress' ), '<span>' . get_search_query() . '</span>' );
              ?>
          </h1>
        </header>
      </div>
    </div>
  </div>
</header>

