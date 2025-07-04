/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { InnerBlocks, useBlockProps } from "@wordpress/block-editor";

// Use the colWidthToClassName function from src/column/index.js
import { colWidthToClassName } from "../column";

/**
 * The save function defines the way in which the different attributes should
 * be combined into the final markup, which is then serialized by the block
 * editor into `post_content`.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#save
 *
 * @return {WPElement} Element to render.
 */

const Save = (props) => {
	let blockProps = useBlockProps.save();

	// Get the width prop
	const columnWidth = props.attributes.width;
	// Append the width class to the blockProps
	blockProps.className += " " + colWidthToClassName(columnWidth);

	// Add the type
	blockProps.className += " type-" + props.attributes.columnContent;

	return (
		<div {...blockProps}>
			<InnerBlocks.Content />
		</div>
	);
};
export default Save;
