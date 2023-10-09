/**
 * @see https://github.com/WordPress/gutenberg/blob/wp/5.5/packages/block-library/src/columns/index.js
 * for reference as to how core handles columns
 */

/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
import { registerBlockType } from "@wordpress/blocks";
import { column as icon } from "@wordpress/icons";

/**
 * Internal dependencies
 */
import edit from "./edit";
import save from "./save";
import metadata from "./block.json";

export function colWidthToClassName(width) {
	let className;
	switch (width) {
		case "66.66%":
			className = " md-8";
			break;
		case "50%":
			className = " md-6";
			break;
		case "33.33%":
			className = " md-4";
			break;
		case "25%":
			className = " md-3";
			break;
		case "16.66%":
			className = " md-4 lg-2";
			break;
		default:
			className = "";
			break;
	}
	return "col sm-12" + className;
}

/**
 * Every block starts by registering a new block type definition.
 * @see https://wphelpers.dev/icons/group for icon SVG
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
registerBlockType(metadata.name, {
	icon,
	edit,
	save,
});
