<?php

/**
 * Useful Helper functions and snippets
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Helper function to set up the attributes for a custom Gutenberg block
 *
 * @param array $block Gutenberg block
 * @return array $attributes Array of attributes for the block
 */
function jellypress_get_block_attributes($block, $context) {
  $block_classes = 'block block-' . sanitize_title($block['title']);
  $block_type = $block['name'];

  // Merge in block classes from Gutenberg Editor
  if (isset($block['className'])) $block_classes .= ' ' . $block['className'];

  // Add background colour class if set
  // Example of how to override the background colour for a specific block

  $excluded_blocks = [
    'ezpz/hero-page',
  ];
  if (in_array($block_type, $excluded_blocks)) {
    // Do nothing as it's set in the view.php
  } elseif (isset($block['backgroundColor'])) {
    $block_classes .= ' bg-' . $block['backgroundColor'];
  } else {
    $block_classes .= ' bg-white';
  }

  $bg_color = isset($block['backgroundColor']) ? $block['backgroundColor'] : 'white';

  if (isset($context['ezpz/outerContainer']) && $context['ezpz/outerContainer'] == 'cover') {
    // Cover block and no background colour set, so set it to black to match block.json
    if (!isset($context['ezpz/backgroundColor']))
      $bg_color = 'black';
    else
      $bg_color = $context['ezpz/backgroundColor'];
  }

  // Remove 'is-style- ' from the block classes
  $block_classes = str_replace('is-style-', '', $block_classes);

  $attributes = array(
    'anchor' => isset($block['anchor']) ? "id='" . esc_attr($block['anchor']) . "'" : '',
    'class' => $block_classes,
    'block_id' => $block['id'],
    'bg_color' => $bg_color,
    'text_align' => isset($block['alignText']) ? 'text-' . $block['alignText'] : 'text-left',
  );

  if (isset($block['alignContent']))  $attributes['align_content'] = sanitize_title($block['alignContent']);

  if (!empty($block['align'])) $attributes['align'] = $block['align'];

  return $attributes;
}

/**
 * Function which trims supplied text to a specified length.
 *
 * @param $text = Text to Trim
 * @param $maxchar = Maximum characters
 * @param string $end = Appended to text that gets trimmed
 * @return void
 */
function jellypress_trimpara($text, $maxchar, $end = '...') {
  // @link https://www.hashbangcode.com/article/cut-string-specified-length-php
  if (strlen($text) > $maxchar || $text == '') {
    $words = preg_split('/\s/', $text);
    $output = '';
    $i      = 0;
    while (1) {
      $length = strlen($output) + strlen($words[$i]);
      if ($length > $maxchar) {
        break;
      } else {
        $output .= " " . $words[$i];
        ++$i;
      }
    }
    $output .= $end;
  } else {
    $output = $text;
  }
  return $output;
}

/**
 * Display an SVG icon from the spritesheet
 * TODOL REOLACE WITH DEVE ICONS FROM SOMEWHERE ELSE
 */
function jellypress_icon($icon, $class = '') {
  // Define SVG sprite file.
  $icon_path = get_theme_file_path('/dist/icons/' . $icon . '.svg');
  // If it exists, include it.
  if (file_exists($icon_path)) {
    $use_link = get_template_directory_uri() . '/dist/icons/icons.svg?v=' . filemtime($icon_path) . '#icon-' . $icon;
    // Append filemtime to link to prevent caching in development.
    $classes = [
      'icon',
      'icon-' . $icon,
    ];
    if ($class) {
      $classes[] = $class;
    }
    return '<svg class="' . implode(" ", $classes) . '"><use xlink:href="' . $use_link . '" /></use></svg>';
  } else {
    return '';
  }
}

/**
 * Determine if a link is external
 *
 * @param string $url URL to check
 * @return bool True if the URL is external
 */
function is_link_external($url) {
  if ($url == '') return false;
  $url_host = parse_url($url, PHP_URL_HOST);
  $site_host = parse_url(get_site_url(), PHP_URL_HOST);
  return ($url_host && $url_host !== $site_host);
}

