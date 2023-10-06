/**
 * This block uses variations to allow the user to select a layout for the columns block.
 * @see https://github.com/WordPress/gutenberg/tree/trunk/packages/block-editor/src/components/block-variation-picker
 * Based on the core columns block.
 */

/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";

import {
	InspectorControls,
	useInnerBlocksProps,
	BlockControls,
	BlockVerticalAlignmentToolbar,
	__experimentalBlockVariationPicker,
	useBlockProps,
	store as blockEditorStore,
} from "@wordpress/block-editor";

import { withDispatch, useDispatch, useSelect } from "@wordpress/data";

import {
	createBlock,
	createBlocksFromInnerBlocksTemplate,
	store as blocksStore,
} from "@wordpress/blocks";

function TestEdit() {
	const ALLOWED_BLOCKS = ["ezpz/column"];
	const TEMPLATE = [
		["ezpz/column", {}],
		["ezpz/column", {}],
	];
	let blockProps = useBlockProps();

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

	const innerBlocksProps = useInnerBlocksProps(blockProps, {
		allowedBlocks: ALLOWED_BLOCKS,
		template: TEMPLATE,
	});

	return (
		<section {...blockProps}>
			<div className="container">
				<div className="row">{innerBlocksProps.children}</div>
			</div>
		</section>
	);
}

function EditColumns({ clientId }) {}

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
	return (
		<div {...blockProps}>
			<__experimentalBlockVariationPicker
				icon={blockType?.icon?.src}
				label={blockType?.title}
				variations={variations}
				instructions="Please select the layout you would like for this block. These layouts apply to larger screens only."
				onSelect={(nextVariation = defaultVariation) => {
					if (nextVariation.attributes) {
						setAttributes(nextVariation.attributes);
					}
					if (nextVariation.innerBlocks) {
						replaceInnerBlocks(
							clientId,
							createBlocksFromInnerBlocksTemplate(nextVariation.innerBlocks),
							true
						);
					}
				}}
				allowSkip
			/>
		</div>
	);
}

const Edit = (props) => {
	const { clientId } = props;
	const hasInnerBlocks = useSelect(
		(select) => select(blockEditorStore).getBlocks(clientId).length > 0,
		[clientId]
	);
	const Component = hasInnerBlocks ? EditColumns : Placeholder;

	return <Component {...props} />;
};

export default Edit;
