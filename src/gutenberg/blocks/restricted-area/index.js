/**
 * @WordPress dependencies
 */
import { memo } from '@wordpress/element';
import { registerBlockType } from '@wordpress/blocks';
import {
	InnerBlocks,
	InspectorControls,
	useBlockProps,
	__experimentalUseInnerBlocksProps as useInnerBlocksProps,
} from '@wordpress/block-editor';
import { BaseControl, PanelBody, ToggleControl, CheckboxControl } from '@wordpress/components';

/**
 * @SWELL dependencies
 */
import metadata from './block.json';
import blockIcon from './_icon';
import getBlockIcon from '@swell-guten/utils/getBlockIcon';

const TEMPLATE = [['core/paragraph']];

const SettingArea = memo(({ attributes, setAttributes }) => {
	const { administrator, editor, author, contributor, subscriber, nonLoggedin } = attributes;

	return (
		<InspectorControls>
			<PanelBody title='表示の制限' initialOpen={true}>
				<BaseControl className='checkbox_group'>
					<BaseControl.VisualLabel>
						{'コンテンツを見ることのできるユーザー'}
					</BaseControl.VisualLabel>
					<ToggleControl
						label='非ログインユーザー'
						checked={nonLoggedin}
						onChange={(val) => setAttributes({ nonLoggedin: val })}
					/>
					<div data-swl-disable={nonLoggedin || null}>
						<CheckboxControl
							label='管理者'
							checked={administrator}
							onChange={(val) => setAttributes({ administrator: val })}
						/>
						<CheckboxControl
							label='編集者'
							checked={editor}
							onChange={(val) => setAttributes({ editor: val })}
						/>
						<CheckboxControl
							label='投稿者'
							checked={author}
							onChange={(val) => setAttributes({ author: val })}
						/>
						<CheckboxControl
							label='寄稿者'
							checked={contributor}
							onChange={(val) => setAttributes({ contributor: val })}
						/>
						<CheckboxControl
							label='購読者'
							checked={subscriber}
							onChange={(val) => setAttributes({ subscriber: val })}
						/>
					</div>
				</BaseControl>
				<BaseControl>
					<BaseControl.VisualLabel>{'日時範囲指定'}</BaseControl.VisualLabel>
				</BaseControl>
			</PanelBody>
		</InspectorControls>
	);
});

/**
 * 制限エリア
 */
const blockName = 'swell-block-restrictedArea';
registerBlockType(metadata.name, {
	icon: getBlockIcon(blockIcon),
	edit: ({ attributes, setAttributes }) => {
		// ブロックprops
		const blockProps = useBlockProps({
			className: `${blockName} swl-inner-blocks swl-has-margin--s`,
		});

		const innerBlocksProps = useInnerBlocksProps(blockProps, {
			template: TEMPLATE,
			templateLock: false,
		});

		return (
			<>
				<SettingArea {...{ attributes, setAttributes }} />
				<div {...innerBlocksProps} />
			</>
		);
	},
	save: () => {
		return <InnerBlocks.Content />;
	},
});
