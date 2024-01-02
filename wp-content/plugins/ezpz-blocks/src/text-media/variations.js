/**
 * WordPress dependencies
 */
import { Path, SVG } from "@wordpress/components";
import { __ } from "@wordpress/i18n";
import { Icon, video, image } from "@wordpress/icons";

/** @typedef {import('@wordpress/blocks').WPBlockVariation} WPBlockVariation */

/**
 * Template option choices for predefined columns layouts.
 *
 * @type {WPBlockVariation[]}
 */
const variations = [
	{
		name: "text-video",
		title: __("Video"),
		icon: <Icon icon={video} />,
		isDefault: true,
		innerBlocks: [
			[
				"ezpz/column",
				{
					width: "40%",
					defaultContent: [["core/embed"]],
				},
			],
			[
				"ezpz/column",
				{
					width: "50%",
					restrictContent: true,
				},
			],
		],
		scope: ["block"],
	},
	{
		name: "text-image",
		title: __("Image"),
		icon: <Icon icon={image} />,
		isDefault: true,
		innerBlocks: [
			[
				"ezpz/column",
				{
					width: "40%",
					defaultContent: [["core/image"]],
				},
			],
			[
				"ezpz/column",
				{
					width: "50%",
					restrictContent: true,
				},
			],
		],
		scope: ["block"],
	},
];

export default variations;
