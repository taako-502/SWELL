/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useMemo } from '@wordpress/element';
import { registerBlockType } from '@wordpress/blocks';
import {
	RichText,
	InnerBlocks,
	// BlockControls,
	InspectorControls,
} from '@wordpress/block-editor';
// import { PanelBody, RadioControl } from '@wordpress/components';

/**
 * @SWELL dependencies
 */
import example from './_example';
import blockIcon from './_icon';
import { iconColor } from '@swell-guten/config';
import CapBlockSidebar from './_sidebar';

/**
 * @others dependencies
 */
import classnames from 'classnames';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

/**
 * 普通のアイコンと fontawesome を分けるための関数
 */
const sliceIconData = (iconClass) => {
	let iconData;

	// FAだったら配列が返される
	if (null !== iconClass.match(/fas |fab |far /)) {
		iconData = iconClass.split(' ');
		iconData[1] = iconData[1].replace('fa-', '');
		return iconData;
	}

	// FA以外は普通に文字列のまま
	return iconClass;
};

const blockName = 'swell-block-capbox';

/**
 * キャプション付きブロック
 */
registerBlockType('loos/cap-block', {
	title: 'キャプション付きブロック',
	description: __('キャプションタイトル付けのコンテンツを作成できます。', 'swell'),
	icon: {
		foreground: iconColor,
		src: blockIcon,
	},
	category: 'swell-blocks',
	keywords: ['swell', 'cap'],
	supports: {
		className: false,
	},
	styles: [
		{ name: 'default', label: 'デフォルト', isDefault: true },
		{ name: 'small_ttl', label: '小' },
		{ name: 'onborder_ttl', label: '枠上' },
		{ name: 'onborder_ttl2', label: '枠上2' },
		{ name: 'inner', label: '枠内' },
		{ name: 'shadow', label: '浮き出し' },
		{ name: 'intext', label: '内テキスト' },
	],
	attributes: {
		content: {
			type: 'string',
			source: 'html',
			selector: '.cap_box_ttl span',
			default: 'キャプション',
		},
		dataColSet: {
			type: 'string',
			default: '',
		},
		iconName: {
			type: 'string',
			default: '',
		},
	},
	example,
	edit: (props) => {
		const { attributes, setAttributes, className } = props;
		const { iconName, content, dataColSet } = attributes;

		const blockClass = classnames(blockName, 'cap_box', className);

		// アイコン
		const iconTag = useMemo(() => {
			if (!iconName) return null;
			const iconData = sliceIconData(iconName);

			if (typeof iconData === 'string') {
				return <i className={iconName}></i>;
			}
			return <FontAwesomeIcon icon={iconData} />;
		}, [iconName]);

		return (
			<>
				<InspectorControls>
					<CapBlockSidebar {...{ attributes, setAttributes }} />
				</InspectorControls>
				<div className={blockClass} data-colset={dataColSet || null}>
					<div className='cap_box_ttl' data-has-icon={iconName ? '1' : null}>
						{iconTag}
						<RichText
							tagName='span'
							// className='cap_box_ttl'
							placeholder={__('Title', 'swell') + '...'}
							value={content}
							onChange={(newContent) => setAttributes({ content: newContent })}
						/>
					</div>
					<div className='cap_box_content'>
						<InnerBlocks />
					</div>
				</div>
			</>
		);
	},

	save: ({ attributes, className }) => {
		const blockClass = classnames(blockName, 'cap_box', className);
		const { iconName, content, dataColSet } = attributes;

		// アイコン
		let icon = null;
		if (iconName) {
			const iconData = sliceIconData(iconName);
			if (typeof iconData === 'string') {
				icon = <i className={iconName}></i>;
			} else {
				icon = <FontAwesomeIcon icon={iconData} />;
			}
		}

		return (
			<div className={blockClass} data-colset={dataColSet || null}>
				<div className='cap_box_ttl'>
					{icon}
					<RichText.Content
						tagName='span'
						// className='cap_box_ttl'
						value={content}
					/>
				</div>
				<div className='cap_box_content'>
					<InnerBlocks.Content />
				</div>
			</div>
		);
	},
	deprecated: [
		{
			attributes: {
				content: {
					type: 'array',
					source: 'children',
					selector: '.cap_box_ttl',
					default: 'キャプション',
				},
				dataColSet: {
					type: 'string',
					default: '',
				},
			},
			supports: {
				className: false,
			},
			save: ({ attributes, className }) => {
				const blockClass = classnames(blockName, 'cap_box', className);

				let customProps = {};
				if (attributes.dataColSet) {
					customProps = { 'data-colset': attributes.dataColSet };
				}

				return (
					<div className={blockClass} {...customProps}>
						<RichText.Content
							tagName='div'
							className='cap_box_ttl'
							value={attributes.content}
						/>
						<div className='cap_box_content'>
							<InnerBlocks.Content />
						</div>
					</div>
				);
			},
		},
		{
			attributes: {
				content: {
					type: 'array',
					source: 'children',
					selector: '.cap_box_ttl',
					default: 'キャプション',
				},
				dataColSet: {
					type: 'string',
					default: '',
				},
			},
			save: ({ attributes, className }) => {
				let boxClass = 'cap_box';
				if (className) {
					boxClass += ' ' + className;
				}

				let customProps = {};
				if (attributes.dataColSet) {
					customProps = { 'data-colset': attributes.dataColSet };
				}

				return (
					<div className={boxClass} {...customProps}>
						<RichText.Content
							tagName='div'
							className='cap_box_ttl'
							value={attributes.content}
						/>
						<div className='cap_box_content'>
							<InnerBlocks.Content />
						</div>
					</div>
				);
			},
		},
	],
});
