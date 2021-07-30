/**
 * @WordPress dependencies
 */
import { useEffect } from '@wordpress/element';
import { useDispatch, useSelect } from '@wordpress/data';
import { registerBlockType } from '@wordpress/blocks';
import {
	InnerBlocks,
	InspectorControls,
	useBlockProps,
	__experimentalUseInnerBlocksProps as useInnerBlocksProps,
} from '@wordpress/block-editor';
import { PanelBody, RangeControl } from '@wordpress/components';

/**
 * @SWELL dependencies
 */
import metadata from './block.json';
import blockIcon from './_icon';
import getBlockIcon from '@swell-guten/utils/getBlockIcon';

const ALLOWED_BLOCKS = ['loos/dt', 'loos/dd'];
const TEMPLATE = [['loos/dt'], ['loos/dd'], ['loos/dt'], ['loos/dd']];

/**
 * 説明リスト(DL)ブロック
 */
const blockName = 'swell-block-dl';
registerBlockType(metadata.name, {
	icon: getBlockIcon(blockIcon),
	styles: [
		{ name: 'default', label: 'デフォルト', isDefault: true },
		{ name: 'border', label: '左に線' },
		{ name: 'float', label: '横並び' },
		{ name: 'vtabel', label: '縦並び表' },
	],
	edit: ({ attributes, setAttributes, clientId }) => {
		const { dtWidth } = attributes;
		const nowClass = attributes.className || '';
		const isFloat = -1 !== nowClass.indexOf('is-style-float');

		// 横並びスタイル以外の時はdtWidthの指定を消す
		useEffect(() => {
			if (!isFloat) {
				setAttributes({ dtWidth: undefined });
			}
		}, [isFloat]);

		// 子ブロックを取得
		const childBlocks = useSelect(
			(select) => select('core/block-editor').getBlocks(clientId),
			[clientId]
		);

		// 子ブロックにdtWidthを伝達
		const { updateBlockAttributes } = useDispatch('core/block-editor');
		useEffect(() => {
			childBlocks.forEach((child) => {
				updateBlockAttributes(child.clientId, {
					dtWidth,
				});
			});
		}, [dtWidth, childBlocks]);

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
				{isFloat && (
					<InspectorControls>
						<PanelBody title='DTの横幅'>
							<RangeControl
								label='※ em単位'
								value={dtWidth}
								onChange={(val) => {
									setAttributes({ dtWidth: val });
								}}
								min={1}
								max={12}
								step={1}
							/>
						</PanelBody>
					</InspectorControls>
				)}
				<div {...blockProps}>
					<div className='swell-block-parentSelector'>親ブロックを選択</div>
					{innerBlocksProps.children}
				</div>
			</>
		);
	},

	save: () => {
		const blockProps = useBlockProps.save({
			className: blockName,
		});

		return (
			<dl {...blockProps}>
				<InnerBlocks.Content />
			</dl>
		);
	},
});
