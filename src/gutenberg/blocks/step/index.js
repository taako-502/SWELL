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

/**
 * @SWELL dependencies
 */
import StepSidebar from './_sidebar';
import metadata from './block.json';
import blockIcon from './_icon';
import getBlockIcon from '@swell-guten/utils/getBlockIcon';

const ALLOWED_BLOCKS = ['loos/step-item'];
const TEMPLATE = [['loos/step-item'], ['loos/step-item']];

/**
 * STEPブロック
 */
const blockName = 'swell-block-step';
registerBlockType(metadata.name, {
	icon: getBlockIcon(blockIcon),
	styles: [
		{ name: 'default', label: 'デフォルト', isDefault: true },
		{ name: 'big', label: 'ビッグ' },
		{ name: 'small', label: 'スモール' },
	],

	edit: ({ attributes, setAttributes, clientId }) => {
		const { stepLabel, stepClass, numShape, numLayout, startNum } = attributes;
		const nowClass = attributes.className || '';

		// ブロックスタイルに応じた attribute
		useEffect(() => {
			if (-1 !== nowClass.indexOf('is-style-big')) {
				setAttributes({ stepClass: 'big' });
			} else if (-1 !== nowClass.indexOf('is-style-small')) {
				setAttributes({ stepClass: 'small' });
			} else {
				setAttributes({ stepClass: 'default' });
			}
		}, [nowClass]);

		// 子ブロックを取得
		const childBlocks = useSelect(
			(select) => select('core/block-editor').getBlocks(clientId),
			[clientId]
		);

		// 子ブロックの更新
		const { updateBlockAttributes } = useDispatch('core/block-editor');
		useEffect(() => {
			childBlocks.forEach((child) => {
				updateBlockAttributes(child.clientId, {
					stepLabel,
					stepClass,
				});
			});
		}, [childBlocks, stepClass, stepLabel]);

		// ブロックprops
		const blockProps = useBlockProps({
			className: `${blockName} swl-inner-blocks`,
			style: 1 < startNum ? { counterReset: `step ${startNum - 1}` } : null,
			'data-num-style': 'big' === stepClass ? numLayout : numShape,
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
					<StepSidebar {...{ attributes, setAttributes }} />
				</InspectorControls>
				<div {...blockProps}>
					<div className='swell-block-parentSelector'>親ブロックを選択</div>
					{innerBlocksProps.children}
				</div>
			</>
		);
	},

	save: ({ attributes }) => {
		const { startNum, stepClass, numLayout, numShape } = attributes;

		// ブロックprops
		const blockProps = useBlockProps.save({
			className: blockName,
			style: 1 < startNum ? { counterReset: `step ${startNum - 1}` } : null,
			'data-num-style': 'big' === stepClass ? numLayout : numShape,
		});

		return (
			<div {...blockProps}>
				<InnerBlocks.Content />
			</div>
		);
	},
});
