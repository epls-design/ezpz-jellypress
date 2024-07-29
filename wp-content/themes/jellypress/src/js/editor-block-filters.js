// Import dependencies
const { addFilter } = wp.hooks;
const { createHigherOrderComponent } = wp.compose;
const { Fragment } = wp.element;
const { InspectorControls } = wp.blockEditor;
const { PanelBody } = wp.components;
const { __ } = wp.i18n;
const { RadioControl } = wp.components;
const { unregisterBlockStyle } = wp.blocks;
const { unregisterFormatType } = wp.richText;

/**
 * Adds custom Aspect Ratio attribute to core/embed block
 */
function ezpzAddAttributesCoreEmbed(settings, name) {
  if (name === "core/embed") {
    return lodash.assign({}, settings, {
      attributes: lodash.merge(settings.attributes, {
        aspectRatio: {
          type: "string",
          default: "16x9",
        },
      }),
    });
  }
  return settings;
}

addFilter(
  "blocks.registerBlockType",
  "ezpz/core/embed/attributes",
  ezpzAddAttributesCoreEmbed
);

/**
 * Adds settings panel for custom attribute AspectRatio on core/embed block
 * @see https://css-tricks.com/a-crash-course-in-wordpress-block-filters/
 * @see https://developer.wordpress.org/block-editor/developers/filters/block-filters/#using-filters
 */
const ezpzAddInspectorControlCoreEmbed = createHigherOrderComponent(
  (BlockEdit) => {
    return (props) => {
      const {
        attributes: { aspectRatio },
        setAttributes,
        name,
      } = props;

      if (name !== "core/embed") {
        return <BlockEdit {...props} />;
      }

      return (
        <Fragment>
          <BlockEdit {...props} />
          <InspectorControls>
            <PanelBody
              title={__("Embed settings", "jellypress")}
              initialOpen={true}
            >
              <RadioControl
                label={__("Aspect Ratio", "jellypress")}
                help={__(
                  "Select the aspect ratio of the embed (applies to video only)",
                  "jellypress"
                )}
                selected={aspectRatio}
                options={[
                  {
                    label: "16x9",
                    value: "16x9",
                  },
                  {
                    label: "21x9",
                    value: "21x9",
                  },
                  {
                    label: "1x1",
                    value: "1x1",
                  },
                  {
                    label: "4x3",
                    value: "4x3",
                  },
                ]}
                onChange={(value) => {
                  setAttributes({ aspectRatio: value });
                }}
              />
            </PanelBody>
          </InspectorControls>
        </Fragment>
      );
    };
  },
  "ezpzAddInspectorControlCoreEmbed"
);

addFilter(
  "editor.BlockEdit",
  "ezpz/core/embed/inspector-controls",
  ezpzAddInspectorControlCoreEmbed
);

/**
 * Removes supports from core blocks
 */
addFilter(
  "blocks.registerBlockType",
  "ezpz/core/remove-supports",
  function (settings, name) {
    let removeFrom = [
      "core/heading",
      "core/code",
      "core/pullquote",
      "core/table",
      "core/audio",
      "core/separator",
      "core/embed",
      "core/image",
    ];
    if (removeFrom.includes(name)) {
      if (name == "core/image") {
        return lodash.assign({}, settings, {
          supports: lodash.assign({}, settings.supports, {
            align: false,
            filter: false, // NOTE: HAVE TO TURN IT OFF HERE because theme.json doesn't work
          }),
        });
      }
      return lodash.assign({}, settings, {
        supports: lodash.assign({}, settings.supports, {
          align: false,
        }),
      });
    }
    return settings;
  }
);

/**
 * Filter out various block styles and formats from core blocks
 **/
window.wp.domReady(function () {
  unregisterFormatType("core/image"); // Prevents the user adding inline images
  unregisterFormatType("core/text-color"); // TODO: It would be nice to allow this but it needs more work
  unregisterFormatType("core/language");
  // unregisterFormatType("core/keyboard");
  // unregisterFormatType("core/code");
  // unregisterFormatType("core/superscript");
  // unregisterFormatType("core/subscript");
  // unregisterFormatType("core/underline");
  // unregisterFormatType("core/strikethrough");
  // unregisterFormatType("core/bold");
  // unregisterFormatType("core/italic");
  // unregisterFormatType("core/footnote");

  // Unregister Styles from core blocks
  unregisterBlockStyle("core/quote", "default");
  unregisterBlockStyle("core/quote", "plain");
  unregisterBlockStyle("core/image", "default");
  unregisterBlockStyle("core/image", "rounded");
  unregisterBlockStyle("core/separator", "default");
  unregisterBlockStyle("core/separator", "wide");
  unregisterBlockStyle("core/separator", "dots");
});

/**
 * Filters core blocks to ensure that they can only be used inside the ezpz/content block
 * unless otherwise specified.
 * @see https://developer.wordpress.org/block-editor/developers/filters/block-filters/#using-filters
 * @see https://nickdiego.com/how-to-modify-block-supports-using-client-side-filters/
 */

function ezpzFilterBlockParents(settings, name) {
  let childBlocks = [
    "core/heading",
    "core/table",
    "core/list",
    "core/image",
    "core/quote",
    "core/audio",
    "core/pullquote",
    "core/embed",
    "core/separator",
    "core/html",
    "core/shortcode",
    "core/code",
    "gravityforms/form",
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
  ezpzFilterBlockParents
);

// TODO: Add option for autoplay on core/embed
// TODO: NAtive support for details block for FAQs
// TODO: How to make tables responsive
