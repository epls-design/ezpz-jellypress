/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from "@wordpress/i18n";

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { InnerBlocks, useBlockProps } from "@wordpress/block-editor";

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit() {
	const ALLOWED_BLOCKS = [
		"core/heading",
		"core/paragraph",
		"core/list",
		"ezpz/buttons",
	];
	const TEMPLATE = [
		[
			"core/heading",
			{ placeholder: "Click here to add an optional sub heading", level: 2 },
		],
		[
			"core/paragraph",
			{
				placeholder:
					"Click here to add an optional preamble to this block. You can also add buttons for a call to action.",
			},
		],
	];
	let blockProps = useBlockProps();
	return (
		<div {...blockProps}>
			<InnerBlocks
				allowedBlocks={ALLOWED_BLOCKS}
				template={TEMPLATE}
				templateLock={false}
			/>
		</div>
	);
}