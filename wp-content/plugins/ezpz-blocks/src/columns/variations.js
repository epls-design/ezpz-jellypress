/**
 * WordPress dependencies
 */
import { Path, SVG } from "@wordpress/components";
import { __ } from "@wordpress/i18n";

/** @typedef {import('@wordpress/blocks').WPBlockVariation} WPBlockVariation */

/**
 * Template option choices for predefined columns layouts.
 *
 * @type {WPBlockVariation[]}
 */
const variations = [
	{
		name: "two-columns-equal",
		title: __("50 / 50"),
		description: __("Two columns; equal split"),
		icon: (
			<SVG
				width="48"
				height="48"
				viewBox="0 0 48 48"
				xmlns="http://www.w3.org/2000/svg"
			>
				<Path
					fillRule="evenodd"
					clipRule="evenodd"
					d="M39 12C40.1046 12 41 12.8954 41 14V34C41 35.1046 40.1046 36 39 36H9C7.89543 36 7 35.1046 7 34V14C7 12.8954 7.89543 12 9 12H39ZM39 34V14H25V34H39ZM23 34H9V14H23V34Z"
				/>
			</SVG>
		),
		isDefault: true,
		innerBlocks: [
			["ezpz/column", { width: "50%" }],
			["ezpz/column", { width: "50%" }],
		],
		scope: ["block"],
	},
	{
		name: "two-columns-one-third-two-thirds",
		title: __("33 / 66"),
		description: __("Two columns; one-third, two-thirds split"),
		icon: (
			<SVG
				width="48"
				height="48"
				viewBox="0 0 48 48"
				xmlns="http://www.w3.org/2000/svg"
			>
				<Path
					fillRule="evenodd"
					clipRule="evenodd"
					d="M39 12C40.1046 12 41 12.8954 41 14V34C41 35.1046 40.1046 36 39 36H9C7.89543 36 7 35.1046 7 34V14C7 12.8954 7.89543 12 9 12H39ZM39 34V14H20V34H39ZM18 34H9V14H18V34Z"
				/>
			</SVG>
		),
		innerBlocks: [
			["ezpz/column", { width: "33.33%" }],
			["ezpz/column", { width: "66.66%" }],
		],
		scope: ["block"],
	},
];

export default variations;
