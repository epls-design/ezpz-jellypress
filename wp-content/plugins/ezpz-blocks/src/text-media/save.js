/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { InnerBlocks, useBlockProps } from "@wordpress/block-editor";

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
	const { attributes } = props;

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
		<section {...useBlockProps.save()}>
			<div className="container">
				<div className={rowClasses.join(" ")}>
					<InnerBlocks.Content />
				</div>
			</div>
		</section>
	);
};
export default Save;
