/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
import { registerBlockType } from "@wordpress/blocks";
import { postContent as icon } from "@wordpress/icons";

/**
 * Internal dependencies
 */
import edit from "./edit";
import save from "./save";
import metadata from "./block.json";

// Note: the block.json has the allowResize prop set to false, so that inner blocks like core/image can't be resized.
// @see https://wordpress.org/support/topic/remove-image-size-preset-settings-in-core-image-block/

/**
 * Every block starts by registering a new block type definition.
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
registerBlockType(metadata.name, {
	icon,
	edit,
	save,
});
