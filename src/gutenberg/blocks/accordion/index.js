/**
 * @WordPress dependencies
 */
import { useEffect, useCallback } from '@wordpress/element';
import { useDispatch, useSelect } from '@wordpress/data';
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
import metadata from './block.json';
import blockIcon from './_icon';
import getBlockIcon from '@swell-guten/utils/getBlockIcon';
import AcordionSidebar from './_sidebar';

const ALLOWED_BLOCKS = ['accordion-item'];
const TEMPLATE = [['accordion-item']];

/**
 * アコーディオンブロック
 */
const blockName = 'swell-block-accordion';
registerBlockType(metadata.name, {
	icon: getBlockIcon(blockIcon),
	styles: [
		{ name: 'default', label: 'デフォルト', isDefault: true },
		{ name: 'simple', label: 'シンプル' },
		{ name: 'border', label: '囲い枠' },
		{ name: 'main', label: 'メインカラー' },
	],
	edit: ({ attributes, setAttributes, clientId }) => {
		// 子ブロックを取得
		const childBlocks = useSelect(
			(select) => select('core/block-editor').getBlocks(clientId),
			[clientId]
		);

		// 子ブロックの更新に使う
		const { updateBlockAttributes } = useDispatch('core/block-editor');

		const updateIcons = useCallback(
			(newIconObj) => {
				// 自分を更新
				setAttributes(newIconObj);

				// 子ブロックにもアイコン設定を伝達
				childBlocks.forEach((child) => {
					updateBlockAttributes(child.clientId, newIconObj);
				});
			},
			[childBlocks]
		);

		// 子ブロックが追加された時もアイコン同期できるように。
		useEffect(() => {
			updateIcons({
				iconOpened: attributes.iconOpened,
				iconClosed: attributes.iconClosed,
			});
		}, [childBlocks]);

		// ブロックprops
		const blockProps = useBlockProps({
			className: `${blockName} swl-inner-blocks`,
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
					<AcordionSidebar iconOpened={attributes.iconOpened} updateIcons={updateIcons} />
				</InspectorControls>
				<div {...blockProps}>
					<div className='swell-block-parentSelector'>親ブロックを選択</div>
					{innerBlocksProps.children}
				</div>
			</>
		);
	},

	save: () => {
		// ブロックprops
		const blockProps = useBlockProps.save({
			className: blockName,
		});

		return (
			<div {...blockProps}>
				<InnerBlocks.Content />
			</div>
		);
	},
});
