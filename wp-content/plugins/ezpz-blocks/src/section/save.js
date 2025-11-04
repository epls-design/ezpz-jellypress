import { InnerBlocks } from "@wordpress/block-editor";

const Save = () => {
	// Just return the inner blocks content, no wrapper at all as we use a view.php template to render the block dynamically.
	return <InnerBlocks.Content />;
};

export default Save;
