<?php
/**
 * Flexible layout: Video block
 * Renders a video block
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Get Params from get_template_part:
$block = $args['block'];
$block_id = $args['block_id'];
//var_dump($block);

$block_title = $block['title'];
$video_url = $block['video'];
$block_is_fullwidth = $block['full_width'];
?>

<?php if ($block_title) : $title_align = $block['title_align']; ?>
  <header class="row block-title">
    <div class="col">
      <h2 class="text-<?php echo $title_align;?>"><?php echo jellypress_bracket_tag_replace($block_title); ?></h2>
    </div>
  </header>
<?php endif; ?>

<div class="row">
  <div class="col">
    <?php
    if ( $block_is_fullwidth == 1 ){ echo '<div class="vw-100">'; }
      jellypress_embed_video($video_url);
    if ( $block_is_fullwidth == 1 ){ echo '</div>'; }?>
  </div>
</div>
