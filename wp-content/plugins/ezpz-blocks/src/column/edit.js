/**
 * WordPress dependencies
 */
import { sprintf, __ } from "@wordpress/i18n";

import {
	InnerBlocks,
	BlockControls,
	BlockVerticalAlignmentToolbar,
	InspectorControls,
	useBlockProps,
	useSetting,
	useInnerBlocksProps,
	store as blockEditorStore,
} from "@wordpress/block-editor";

import { PanelBody, SelectControl } from "@wordpress/components";
import { useSelect, useDispatch } from "@wordpress/data";

function widthToClass(width) {
	switch (width) {
		case "66.66%":
			return "md-8";
		case "50%":
			return "md-6";
		case "33.33%":
			return "md-4";
		case "25%":
			return "md-3";
		default:
			return "";
	}
}

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit({
	attributes: { width },
	setAttributes,
	clientId,
}) {
	const ALLOWED_BLOCKS = ["ezpz/content"];
	const TEMPLATE = [["ezpz/content", {}]];

	// Get blockProps
	const blockProps = useBlockProps();

	let colClass = "col sm-12";
	// console.log("blockProps", blockProps);

	// Get the width property of this column
	const columnWidth = width;

	// Add to colClass
	colClass += " " + widthToClass(columnWidth);

	// Merge into blockProps.className
	blockProps.className = blockProps.className + " " + colClass;

	return (
		<>
			<InspectorControls>
				<PanelBody title={__("Column settings")}>
					<SelectControl
						label={__("Width (tablet and up))")}
						value={width || ""}
						options={[
							{ label: "Two thirds", value: "66.66%" },
							{ label: "Half", value: "50%" },
							{ label: "One third", value: "33.33%" },
							{ label: "One quarter", value: "25%" },
						]}
						onChange={(newWidth) => {
							setAttributes({ width: newWidth });
						}}
					/>
				</PanelBody>
			</InspectorControls>
			<div {...blockProps}>
				<InnerBlocks
					allowedBlocks={ALLOWED_BLOCKS}
					template={TEMPLATE}
					templateLock="all"
				/>
			</div>
		</>
	);
}

// TODO CHANGE THE RENDERAPPENDER
