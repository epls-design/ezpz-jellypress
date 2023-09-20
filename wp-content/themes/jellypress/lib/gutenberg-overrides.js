// Removes additional formatting options from the Gutenberg editor (used in core/paragraph)
window.wp.domReady(function () {
  wp.richText.unregisterFormatType("core/image");
  wp.richText.unregisterFormatType("core/text-color");
  wp.richText.unregisterFormatType("core/keyboard");
  wp.richText.unregisterFormatType("core/code");
});

/**
 * Filters core blocks to ensure that they can only be used inside the ezpz/content block
 * unless otherwise specified.
 * @see https://developer.wordpress.org/block-editor/developers/filters/block-filters/#using-filters
 * @see https://nickdiego.com/how-to-modify-block-supports-using-client-side-filters/
 */

function filterBlockParents(settings, name) {
  let childBlocks = [
    "core/paragraph",
    "core/heading",
    "core/list",
    "core/shortcode",
    "core/table",
  ];

  // Bail if not in array of child blocks
  if (!childBlocks.includes(name)) return settings;

  // Return the block with a forced parent block, and the updated supports
  return lodash.assign({}, settings, {
    parent: ["ezpz/content"],
  });
}

wp.hooks.addFilter(
  "blocks.registerBlockType",
  "ezpz/content",
  filterBlockParents
);
