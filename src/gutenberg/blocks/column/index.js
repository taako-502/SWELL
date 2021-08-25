/**
 * @WordPress dependencies
 */
import { useSelect } from '@wordpress/data';
import { useState } from '@wordpress/element';
import { registerBlockType } from '@wordpress/blocks';
import {
	BlockControls,
	InspectorControls,
	InnerBlocks,
	BlockVerticalAlignmentToolbar,
	useBlockProps,
	__experimentalUseInnerBlocksProps as useInnerBlocksProps,
} from '@wordpress/block-editor';
import {
	PanelBody,
	RangeControl,
	ToggleControl,
	Tooltip,
	Button,
	BaseControl,
	Flex,
	FlexItem,
	FlexBlock,
} from '@wordpress/components';
import { Icon, mobile, tablet, desktop, link, linkOff } from '@wordpress/icons';

/**
 * @SWELL dependencies
 */
import metadata from './block.json';
import blockIcon from './_icon';
import getBlockIcon from '@swell-guten/utils/getBlockIcon';
import PaddingControl from '@swell-guten/components/PaddingControl';

/**
 * @Others dependencies
 */
import classnames from 'classnames';

/**
 * see: https://github.com/WordPress/gutenberg/blob/899286307b/packages/components/src/box-control/linked-button.js
 */
const LinkedButton = ({ isLinked, ...props }) => {
	const label = isLinked ? '個別に指定する' : '同じ値を使用する';

	return (
		<Tooltip text={label}>
			<span className='__link'>
				<Button
					{...props}
					className='component-box-control__linked-button'
					isPrimary={isLinked}
					isSecondary={!isLinked}
					isSmall
					icon={isLinked ? link : linkOff}
					iconSize={16}
					aria-label={label}
				/>
			</span>
		</Tooltip>
	);
};

/**
 * カラム項目
 */
