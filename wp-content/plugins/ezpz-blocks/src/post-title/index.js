/**
 * Cloned from core/post-title but removes all of the options we don't want the editor to
 * update, such as adding a link to the title, or changing heading level. It would be nice if
 * Wordpress allowed us to do this with filters, but here we are...
 */

/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */

/**
 * WordPress dependencies
 */
import { registerBlockType } from "@wordpress/blocks";
import { title as icon } from "@wordpress/icons";

/**
 * Internal dependencies
 */
import edit from "./edit";
import save from "./save";
import metadata from "./block.json";

/**
 * Every block starts by registering a new block type definition.
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
registerBlockType(metadata.name, {
	icon,
	edit,
	save,
});
