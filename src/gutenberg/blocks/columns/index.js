/**
 * @WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import {
	BlockControls,
	InspectorControls,
	BlockVerticalAlignmentToolbar,
	InnerBlocks,
	useBlockProps,
	__experimentalUseInnerBlocksProps as useInnerBlocksProps,
} from '@wordpress/block-editor';
import {
	PanelBody,
	RangeControl,
	BaseControl,
	Flex,
	FlexItem,
	FlexBlock,
} from '@wordpress/components';
import { Icon, mobile, tablet, desktop } from '@wordpress/icons';

/**
 * @SWELL dependencies
 */
import metadata from './block.json';
import blockIcon from './_icon';
import { getColumnBasis } from './_helper';
import UnitNumber from '@swell-guten/components/UnitNumber';
import getBlockIcon from '@swell-guten/utils/getBlockIcon';

const ALLOWED_BLOCKS = ['loos/column'];
const TEMPLATE = [['loos/column'], ['loos/column']];

/**
 * リッチカラム
 */
const blockName = 'swell-block-columns';
registerBlockType(metadata.name, {
	icon: getBlockIcon(blockIcon),
	styles: [
		{ name: 'default', label: 'デフォルト', isDefault: true },
		{ name: 'shadow', label: 'シャドウ' },
	],
	edit: (props) => {
		const { attributes, setAttributes } = props;
		const { vAlign, colPC, colTab, colMobile, margin } = attributes;

		// ブロックprops
		const blockProps = useBlockProps({
			className: blockName,
			style: {
				'--swl-fb': '1' !== colMobile ? getColumnBasis(colMobile) : null,
				'--swl-fb_tab': '2' !== colTab ? getColumnBasis(colTab) : null,
				'--swl-fb_pc': '2' !== colPC ? getColumnBasis(colPC) : null,
				'--swl-clmn-mrgn--x': '0.75rem' !== margin.x ? margin.x : null,
				'--swl-clmn-mrgn--bttm': '1.5rem' !== margin.bottom ? margin.bottom : null,
			},
			'data-valign': vAlign || null,
		});

		// 左右marginの関係でカラムブロックは一つdivかませる
		const innerBlocksProps = useInnerBlocksProps(
			{
				className: `${blockName}__inner`,
			},
			{
				allowedBlocks: ALLOWED_BLOCKS,
				template: TEMPLATE,
				templateLock: false,
				orientation: 'horizontal',
				renderAppender: InnerBlocks.ButtonBlockAppender,
			}
		);

		return (
			<>
				<BlockControls>
					<BlockVerticalAlignmentToolbar
						onChange={(value) => {
							setAttributes({ vAlign: value });
						}}
						value={vAlign}
					/>
				</BlockControls>
				<InspectorControls>
					<PanelBody title='設定'>
						<BaseControl>
							<BaseControl.VisualLabel>列数</BaseControl.VisualLabel>
							<Flex>
								<FlexItem style={{ marginRight: '4px', marginBottom: '8px' }}>
									<Icon icon={desktop} />
								</FlexItem>
								<FlexBlock>
									<RangeControl
										value={parseInt(colPC)}
										onChange={(val) => {
											setAttributes({ colPC: val + '' });
										}}
										min={1}
										max={8}
									/>
								</FlexBlock>
							</Flex>
							<Flex>
								<FlexItem style={{ marginRight: '4px', marginBottom: '8px' }}>
									<Icon icon={tablet} />
								</FlexItem>
								<FlexBlock>
									<RangeControl
										value={parseInt(colTab)}
										onChange={(val) => {
											setAttributes({ colTab: val + '' });
										}}
										min={1}
										max={8}
									/>
								</FlexBlock>
							</Flex>
							<Flex>
								<FlexItem style={{ marginRight: '4px', marginBottom: '8px' }}>
									<Icon icon={mobile} />
								</FlexItem>
								<FlexBlock>
									<RangeControl
										value={parseInt(colMobile)}
										onChange={(val) => {
											setAttributes({ colMobile: val + '' });
										}}
										min={1}
										max={6}
									/>
								</FlexBlock>
							</Flex>
						</BaseControl>
						<BaseControl>
							<BaseControl.VisualLabel>
								カラム間の余白 (<code style={{ fontSize: '12px' }}>margin:</code>)
							</BaseControl.VisualLabel>
							<Flex>
								<FlexItem style={{ minWidth: '4em', marginRight: 'auto' }}>
									left & right
								</FlexItem>
								<FlexBlock style={{ flex: '0 1 auto' }}>
									<UnitNumber
										value={margin.x}
										onChange={(newVal) => {
											setAttributes({ margin: { ...margin, x: newVal } });
										}}
									/>
								</FlexBlock>
							</Flex>
							<Flex style={{ marginTop: '8px' }}>
								<FlexItem style={{ minWidth: '4em', marginRight: 'auto' }}>
									bottom
								</FlexItem>
								<FlexBlock style={{ flex: '0 1 auto' }}>
									<UnitNumber
										value={margin.bottom}
										onChange={(newVal) => {
											setAttributes({
												margin: { ...margin, bottom: newVal },
											});
										}}
									/>
								</FlexBlock>
							</Flex>
						</BaseControl>
					</PanelBody>
				</InspectorControls>
				{/* 左右marginの関係でカラムブロックは一つdivかませる */}
				<div {...blockProps}>
					<div className='swell-block-parentSelector'>親ブロックを選択</div>
					<div {...innerBlocksProps} />
				</div>
			</>
		);
	},

	save: ({ attributes }) => {
		const { vAlign, colPC, colTab, colMobile, margin } = attributes;

		// ブロックprops
		const blockProps = useBlockProps.save({
			className: blockName,
			style: {
				'--swl-fb': '1' !== colMobile ? getColumnBasis(colMobile) : null,
				'--swl-fb_tab': '2' !== colTab ? getColumnBasis(colTab) : null,
				'--swl-fb_pc': '2' !== colPC ? getColumnBasis(colPC) : null,
				'--swl-clmn-mrgn--x': '0.75rem' !== margin.x ? margin.x : null,
				'--swl-clmn-mrgn--bttm': '1.5rem' !== margin.bottom ? margin.bottom : null,
			},
			'data-valign': vAlign || null,
		});
		return (
			<div {...blockProps}>
				<div className={`${blockName}__inner`}>
					<InnerBlocks.Content />
				</div>
			</div>
		);
	},
});
