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
	Notice,
	PanelBody,
	RangeControl,
	ToggleControl,
} from "@wordpress/components";

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

/**
 * Allowed blocks constant is passed to InnerBlocks precisely as specified here.
 * The contents of the array should never change.
 * The array should contain the name of each block that is allowed.
 * In columns block, the only block we allow is 'core/column'.
 */
const ALLOWED_BLOCKS = ["ezpz/column"];

function TestEdit() {
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

function EditColumns({
	attributes,
	setAttributes,
	updateAlignment,
	updateColumns,
	clientId,
}) {
	const { isStackedOnMobile, verticalAlignment, templateLock } = attributes;
	// console.log(isStackedOnMobile);
	// console.log(verticalAlignment);
	// console.log(templateLock);

	const { count, canInsertColumnBlock, minCount } = useSelect(
		(select) => {
			const { canInsertBlockType, canRemoveBlock, getBlocks, getBlockCount } =
				select(blockEditorStore);
			const innerBlocks = getBlocks(clientId);

			// Get the indexes of columns for which removal is prevented.
			// The highest index will be used to determine the minimum column count.
			const preventRemovalBlockIndexes = innerBlocks.reduce(
				(acc, block, index) => {
					if (!canRemoveBlock(block.clientId)) {
						acc.push(index);
					}
					return acc;
				},
				[]
			);

			return {
				count: getBlockCount(clientId),
				canInsertColumnBlock: canInsertBlockType("core/column", clientId),
				minCount: Math.max(...preventRemovalBlockIndexes) + 1,
			};
		},
		[clientId]
	);
	// console.log(count);
	// console.log(canInsertColumnBlock);
	// console.log(minCount);

	const blockProps = useBlockProps({});
	const innerBlocksProps = useInnerBlocksProps(blockProps, {
		allowedBlocks: ALLOWED_BLOCKS,
		orientation: "horizontal",
		renderAppender: false,
		templateLock,
	});
	// console.log(blockProps);
	// console.log(innerBlocksProps);

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

	return (
		<>
			<BlockControls>
				<BlockVerticalAlignmentToolbar
					onChange={updateAlignment}
					value={verticalAlignment}
				/>
			</BlockControls>
			<InspectorControls>
				<PanelBody>
					{canInsertColumnBlock && (
						<>
							<RangeControl
								__nextHasNoMarginBottom
								__next40pxDefaultSize
								label={__("Columns")}
								value={count}
								onChange={(value) =>
									updateColumns(count, Math.max(minCount, value))
								}
								min={Math.max(1, minCount)}
								max={Math.max(6, count)}
							/>
							{count > 6 && (
								<Notice status="warning" isDismissible={false}>
									{__(
										"This column count exceeds the recommended amount and may cause visual breakage."
									)}
								</Notice>
							)}
						</>
					)}
					<ToggleControl
						__nextHasNoMarginBottom
						label={__("Stack on mobile")}
						checked={isStackedOnMobile}
						onChange={() =>
							setAttributes({
								isStackedOnMobile: !isStackedOnMobile,
							})
						}
					/>
				</PanelBody>
			</InspectorControls>
			<section {...blockProps}>
				<div className="container">
					<div className="row">{innerBlocksProps.children}</div>
				</div>
			</section>
		</>
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

	return (
		<div {...blockProps}>
			<__experimentalBlockVariationPicker
				icon={blockType?.icon?.src}
				label={blockType?.title}
				variations={variations}
				instructions="Please select the layout you would like for this block. These layouts apply to larger screens only."
				onSelect={(nextVariation = defaultVariation) => {
					console.log(nextVariation);
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
