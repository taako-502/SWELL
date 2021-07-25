/**
 * @WordPress dependencies
 */
import { RichText, InnerBlocks } from '@wordpress/block-editor';

/**
 * @others dependencies
 */
import classnames from 'classnames';

/**
 * deprecated
 */
const blockName = 'swell-block-capbox';
export default [
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
];
