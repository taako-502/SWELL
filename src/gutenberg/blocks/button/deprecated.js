/**
 * @WordPress dependencies
 */
import { RichText } from '@wordpress/block-editor';
import { RawHTML } from '@wordpress/element';

/**
 * @others dependencies
 */
import classnames from 'classnames';

/**
 * deprecated
 */
export default [
	{
		attributes: {
			content: {
				type: 'array',
				source: 'children',
				selector: 'a',
			},
			hrefUrl: {
				type: 'string',
				default: '',
			},
			isNewTab: {
				type: 'boolean',
				default: false,
			},
			rel: {
				type: 'string',
				source: 'attribute',
				selector: 'a',
				attribute: 'rel',
			},
			imgUrl: {
				type: 'string',
				source: 'attribute',
				selector: 'img',
				attribute: 'src',
			},
			btnAlign: {
				type: 'string',
				default: '',
			},
			htmlTags: {
				type: 'string',
				source: 'html',
				selector: '.swell-block-button.-html',
				default: '',
			},
			isCount: {
				type: 'boolean',
				default: false,
			},
			btnId: {
				type: 'string',
				source: 'attribute',
				selector: '.swell-block-button',
				attribute: 'data-id',
			},
		},
		supports: {
			className: false,
		},
		save: ({ attributes }) => {
			const { btnAlign, htmlTags, btnId } = attributes;
			const hasHtml = !!htmlTags;
			let saveHTML = '';
			if (hasHtml) {
				saveHTML = (
					<div
						className={classnames('swell-block-button', '-html')}
						data-align={btnAlign || null}
						data-id={btnId || null}
					>
						<RawHTML>{htmlTags}</RawHTML>
					</div>
				);
			} else {
				saveHTML = (
					<div
						className='swell-block-button'
						data-align={btnAlign || null}
						data-id={btnId || null}
					>
						<RichText.Content
							tagName='a'
							className='swell-block-button__link'
							value={attributes.content}
							href={attributes.hrefUrl}
							target={attributes.isNewTab ? '_blank' : null}
							rel={attributes.rel || null}
						/>
						{attributes.imgUrl && (
							<img
								src={attributes.imgUrl}
								className='swell-block-button__img'
								width='1'
								height='1'
								alt=''
							/>
						)}
					</div>
				);
			}
			return saveHTML;
		},
	},
];
