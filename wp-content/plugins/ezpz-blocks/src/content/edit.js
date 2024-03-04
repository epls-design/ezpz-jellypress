/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";
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
		"core/table",
		"core/list",
		// "core/list-item",
		"core/image",
		"core/quote",
		"core/audio",
		"core/pullquote",
		"core/embed",
		"core/separator",
		"core/html",
		"core/shortcode",
		"core/code",
		"gravityforms/form",
	];
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
				allowedBlocks={ALLOWED_BLOCKS}
				template={TEMPLATE}
				templateLock={false}
			/>
		</div>
	);
}
