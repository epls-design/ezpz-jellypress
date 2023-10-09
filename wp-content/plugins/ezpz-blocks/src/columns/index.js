/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */

/**
 * WordPress dependencies
 */
import { registerBlockType } from "@wordpress/blocks";
import { columns as icon } from "@wordpress/icons";

/**
 * Internal dependencies
 */
import edit from "./edit";
import save from "./save";
import metadata from "./block.json";
import variations from "./variations";

/**
 * Every block starts by registering a new block type definition.
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
registerBlockType(metadata.name, {
	icon,
	variations,
	edit,
	save,
});
// TODO: Add an example
