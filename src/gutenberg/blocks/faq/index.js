/**
 * @WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import {
	InnerBlocks,
	InspectorControls,
	useBlockProps,
	__experimentalUseInnerBlocksProps as useInnerBlocksProps,
} from '@wordpress/block-editor';

/**
 * @SWELL dependencies
 */
import FaqSidebar from './_sidebar';
import metadata from './block.json';
import blockIcon from './_icon';
import getBlockIcon from '@swell-guten/utils/getBlockIcon';

/**
 * @Others dependencies
 */
import classnames from 'classnames';

const ALLOWED_BLOCKS = ['loos/faq-item'];
const TEMPLATE = [['loos/faq-item'], ['loos/faq-item']];

/**
 * FAQブロック
 */
const blockName = 'swell-block-faq';
registerBlockType(metadata.name, {
	icon: getBlockIcon(blockIcon),
	styles: [
		// ブロック要素のスタイルを設定
		{
			label: 'シンプル',
			name: 'default',
			isDefault: true,
		},
		{
			label: '線あり',
			name: 'faq-border',
		},
		{
			label: 'ボックス',
			name: 'faq-box',
		},
		{
			label: 'ストライプ',
			name: 'faq-stripe',
		},
	],
	edit: ({ attributes, setAttributes }) => {
		const { iconRadius, qIconStyle, aIconStyle } = attributes;

		// ブロックprops
		const blockProps = useBlockProps({
			className: classnames(blockName, 'swl-inner-blocks', {
				[`-icon-${iconRadius}`]: !!iconRadius,
			}),
			'data-q': qIconStyle,
			'data-a': aIconStyle,
		});
		const innerBlocksProps = useInnerBlocksProps(
			{},
			{
				allowedBlocks: ALLOWED_BLOCKS,
				template: TEMPLATE,
				templateLock: false,
				renderAppender: InnerBlocks.ButtonBlockAppender,
			}
		);

		return (
			<>
				<InspectorControls>
					<FaqSidebar {...{ attributes, setAttributes }} />
				</InspectorControls>
				<div {...blockProps}>
					<div className='swell-block-parentSelector'>親ブロックを選択</div>
					{innerBlocksProps.children}
				</div>
			</>
		);
	},

	save: ({ attributes }) => {
		const { iconRadius, qIconStyle, aIconStyle } = attributes;

		// ブロックprops
		const blockProps = useBlockProps.save({
			className: classnames(blockName, {
				[`-icon-${iconRadius}`]: !!iconRadius,
			}),
			'data-q': qIconStyle,
			'data-a': aIconStyle,
		});

		return (
			<dl {...blockProps}>
				<InnerBlocks.Content />
			</dl>
		);
	},
});
