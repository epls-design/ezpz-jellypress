// Import dependencies
const { addFilter } = wp.hooks;
const { createHigherOrderComponent } = wp.compose;
const { Fragment } = wp.element;
const { InspectorControls } = wp.blockEditor;
const { PanelBody } = wp.components;
const { __ } = wp.i18n;
const { RadioControl } = wp.components;

/**
 * Adds custom Aspect Ratio attribute to core/embed block
 */
function jellypressAddAttributesCoreEmbed(settings, name) {
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
  "jellypress/core/embed/attributes",
  jellypressAddAttributesCoreEmbed
);

/**
 * Adds settings panel for custom attribute AspectRatio on core/embed block
 * @see https://css-tricks.com/a-crash-course-in-wordpress-block-filters/
 * @see https://developer.wordpress.org/block-editor/developers/filters/block-filters/#using-filters
 */
const jellypressAddInspectorControlCoreEmbed = createHigherOrderComponent(
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
  "jellypressAddInspectorControlCoreEmbed"
);

addFilter(
  "editor.BlockEdit",
  "jellypress/core/embed/inspector-controls",
  jellypressAddInspectorControlCoreEmbed
);

/**
 * Removes the align option from core blocks
 */
addFilter(
  "blocks.registerBlockType",
  "ezpz/core/remove-align",
  function (settings, name) {
    let removeFrom = ["core/embed"];
    if (removeFrom.includes(name)) {
      return lodash.assign({}, settings, {
        supports: lodash.assign({}, settings.supports, {
          align: false,
        }),
      });
    }
    return settings;
  }
);

/***** TODO NEED TO REVIEW FROM HERE  ********/

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
    // "core/paragraph",
    "core/heading",
    "core/list",
    "core/shortcode",
    "core/table",
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
  filterBlockParents
);

/**
 * TODOS
 * - Add option for autoplay on core/block
 */
