/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { InnerBlocks } from '@wordpress/block-editor';

import rgbHex from 'rgb-hex';

export default [
	{
		attributes: {
			bgColor: {
				type: 'string',
				default: '#f7f7f7',
			},
			textColor: {
				type: 'string',
				default: '',
			},
			fullwide_bg: {
				type: 'string',
				default: '',
			},
			blockID: {
				type: 'string',
				default: '',
			},
			isOpenBgPopover: {
				type: 'string',
				default: '',
			},
		},
		supports: {
			className: true,
		},

		migrate: (attributes) => {
			// rgbaの背景色を hex と opacityに分解
			let bgColor = attributes.bgColor;
			let bgOpacity = attributes.fullwide_bg ? 0 : 100;
			const rgbaData = bgColor.split(',');
			if (3 < rgbaData.length) {
				const r = parseInt(rgbaData[0].replace('rgba(', ''));
				const g = parseInt(rgbaData[1]);
				const b = parseInt(rgbaData[2]);

				bgColor = `#${rgbHex(r, g, b)}`;
				bgOpacity = parseFloat(rgbaData[3].replace(')', '')) * 100;
			}

			return {
				anchor: attributes.blockID,
				bgColor,
				textColor: attributes.textColor,
				bgImageUrl: attributes.fullwide_bg,
				bgImageID: 0,
				bgOpacity,
				contentSize: 'article',
				isFixBg: false,
				pcPadding: '60',
				spPadding: '40',
				topSvgLevel: 0,
				bottomSvgLevel: 0,
			};
		},

		save: ({ attributes, className }) => {
			let blockData = {};
			const styleDta = {};
			let blockClass = className;
			const blockID = attributes.blockID;

			if (blockClass === undefined || !blockClass) {
				blockClass = '';
			}

			//
			if (attributes.textColor) {
				styleDta.color = attributes.textColor;
			}

			if (attributes.fullwide_bg) {
				blockData = {
					className: blockClass + ' lazyload',
					style: styleDta,
					'data-bg': attributes.fullwide_bg,
				};
			} else {
				styleDta.backgroundColor = attributes.bgColor;
				blockData = {
					className: blockClass,
					style: styleDta,
				};
			}

			if (blockID) {
				blockData.id = blockID;
			}

			return (
				<div {...blockData}>
					<InnerBlocks.Content />
				</div>
			);
		},
	},
];
