/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";
import { InnerBlocks, useBlockProps } from "@wordpress/block-editor";
import blockMetadata from "./block.json";

const ALLOWED_BLOCKS_EZPZCONTENT = blockMetadata.allowedInnerBlocks || [
	"core/heading",
	"core/paragraph",
];

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
function Edit() {
	const TEMPLATE = [
		[
			"core/paragraph",
			{
				placeholder:
					"Start typing or paste your text here. Type / to see all available blocks.",
			},
		],
	];
	let blockProps = useBlockProps();
	return (
		<div {...blockProps}>
			<InnerBlocks
				allowedBlocks={ALLOWED_BLOCKS_EZPZCONTENT}
				template={TEMPLATE}
				templateLock={false}
			/>
		</div>
	);
}

Edit.allowedBlocks = ALLOWED_BLOCKS_EZPZCONTENT;

export default Edit;
