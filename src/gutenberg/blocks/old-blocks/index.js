/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { createBlock, registerBlockType } from '@wordpress/blocks';
import { InnerBlocks } from '@wordpress/block-editor';

/**
 * @SWELL dependencies
 */
import blockIcon from './_icon';

/**
 * 装飾ブロック
 */
const attrs = {
	dataLayout: {
		type: 'string',
		default: '',
	},
};

registerBlockType('loos/style-block', {
	title: '装飾ブロック',
	icon: {
		src: blockIcon.styleBlock,
	},
	category: 'swell-blocks',
	keywords: ['swell', 'style'],
	attributes: attrs,
	supports: {
		inserter: false,
		className: false,
	},
	transforms: {
		to: [
			//どのブロックタイプへ変更できるようにするか
			{
				type: 'block',
				blocks: ['core/group'],
				transform: (attributes, innerBlocks) => {
					let classNames = attributes.className || '';
					const returnAttrData = {};
					if (-1 !== classNames.indexOf('center_box')) {
						classNames = classNames.replace('center_box', 'u-ta-c');
						// returnAttrData.align = 'center';
					}

					returnAttrData.className = classNames;
					return createBlock(
						'core/group',
						returnAttrData,
						innerBlocks
					);
				},
			},
		],
	},

	edit: (props) => {
		const { attributes, className } = props;

		//通常のエディター内
		return (
			<>
				<div
					className='loos_style_block u-block-guide'
					data-layout={attributes.dataLayout}
				>
					<div className='swl-obsoleteBlocks'>
						※
						このブロックは廃止されました。グループブロックに置き換えてください。
					</div>
					<div className={className}>
						<InnerBlocks />
					</div>
				</div>
			</>
		);
	},
	save: () => {
		return (
			<div className='swell-block-style'>
				<InnerBlocks.Content />
			</div>
		);
	},

	deprecated: [
		{
			attributes: attrs,
			supports: {
				className: false,
			},
			save: (props) => {
				//save時のprops.classNameは高度な設定のclassは含まれない
				return (
					<div className={props.className}>
						<InnerBlocks.Content />
					</div>
				);
			},
		},
	],
});
