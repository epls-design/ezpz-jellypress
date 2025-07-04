"use strict";
var addFilter = wp.hooks.addFilter,
  createHigherOrderComponent = wp.compose.createHigherOrderComponent,
  Fragment = wp.element.Fragment,
  InspectorControls = wp.blockEditor.InspectorControls,
  PanelBody = wp.components.PanelBody,
  __ = wp.i18n.__,
  RadioControl = wp.components.RadioControl,
  _wp$blocks = wp.blocks,
  unregisterBlockStyle = _wp$blocks.unregisterBlockStyle,
  getBlockType = _wp$blocks.getBlockType,
  unregisterFormatType = wp.richText.unregisterFormatType;
function ezpzGetAllowedBlocks(e) {
  e = getBlockType(e);
  if (!e || !e.edit) return [];
  try {
    return e.edit.allowedBlocks || [];
  } catch (e) {
    return [];
  }
}
var allowedInContent = ezpzGetAllowedBlocks("ezpz/content"),
  allowedInContentPreamble = ezpzGetAllowedBlocks("ezpz/content-restricted");
function ezpzAddAttributesCoreEmbed(e, t) {
  return "core/embed" === t
    ? lodash.assign({}, e, {
        attributes: lodash.merge(e.attributes, {
          aspectRatio: { type: "string", default: "16x9" },
        }),
      })
    : e;
}
addFilter(
  "blocks.registerBlockType",
  "ezpz/core/embed/attributes",
  ezpzAddAttributesCoreEmbed
);
var ezpzAddInspectorControlCoreEmbed = createHigherOrderComponent(function (r) {
  return function (e) {
    var t = e.attributes.aspectRatio,
      o = e.setAttributes;
    return "core/embed" !== e.name
      ? React.createElement(r, e)
      : React.createElement(
          Fragment,
          null,
          React.createElement(r, e),
          React.createElement(
            InspectorControls,
            null,
            React.createElement(
              PanelBody,
              { title: __("Embed settings", "jellypress"), initialOpen: !0 },
              React.createElement(RadioControl, {
                label: __("Aspect Ratio", "jellypress"),
                help: __(
                  "Select the aspect ratio of the embed (applies to video only)",
                  "jellypress"
                ),
                selected: t,
                options: [
                  { label: "16x9", value: "16x9" },
                  { label: "21x9", value: "21x9" },
                  { label: "1x1", value: "1x1" },
                  { label: "4x3", value: "4x3" },
                ],
                onChange: function (e) {
                  o({ aspectRatio: e });
                },
              })
            )
          )
        );
  };
}, "ezpzAddInspectorControlCoreEmbed");
function ezpzFilterBlockParents(e, t) {
  var o;
  return "widgets" === window.pagenow
    ? ["core/heading", "core/list", "core/image", "core/paragraph"].includes(t)
      ? lodash.assign({}, e, { parent: ["ezpz/widget-content"] })
      : e
    : "post" == window.pagenow
    ? e.parent &&
      (e.parent.includes("ezpz/content") ||
        e.parent.includes("ezpz/content-restricted"))
      ? lodash.assign({}, e, { parent: void 0 })
      : e
    : "core/paragraph" === t ||
      ((o = e.parent || []),
      allowedInContent.includes(t) &&
        !o.includes("ezpz/content") &&
        o.push("ezpz/content"),
      allowedInContentPreamble.includes(t) &&
        !o.includes("ezpz/content-restricted") &&
        o.push("ezpz/content-restricted"),
      0 === o.length)
    ? e
    : ((e.parent = o), lodash.assign({}, e, {}));
}
addFilter(
  "editor.BlockEdit",
  "ezpz/core/embed/inspector-controls",
  ezpzAddInspectorControlCoreEmbed
),
  addFilter(
    "blocks.registerBlockType",
    "ezpz/core/remove-supports",
    function (e, t) {
      return [
        "core/heading",
        "core/code",
        "core/pullquote",
        "core/table",
        "core/audio",
        "core/separator",
        "core/embed",
        "core/image",
      ].includes(t)
        ? lodash.assign({}, e, {
            supports: lodash.assign({}, e.supports, { align: !1 }),
          })
        : e;
    }
  ),
  window.wp.domReady(function () {
    unregisterFormatType("core/image"),
      unregisterFormatType("core/text-color"),
      unregisterFormatType("core/language"),
      unregisterBlockStyle("core/quote", "default"),
      unregisterBlockStyle("core/quote", "plain"),
      unregisterBlockStyle("core/image", "default"),
      unregisterBlockStyle("core/image", "rounded"),
      unregisterBlockStyle("core/separator", "default"),
      unregisterBlockStyle("core/separator", "wide"),
      unregisterBlockStyle("core/separator", "dots");
  }),
  wp.hooks.addFilter(
    "blocks.registerBlockType",
    "ezpz/content",
    ezpzFilterBlockParents
  );
