/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import {
	RichText,
	InnerBlocks,
	InspectorControls,
	useBlockProps,
	__experimentalUseInnerBlocksProps as useInnerBlocksProps,
} from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';

/**
 * @SWELL dependencies
 */
import metadata from './block.json';
import blockIcon from './_icon';
import getBlockIcon from '@swell-guten/utils/getBlockIcon';

const TEMPLATE = [['core/paragraph']];

/**
 * アコーディオン項目ブロック
 */
const blockName = 'swell-block-accordion';
registerBlockType(metadata.name, {
	icon: getBlockIcon(blockIcon),
	edit: ({ attributes, setAttributes }) => {
		const { isDefultOpen } = attributes;

		// ブロックprops
		const blockProps = useBlockProps({
			className: `${blockName}__item`,
			'aria-expanded': true,
		});

		const innerBlocksProps = useInnerBlocksProps(
			{
				className: `${blockName}__body swl-inner-blocks swl-has-margin--s`,
			},
			{
				template: TEMPLATE,
			}
		);

		return (
			<>
				<InspectorControls>
					<PanelBody title='アコーディオン設定'>
						<ToggleControl
							label={__('デフォルトで開いた状態にする', 'swell')}
							checked={isDefultOpen}
							onChange={(value) => {
								setAttributes({ isDefultOpen: value });
							}}
						/>
					</PanelBody>
				</InspectorControls>
				<div {...blockProps}>
					<div className={`${blockName}__title`}>
						<RichText
							tagName='div'
							className={`${blockName}__label`}
							placeholder={__('Text…', 'swell')}
							value={attributes.title}
							onChange={(title) => setAttributes({ title })}
						/>
						<span
							className={`${blockName}__icon c-switchIconBtn`}
							aria-hidden='false'
							data-opened='true'
						>
							<i className={`__icon--closed ${attributes.iconClosed}`}></i>
							<i className={`__icon--opened ${attributes.iconOpened}`}></i>
						</span>
					</div>
					<div {...innerBlocksProps} />
				</div>
			</>
		);
	},
	save: ({ attributes }) => {
		const { isDefultOpen } = attributes;

		// ブロックprops
		const blockProps = useBlockProps.save({
			className: `${blockName}__item`,
			'aria-expanded': isDefultOpen ? true : false,
		});

		return (
			<div {...blockProps}>
				<div className={`${blockName}__title`} data-onclick='toggleAccordion'>
					<span className={`${blockName}__label`}>
						<RichText.Content value={attributes.title} />
					</span>
					<span
						className={`${blockName}__icon c-switchIconBtn`}
						aria-hidden='true'
						data-opened={isDefultOpen ? 'true' : 'false'}
					>
						<i className={`__icon--closed ${attributes.iconClosed}`}></i>
						<i className={`__icon--opened ${attributes.iconOpened}`}></i>
					</span>
				</div>

				<div className={`${blockName}__body`} aria-hidden={isDefultOpen ? 'false' : 'true'}>
					<InnerBlocks.Content />
				</div>
			</div>
		);
	},
});