const blockName = 'swell-block-column';
registerBlockType(metadata.name, {
	icon: getBlockIcon(blockIcon),
	attributes: metadata.attributes,
	edit: (props) => {
		const { attributes, setAttributes, clientId } = props;
		const { vAlign, widthPC, widthTab, widthMobile, isBreakAll, padding, useCustomPadding } =
			attributes;

		const [isLinked, setIsLinked] = useState(false);

		// 子ブロックの設定
		const blockClass = classnames(blockName, 'swl-inner-blocks', 'swl-has-margin--s', {
			'is-breadk-all': isBreakAll,
		});

		const columnStyle = {};
		if (widthMobile) {
			columnStyle['--swl-fb'] = widthMobile + '%';
		}
		if (widthTab) {
			columnStyle['--swl-fb_tab'] = widthTab + '%';
		}
		if (widthPC) {
			columnStyle['--swl-fb_pc'] = widthPC + '%';
		}
		if (widthPC) {
			columnStyle['--swl-fb_pc'] = widthPC + '%';
		}

		// 内部padding
		if (useCustomPadding) {
			const pad = `${padding.top} ${padding.right} ${padding.bottom} ${padding.left}`;
			columnStyle['--swl-clmn-pddng'] = pad;
		}

		const hasChildBlocks = useSelect(
			(select) => {
				const { getBlockOrder } = select('core/block-editor');
				return getBlockOrder(clientId).length > 0;
			},
			[clientId]
		);

		// ブロックprops
		const blockProps = useBlockProps({
			className: blockClass,
			'data-valign': vAlign || null,
			style: columnStyle || null,
		});

		const innerBlocksProps = useInnerBlocksProps(blockProps, {
			templateLock: false,
			renderAppender: hasChildBlocks ? undefined : InnerBlocks.ButtonBlockAppender,
		});

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
							<BaseControl.VisualLabel>
								<Flex style={{ paddingBottom: '4px' }}>
									<FlexItem style={{ marginRight: 'auto' }}>
										カラム横幅 [%]
									</FlexItem>
									<FlexItem>
										<LinkedButton
											onClick={() => {
												setIsLinked(!isLinked);
											}}
											isLinked={isLinked}
										/>
									</FlexItem>
								</Flex>
							</BaseControl.VisualLabel>
							<Flex align='flex-start'>
								<FlexItem style={{ marginRight: '4px', marginTop: '4px' }}>
									<Icon icon={desktop} />
								</FlexItem>
								<FlexBlock>
									<RangeControl
										className='swl-range--useReset -colmun'
										value={widthPC}
										onChange={(val) => {
											if (isLinked) {
												setAttributes({
													widthPC: val,
													widthTab: val,
													widthMobile: val,
												});
											} else {
												setAttributes({ widthPC: val });
											}
										}}
										min={10}
										max={100}
										allowReset={true}
									/>
								</FlexBlock>
							</Flex>
							<Flex align='flex-start'>
								<FlexItem style={{ marginRight: '4px', marginTop: '4px' }}>
									<Icon icon={tablet} />
								</FlexItem>
								<FlexBlock>
									<RangeControl
										className='swl-range--useReset -colmun'
										value={widthTab}
										onChange={(val) => {
											if (isLinked) {
												setAttributes({
													widthPC: val,
													widthTab: val,
													widthMobile: val,
												});
											} else {
												setAttributes({ widthTab: val });
											}
										}}
										min={10}
										max={100}
										allowReset={true}
									/>
								</FlexBlock>
							</Flex>
							<Flex align='flex-start'>
								<FlexItem style={{ marginRight: '4px', marginTop: '4px' }}>
									<Icon icon={mobile} />
								</FlexItem>
								<FlexBlock>
									<RangeControl
										className='swl-range--useReset -colmun'
										value={widthMobile}
										onChange={(val) => {
											if (isLinked) {
												setAttributes({
													widthPC: val,
													widthTab: val,
													widthMobile: val,
												});
											} else {
												setAttributes({ widthMobile: val });
											}
										}}
										min={10}
										max={100}
										allowReset={true}
									/>
								</FlexBlock>
							</Flex>
						</BaseControl>
						<ToggleControl
							label='表示範囲に合わせて強制的に文字列を改行する'
							help='“word-break: break-all” が適用されます。'
							checked={isBreakAll}
							onChange={(val) => {
								setAttributes({ isBreakAll: val });
							}}
						/>
					</PanelBody>
					<PanelBody title='余白設定'>
						<ToggleControl
							label='カスタムパディングを使用する'
							checked={useCustomPadding}
							onChange={(val) => {
								setAttributes({ useCustomPadding: val });
							}}
						/>
						<div data-swl-disable={!useCustomPadding || null}>
							<PaddingControl
								name='padding'
								value={padding}
								setAttributes={setAttributes}
							/>
						</div>
					</PanelBody>
				</InspectorControls>
				<div {...innerBlocksProps} />
			</>
		);
	},
	save: ({ attributes }) => {
		const { vAlign, widthPC, widthTab, widthMobile, isBreakAll, useCustomPadding, padding } =
			attributes;

		const columnStyle = {};
		if (widthMobile) {
			columnStyle['--swl-fb'] = widthMobile + '%';
		}
		if (widthTab) {
			columnStyle['--swl-fb_tab'] = widthTab + '%';
		}
		if (widthPC) {
			columnStyle['--swl-fb_pc'] = widthPC + '%';
		}

		// 内部padding
		if (useCustomPadding) {
			const pad = `${padding.top} ${padding.right} ${padding.bottom} ${padding.left}`;
			columnStyle['--swl-clmn-pddng'] = pad;
		}

		// ブロックprops
		const blockProps = useBlockProps.save({
			className: classnames(blockName, {
				'is-breadk-all': isBreakAll,
			}),
			'data-valign': vAlign || null,
			style: columnStyle || null,
		});

		return (
			<div {...blockProps}>
				<InnerBlocks.Content />
			</div>
		);
	},
});
