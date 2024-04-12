// Initialise the Accordion if loaded in the block preview - otherwise the library will load itself on front end
if (window.acf) {
  window.acf.addAction("render_block_preview/type=ezpz/accordion", function () {
    ARIAaccordion.init();
  });
}
