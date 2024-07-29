/**
 * WordPress dependencies
 */
import { useBlockProps, RichText } from "@wordpress/block-editor";
import { __ } from "@wordpress/i18n";
import { useEntityProp } from "@wordpress/core-data";

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit({
	context: { postType, postId },
	attributes,
	setAttributes,
}) {
	const [rawTitle = "", setTitle, fullTitle] = useEntityProp(
		"postType",
		postType,
		"title",
		postId,
	);

	const blockProps = useBlockProps();

	const { titleText } = attributes;

	const settitleText = (value) => {
		console.log(value);
		setAttributes({ titleText: value });
	};

	// Default to rawTitle if titleText is empty
	const displayTitle = titleText || rawTitle;

	let titleElement = <h1>{__("Title", "ezpz-post-title")}</h1>;

	if (postType && postId) {
		titleElement = (
			<RichText
				{...blockProps}
				identifier="title"
				tagName="h1"
				placeholder={__("No Title", "ezpz-post-title")}
				value={displayTitle}
				onChange={settitleText}
				allowedFormats={["core/bold"]}
				__experimentalVersion={2}
			/>
		);
	}

	return <>{titleElement}</>;
}
