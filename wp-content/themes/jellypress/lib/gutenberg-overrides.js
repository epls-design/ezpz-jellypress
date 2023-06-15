// Removes additional formatting options from the Gutenberg editor (used in core/paragraph)
window.wp.domReady(function () {
  wp.richText.unregisterFormatType("core/image");
  wp.richText.unregisterFormatType("core/text-color");
  wp.richText.unregisterFormatType("core/keyboard");
  wp.richText.unregisterFormatType("core/code");
});
