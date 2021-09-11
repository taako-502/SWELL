/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useMemo } from '@wordpress/element';
import { registerBlockType } from '@wordpress/blocks';
import {
	RichText,
	InnerBlocks,
	InspectorControls,
	useBlockProps,
	__experimentalUseInnerBlocksProps as useInnerBlocksProps,
} from '@wordpress/block-editor';

/**
 * @SWELL dependencies
 */
import metadata from './block.json';
import deprecated from './deprecated';
import blockIcon from './_icon';
import CapBlockSidebar from './_sidebar';
import getBlockIcon from '@swell-guten/utils/getBlockIcon';
import sliceIconData from '@swell-guten/utils/sliceIconData';

/**
 * @others dependencies
 */
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

/**
 * キャプションのアイコンを取得
 */
const getIconTag = (iconName) => {
	if (!iconName) return null;
	const iconData = sliceIconData(iconName);

	if (typeof iconData === 'string') {
		return <i className={iconName}></i>;
	}
	return <FontAwesomeIcon icon={iconData} />;
};

/**
 * キャプションボックス
 */
const blockName = 'swell-block-capbox';
registerBlockType(metadata.name, {
	icon: getBlockIcon(blockIcon),
	styles: [
		{ name: 'default', label: 'デフォルト', isDefault: true },
		{ name: 'small_ttl', label: '小' },
		{ name: 'onborder_ttl', label: '枠上' },
		{ name: 'onborder_ttl2', label: '枠上2' },
		{ name: 'inner', label: '枠内' },
		{ name: 'shadow', label: '浮き出し' },
		{ name: 'intext', label: '内テキスト' },
	],
	// example,
	edit: (props) => {
		const { attributes, setAttributes } = props;
		const { iconName, content, dataColSet } = attributes;

		// アイコン
		const iconTag = useMemo(() => {
			return getIconTag(iconName);
		}, [iconName]);

		// ブロックprops
		const blockProps = useBlockProps({
			className: `${blockName} cap_box`,
			'data-colset': dataColSet || null,
		});

		const innerBlocksProps = useInnerBlocksProps(
			{
				className: 'cap_box_content swl-inner-blocks swl-has-margin--s',
			},
			{}
		);

		return (
			<>
				<InspectorControls>
					<CapBlockSidebar {...{ attributes, setAttributes }} />
				</InspectorControls>
				<div {...blockProps}>
					<div className='cap_box_ttl' data-has-icon={iconName ? '1' : null}>
						{iconTag}
						<RichText
							tagName='span'
							placeholder={__('Title', 'swell') + '...'}
							value={content}
							onChange={(newContent) => setAttributes({ content: newContent })}
						/>
					</div>
					<div {...innerBlocksProps} />
				</div>
			</>
		);
	},

	save: ({ attributes }) => {
		const { iconName, content, dataColSet } = attributes;

		// アイコン
		const iconTag = getIconTag(iconName);

		// ブロックprops
		const blockProps = useBlockProps.save({
			className: `${blockName} cap_box`,
			'data-colset': dataColSet || null,
		});

		return (
			<div {...blockProps}>
				<div className='cap_box_ttl'>
					{iconTag}
					<RichText.Content tagName='span' value={content} />
				</div>
				<div className='cap_box_content'>
					<InnerBlocks.Content />
				</div>
			</div>
		);
	},
	deprecated,
});
