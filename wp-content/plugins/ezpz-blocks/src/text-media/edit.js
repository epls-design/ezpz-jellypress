/**
 * This block uses variations to allow the user to select a layout for the block.
 * @see https://github.com/WordPress/gutenberg/tree/trunk/packages/block-editor/src/components/block-variation-picker
 * Based on the core columns block.
 */

/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";

import {
	useInnerBlocksProps,
	__experimentalBlockVariationPicker as BlockVariationPicker,
	useBlockProps,
	BlockControls,
	BlockVerticalAlignmentToolbar,
	store as blockEditorStore,
} from "@wordpress/block-editor";

import { useDispatch, useSelect } from "@wordpress/data";

import {
	createBlocksFromInnerBlocksTemplate,
	store as blocksStore,
} from "@wordpress/blocks";

const ALLOWED_BLOCKS = ["ezpz/column"];

function EditColumns({ attributes, setAttributes }) {
	const blockProps = useBlockProps({});
	const innerBlocksProps = useInnerBlocksProps(blockProps, {
		allowedBlocks: ALLOWED_BLOCKS,
		orientation: "horizontal",
		renderAppender: false,
		templateLock: "insert",
	});

	// Explode blockProps.className
	let classes = blockProps.className.split(" ");
	// If any of the classes look like has-*-background-color, get the bit in the wildcard
	// and add it to the classes array with a prefix of bg-* to match the theme classes
	classes.forEach((className) => {
		if (className.match(/has-(.*)-background-color/)) {
			classes.push("bg-" + RegExp.$1);
			// Remove the original class
			classes = classes.filter((c) => c !== className);
		}
	});
	// If the block has no background color set, add a class of bg-white
	if (!classes.some((c) => c.match(/bg-/))) {
		classes.push("bg-white");
	}

	classes.push("block"); // Add the block class to match the theme styles

	// Set the new classes
	blockProps.className = classes.join(" ");

	if (attributes.outerContainer) {
		blockProps.className += " is-" + attributes.outerContainer;
	}

	// Push align class to row
	let rowClasses = ["row", "justify-between"];
	if (attributes.verticalAlign) {
		// Change 'center' to 'middle' to match the theme class
		if (attributes.verticalAlign === "center") {
			attributes.verticalAlign = "middle";
		}
		rowClasses.push("align-" + attributes.verticalAlign);
	}

	return (
		<section {...blockProps}>
			<BlockControls>
				<BlockVerticalAlignmentToolbar
					value={attributes.verticalAlign}
					onChange={(value) => {
						setAttributes({ verticalAlign: value });
					}}
				/>
			</BlockControls>
			<div className="container">
				<div className={rowClasses.join(" ")}>{innerBlocksProps.children}</div>
			</div>
		</section>
	);
}

/**
 * Placeholder component shows when the block is first added. It allows the editor to specify the layout they would like for the columns block.
 */
function Placeholder({ clientId, name, setAttributes }) {
	/**
	 * Sets up the block variations for the block type.
	 * @returns blockType {Object} The block type object.
	 * @returns defaultVariation {Object} The default variation object.
	 * @returns variations {Object} All variations available
	 */
	const { blockType, defaultVariation, variations } = useSelect(
		(select) => {
			// Gets these functions from the blocks store
			const { getBlockVariations, getBlockType, getDefaultBlockVariation } =
				select(blocksStore);

			return {
				blockType: getBlockType(name),
				defaultVariation: getDefaultBlockVariation(name, "block"),
				variations: getBlockVariations(name, "block"),
			};
		},
		[name]
	);

	const { replaceInnerBlocks } = useDispatch(blockEditorStore);
	const blockProps = useBlockProps();

	/**
	 * Outputs a variation picker. This allows the user to select a layout for the columns block.
	 * OnSelect, the block attributes and inner blocks are updated.
	 */
	return (
		<div {...blockProps}>
			<BlockVariationPicker
				icon={blockType?.icon?.src}
				label={blockType?.title}
				variations={variations}
				instructions="What type of media element are you embedding?"
				onSelect={(nextVariation = defaultVariation) => {
					if (nextVariation.attributes) {
						setAttributes(...nextVariation.attributes);
					}
					// Save the variation as a block attribute
					if (nextVariation.name) {
						setAttributes({
							outerContainer: nextVariation.name,
						});
					}
					if (nextVariation.innerBlocks) {
						replaceInnerBlocks(
							clientId,
							createBlocksFromInnerBlocksTemplate(nextVariation.innerBlocks),
							true,
						);
					}
				}}
			/>
		</div>
	);
}

const Edit = (props) => {
	// Check if this block has inner blocks.
	const { clientId, attributes } = props;
	const hasInnerBlocks = useSelect(
		(select) => select(blockEditorStore).getBlocks(clientId).length > 0,
		[clientId],
	);

	// Determines which component to render based on whether the block has inner blocks or not.
	const Component = hasInnerBlocks ? EditColumns : Placeholder;

	return <Component {...props} />;
};

export default Edit;
