/**
 * WordPress dependencies
 */
import { PlainText } from "@wordpress/block-editor";
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
export default function Edit({ context: { postType, postId } }) {
	const [rawTitle = "", setTitle, fullTitle] = useEntityProp(
		"postType",
		postType,
		"title",
		postId,
	);

	// INITIAL STATE BEFORE LOAD
	let titleElement = <h1>{__("Title", "ezpz-post-title")}</h1>;

	if (postType && postId) {
		titleElement = (
			<PlainText
				tagName="h1"
				placeholder={__("No Title", "ezpz-post-title")}
				value={rawTitle}
				onChange={setTitle}
				__experimentalVersion={2}
			/>
		);
	}

	return <>{titleElement}</>;
}
