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
		innerBlocks: [["ezpz/column"], ["ezpz/column"]],
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
	{
		name: "two-columns-two-thirds-one-third",
		title: __("66 / 33"),
		description: __("Two columns; two-thirds, one-third split"),
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
					d="M39 12C40.1046 12 41 12.8954 41 14V34C41 35.1046 40.1046 36 39 36H9C7.89543 36 7 35.1046 7 34V14C7 12.8954 7.89543 12 9 12H39ZM39 34V14H30V34H39ZM28 34H9V14H28V34Z"
				/>
			</SVG>
		),
		innerBlocks: [
			["ezpz/column", { width: "66.66%" }],
			["ezpz/column", { width: "33.33%" }],
		],
		scope: ["block"],
	},
	{
		name: "three-columns-equal",
		title: __("33 / 33 / 33"),
		description: __("Three columns; equal split"),
		icon: (
			<SVG
				width="48"
				height="48"
				viewBox="0 0 48 48"
				xmlns="http://www.w3.org/2000/svg"
			>
				<Path
					fillRule="evenodd"
					d="M41 14a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v20a2 2 0 0 0 2 2h30a2 2 0 0 0 2-2V14zM28.5 34h-9V14h9v20zm2 0V14H39v20h-8.5zm-13 0H9V14h8.5v20z"
				/>
			</SVG>
		),
		innerBlocks: [["ezpz/column"], ["ezpz/column"], ["ezpz/column"]],
		scope: ["block"],
	},
	{
		name: "four-columns-equal",
		title: __("25/25/25/25"),
		description: __("Four columns; equal split"),
		icon: (
			<SVG
				width="48"
				height="48"
				viewBox="0 0 48 48"
				xmlns="http://www.w3.org/2000/svg"
			>
				<Path
					d="M39,12H9c-1.1,0-2,0.9-2,2v20c0,1.1,0.9,2,2,2h30c1.1,0,2-0.9,2-2V14C41,12.9,40.1,12,39,12z M11.2,34H9V14h2.2H15v20H11.2z
	 M19.5,34h-2H17V14h0.5h2H23v20H19.5z M30.5,34h-2H25V14h3.5h2H31v20H30.5z M39,34h-0.9H33V14h5.1H39V34z"
				/>
			</SVG>
		),
		innerBlocks: [
			["ezpz/column"],
			["ezpz/column"],
			["ezpz/column"],
			["ezpz/column"],
		],
		scope: ["block"],
	},
];

export default variations;
