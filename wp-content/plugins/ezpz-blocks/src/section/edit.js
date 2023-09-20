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
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import "./editor.scss";

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit() {
	const ALLOWED_BLOCKS = ["ezpz/content"];
	const TEMPLATE = [["ezpz/content", {}]];
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

	return (
		<section {...blockProps}>
			<div className="container">
				<div className="row justify-center">
					<div className="col md-10 lg-8">
						<InnerBlocks
							allowedBlocks={ALLOWED_BLOCKS}
							template={TEMPLATE}
							templateLock="all"
						/>
					</div>
				</div>
			</div>
		</section>
	);
}
