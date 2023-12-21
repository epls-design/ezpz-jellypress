"use strict";

// Import dependencies
var addFilter = wp.hooks.addFilter;
var createHigherOrderComponent = wp.compose.createHigherOrderComponent;
var Fragment = wp.element.Fragment;
var InspectorControls = wp.blockEditor.InspectorControls;
var PanelBody = wp.components.PanelBody;
var __ = wp.i18n.__;
var RadioControl = wp.components.RadioControl;
var unregisterBlockStyle = wp.blocks.unregisterBlockStyle;
var unregisterFormatType = wp.richText.unregisterFormatType;

/**
 * Adds custom Aspect Ratio attribute to core/embed block
 */
function jellypressAddAttributesCoreEmbed(settings, name) {
  if (name === "core/embed") {
    return lodash.assign({}, settings, {
      attributes: lodash.merge(settings.attributes, {
        aspectRatio: {
          type: "string",
          "default": "16x9"
        }
      })
    });
  }
  return settings;
}
addFilter("blocks.registerBlockType", "jellypress/core/embed/attributes", jellypressAddAttributesCoreEmbed);

/**
 * Adds settings panel for custom attribute AspectRatio on core/embed block
 * @see https://css-tricks.com/a-crash-course-in-wordpress-block-filters/
 * @see https://developer.wordpress.org/block-editor/developers/filters/block-filters/#using-filters
 */
var jellypressAddInspectorControlCoreEmbed = createHigherOrderComponent(function (BlockEdit) {
  return function (props) {
    var aspectRatio = props.attributes.aspectRatio,
      setAttributes = props.setAttributes,
      name = props.name;
    if (name !== "core/embed") {
      return /*#__PURE__*/React.createElement(BlockEdit, props);
    }
    return /*#__PURE__*/React.createElement(Fragment, null, /*#__PURE__*/React.createElement(BlockEdit, props), /*#__PURE__*/React.createElement(InspectorControls, null, /*#__PURE__*/React.createElement(PanelBody, {
      title: __("Embed settings", "jellypress"),
      initialOpen: true
    }, /*#__PURE__*/React.createElement(RadioControl, {
      label: __("Aspect Ratio", "jellypress"),
      help: __("Select the aspect ratio of the embed (applies to video only)", "jellypress"),
      selected: aspectRatio,
      options: [{
        label: "16x9",
        value: "16x9"
      }, {
        label: "21x9",
        value: "21x9"
      }, {
        label: "1x1",
        value: "1x1"
      }, {
        label: "4x3",
        value: "4x3"
      }],
      onChange: function onChange(value) {
        setAttributes({
          aspectRatio: value
        });
      }
    }))));
  };
}, "jellypressAddInspectorControlCoreEmbed");
addFilter("editor.BlockEdit", "jellypress/core/embed/inspector-controls", jellypressAddInspectorControlCoreEmbed);

/**
 * Removes the align option from core blocks
 */
addFilter("blocks.registerBlockType", "ezpz/core/remove-align", function (settings, name) {
  var removeFrom = ["core/heading", "core/code", "core/pullquote", "core/table", "core/audio", "core/separator", "core/embed"];
  if (removeFrom.includes(name)) {
    return lodash.assign({}, settings, {
      supports: lodash.assign({}, settings.supports, {
        align: false
      })
    });
  }
  return settings;
});

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

function jellypressFilterBlockParents(settings, name) {
  var childBlocks = ["core/heading", "core/table", "core/list", "core/quote", "core/audio", "core/pullquote", "core/embed", "core/separator", "core/html", "core/shortcode", "core/code", "gravityforms/form"];

  // Bail if not in array of child blocks
  if (!childBlocks.includes(name)) return settings;

  // Return the block with a forced parent block, and the updated supports
  return lodash.assign({}, settings, {
    parent: ["ezpz/content"]
  });
}
wp.hooks.addFilter("blocks.registerBlockType", "ezpz/content", jellypressFilterBlockParents);

/**
 * TODOS
 * - Add option for autoplay on core/block
 * - GET RID OF ALL IMAGE FILTER STUFF
 */