/**
 * Displays the page URL that was requested, for use on a 404 page
 */
add_shortcode('requested-page', function () {
  if (isset($_GET['request'])) {
    return '<span class="bold">' . $_GET['request'] . '</span>';
  }
  return '';
});


/**
 * Consumes a social channel URL and returns the channel name and icon
 *
 * @param string $url URL of the social channel
 * @return array Array containing the URL, icon, and channel name
 */
function jellypress_get_social_channel($url) {
  $url = strtolower(trim($url));
  $icon = '';
  $channel_name = '';

  // Extract domain from URL
  $domain = '';
  if (preg_match('/^(?:https?:\/\/)?(?:www\.)?([^\/]+)/', $url, $matches)) {
    $domain = $matches[1];
  }

  switch (true) {
    case strpos($domain, 'facebook.com') !== false:
      $icon = 'facebook';
      $channel_name = 'Facebook';
      break;

    case strpos($domain, 'twitter.com') !== false || strpos($domain, 'x.com') !== false:
      $icon = 'x';
      $channel_name = 'Twitter/X';
      break;

    case strpos($domain, 'instagram.com') !== false:
      $icon = 'instagram';
      $channel_name = 'Instagram';
      break;

    case strpos($domain, 'linkedin.com') !== false:
      $icon = 'linkedin';
      $channel_name = 'LinkedIn';
      break;

    case strpos($domain, 'youtube.com') !== false || strpos($domain, 'youtu.be') !== false:
      $icon = 'youtube';
      $channel_name = 'YouTube';
      break;

    case strpos($domain, 'pinterest.com') !== false:
      $icon = 'pinterest';
      $channel_name = 'Pinterest';
      break;

    case strpos($domain, 'tiktok.com') !== false:
      $icon = 'tiktok';
      $channel_name = 'TikTok';
      break;

    case strpos($domain, 'snapchat.com') !== false:
      $icon = 'snapchat';
      $channel_name = 'Snapchat';
      break;

    case strpos($domain, 'reddit.com') !== false:
      $icon = 'reddit';
      $channel_name = 'Reddit';
      break;

    case strpos($domain, 'tumblr.com') !== false:
      $icon = 'tumblr';
      $channel_name = 'Tumblr';
      break;

    case strpos($domain, 'whatsapp.com') !== false:
      $icon = 'whatsapp';
      $channel_name = 'WhatsApp';
      break;

    case strpos($domain, 'telegram.org') !== false || strpos($domain, 't.me') !== false:
      $icon = 'telegram';
      $channel_name = 'Telegram';
      break;

    case strpos($domain, 'threads.net') !== false:
      $icon = 'threads';
      $channel_name = 'Threads';
      break;

    case strpos($domain, 'discord.com') !== false || strpos($domain, 'discord.gg') !== false:
      $icon = 'discord';
      $channel_name = 'Discord';
      break;

    case strpos($domain, 'medium.com') !== false:
      $icon = 'medium';
      $channel_name = 'Medium';
      break;

    case strpos($domain, 'vimeo.com') !== false:
      $icon = 'vimeo';
      $channel_name = 'Vimeo';
      break;

    case strpos($domain, 'flickr.com') !== false:
      $icon = 'flickr';
      $channel_name = 'Flickr';
      break;

    case strpos($domain, 'twitch.tv') !== false:
      $icon = 'twitch';
      $channel_name = 'Twitch';
      break;

    case strpos($domain, 'github.com') !== false:
      $icon = 'github';
      $channel_name = 'GitHub';
      break;

    case strpos($domain, 'mastodon') !== false:
      $icon = 'mastodon';
      $channel_name = 'Mastodon';
      break;

    default:
      $icon = 'website';
      $channel_name = 'Website';
      break;
  }

  return [
    'url' => $url,
    'icon' => $icon,
    'name' => $channel_name,
  ];
}
