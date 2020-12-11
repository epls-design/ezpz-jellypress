<?php
/**
 * Template part for displaying hero content on archive.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?>
<header class="block hero hero-archive bg-white">
  <div class="container">
    <div class="row">
      <div class="col">
        <header class="page-header">
          <?php
          the_archive_title( '<h1 class="page-title">', '</h1>' );
          the_archive_description( '<div class="archive-description">', '</div>' );
          ?>
        </header>
      </div>
    </div>
  </div>
</header>
