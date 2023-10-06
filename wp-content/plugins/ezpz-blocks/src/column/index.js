/**
 * @see https://github.com/WordPress/gutenberg/blob/wp/5.5/packages/block-library/src/columns/index.js
 * for reference as to how core handles columns
 * TODO: Add in example block
 */

/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
import { registerBlockType } from "@wordpress/blocks";
import { columns as icon } from "@wordpress/icons";

/**
 * Internal dependencies
 */
import Edit from "./edit";
import save from "./save";
import metadata from "./block.json";

/**
 * Every block starts by registering a new block type definition.
 * @see https://wphelpers.dev/icons/group for icon SVG
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
registerBlockType(metadata.name, {
	icon,
	/**
	 * @see ./edit.js
	 */
	edit: Edit,

	/**
	 * @see ./save.js
	 */
	save,
});

// TODO: Change the renderAppender
