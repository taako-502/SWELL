/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import { RichText, InnerBlocks, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';
// import { PanelBody, RadioControl } from '@wordpress/components';

/**
 * @SWELL dependencies
 */
import blockIcon from './_icon';
import { iconColor } from '@swell-guten/config';

/**
 * @Others dependencies
 */
import classnames from 'classnames';

/**
 * アコーディオン項目ブロック
 */
const blockName = 'swell-block-accordion';
registerBlockType('loos/accordion-item', {
	title: '項目',
	icon: {
		foreground: iconColor,
		src: blockIcon.accordionItem,
	},
	category: 'swell-blocks',
	parent: ['loos/accordion'],
	supports: {
		className: false,
		reusable: false,
	},
	attributes: {
		title: {
			type: 'string',
			source: 'html',
			selector: '.swell-block-accordion__label',
			default: '',
		},
		iconOpened: {
			type: 'string',
			default: 'icon-arrow_drop_up',
		},
		iconClosed: {
			type: 'string',
			default: 'icon-arrow_drop_down',
		},
		isDefultOpen: {
			type: 'boolean',
			default: false,
		},
	},
	edit: (props) => {
		const { className, attributes, setAttributes } = props;
		const { isDefultOpen } = attributes;
		const blockClass = classnames(className, `${blockName}__item`);

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
				<div className={blockClass} aria-expanded='true'>
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
					<div className={`${blockName}__body`}>
						<InnerBlocks />
					</div>
				</div>
			</>
		);
	},
	save: ({ attributes }) => {
		const { isDefultOpen } = attributes;

		return (
			<div className={`${blockName}__item`} aria-expanded={isDefultOpen ? 'true' : 'false'}>
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
