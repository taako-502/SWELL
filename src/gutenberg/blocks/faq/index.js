/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import { InnerBlocks, InspectorControls } from '@wordpress/block-editor';

/**
 * @SWELL dependencies
 */
import blockIcon from './_icon';
import example from './_example';
import FaqSidebar from './_sidebar';
import { iconColor } from '@swell-guten/config';

/**
 * @Others dependencies
 */
import classnames from 'classnames';

/**
 * FAQブロック
 */
const blockName = 'swell-block-faq';
registerBlockType('loos/faq', {
	title: 'FAQ',
	description: __('Q&A形式のコンテンツを簡単に設置できます。', 'swell'),
	icon: {
		foreground: iconColor,
		src: blockIcon.faq,
	},
	category: 'swell-blocks',
	keywords: ['swell', 'faq', 'qa'],
	supports: {
		anchor: true,
		className: false, //ブロック要素を作成した際に付く .wp-block-[ブロック名]で自動生成されるクラス名の設定。
	},
	example,
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
	attributes: {
		iconRadius: {
			type: 'string',
			default: '',
		},
		qIconStyle: {
			type: 'string',
			default: 'col-text',
		},
		aIconStyle: {
			type: 'string',
			default: 'col-text',
		},
	},

	edit: ({ attributes, setAttributes, className }) => {
		const { iconRadius, qIconStyle, aIconStyle } = attributes;

		let blockClass = classnames(className, blockName);
		if (iconRadius) {
			blockClass = classnames(blockClass, `-icon-${iconRadius}`);
		}

		return (
			<>
				<InspectorControls>
					<FaqSidebar {...{ attributes, setAttributes }} />
				</InspectorControls>
				<div className={blockClass} data-q={qIconStyle} data-a={aIconStyle}>
					<div className='swell-block-parentSelector'>親ブロックを選択</div>
					<InnerBlocks
						allowedBlocks={['loos/faq-item', 'loos/faq-q', 'loos/faq-a']}
						templateLock={false}
						template={[['loos/faq-item'], ['loos/faq-item']]}
						renderAppender={InnerBlocks.ButtonBlockAppender}
					/>
				</div>
			</>
		);
	},

	save: (props) => {
		const { iconRadius, qIconStyle, aIconStyle } = props.attributes;

		let blockClass = blockName;
		if (iconRadius) {
			blockClass = classnames(blockClass, `-icon-${iconRadius}`);
		}
		return (
			<dl className={blockClass} data-q={qIconStyle} data-a={aIconStyle}>
				<InnerBlocks.Content />
			</dl>
		);
	},
});
