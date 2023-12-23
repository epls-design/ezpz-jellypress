/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";
import { InnerBlocks, useBlockProps } from "@wordpress/block-editor";

// Use the colWidthToClassName function from src/column/index.js
import { colWidthToClassName } from "../column";

/**
 * The edit function describes the structure of your block in the context of the editor. This
 * represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * Note: Width is an attribute added in block.json. The columns block sets this on the creation
 * of the layout, based on the variation selected. It is not necessary for the editor to be
 * able to customise the width post-creation.
 *
 * @return {WPElement} Element to render.
 */
export default function Edit({ attributes: { width, defaultContent } }) {
	let ALLOWED_BLOCKS;
	let TEMPLATE;

	if (defaultContent && defaultContent.length > 0) {
		// Include locked template block if defaultContent is not empty
		TEMPLATE = defaultContent.map(([blockName, blockAttributes]) => [
			blockName,
			// { ...blockAttributes },
		]);
		// Push the template to ALLOWED BLOCKS
		ALLOWED_BLOCKS = [...TEMPLATE.map(([blockName]) => blockName)];
	} else {
		// Include unlocked ezpz/content block if defaultContent is empty
		TEMPLATE = [["ezpz/content", {}]];
		ALLOWED_BLOCKS = ["ezpz/content"];
	}

	// Get blockProps
	const blockProps = useBlockProps();

	// Merge into blockProps.className
	blockProps.className =
		blockProps.className + " " + colWidthToClassName(width);

	return (
		<div {...blockProps}>
			<InnerBlocks
				allowedBlocks={ALLOWED_BLOCKS}
				template={TEMPLATE}
				templateLock="all"
			/>
		</div>
	);
}
