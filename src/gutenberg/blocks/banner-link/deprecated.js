/**
 * import
 */
// import { __ } from '@wordpress/i18n';
import { RichText, useBlockProps } from '@wordpress/block-editor';
import { getBannerStyle } from './_helper';
import classnames from 'classnames';

/**
 * deprecated
 */
const blockName = 'swell-block-bannerLink';
export default [
	{
		attributes: {
			alignment: {
				type: 'string',
				default: 'center',
			},
			verticalAlignment: {
				type: 'string',
				default: 'center',
			},
			hrefUrl: {
				type: 'string',
				source: 'attribute',
				selector: 'a',
				attribute: 'href',
			},
			bannerTitle: {
				type: 'string',
				source: 'html',
				selector: '.c-bannerLink__title',
			},
			bannerDescription: {
				type: 'string',
				source: 'html',
				selector: '.c-bannerLink__description',
			},
			imageUrl: {
				type: 'string',
				source: 'attribute',
				selector: 'img',
				attribute: 'src',
			},
			imageAlt: {
				type: 'string',
				source: 'attribute',
				selector: 'img',
				attribute: 'alt',
				default: '',
			},
			imageID: {
				type: 'number',
				default: 0,
			},
			isBlank: {
				type: 'boolean',
				default: false,
			},
			isBlurON: {
				type: 'boolean',
				default: false,
			},
			isShadowON: {
				type: 'boolean',
				default: false,
			},
			bgColor: {
				type: 'string',
				default: '#000',
			},
			bgOpacity: {
				type: 'number',
				default: 50,
			},
			imgRadius: {
				type: 'number',
				default: 0,
			},
			textColor: {
				type: 'string',
				default: '',
			},
			bannerWidth: {
				type: 'string',
				default: '',
			},
			bannerHeightPC: {
				type: 'string',
				default: '',
			},
			bannerHeightSP: {
				type: 'string',
				default: '',
			},
			rel: {
				type: 'string',
				source: 'attribute',
				selector: 'a',
				attribute: 'rel',
			},
		},
		supports: {
			anchor: true,
			className: false,
		},
		save: ({ attributes }) => {
			const {
				alignment,
				verticalAlignment,
				hrefUrl,
				imageUrl,
				imageAlt,
				bannerTitle,
				bannerDescription,
				textColor,
				isBlank,
				isBlurON,
				isShadowON,
				bannerHeightPC,
				bannerHeightSP,
				rel,
			} = attributes;

			// クラス名
			const bannerClass = classnames('c-bannerLink', {
				'-blur-on': isBlurON,
				'-shadow-on': isShadowON,
				'-hov-off': !hrefUrl, // 3.0で消す
			});

			const textClass = classnames('c-bannerLink__text', {
				[`has-text-align-${alignment}`]: 'center' !== alignment,
				[`is-vertically-aligned-${verticalAlignment}`]: 'center' !== verticalAlignment,
			});

			// Style
			const bannerStyle = getBannerStyle(attributes);

			let figureStylePC = '';
			let figureStyleSP = '';
			if (bannerHeightPC) {
				figureStylePC = `height:${bannerHeightPC}`;
			}
			if (bannerHeightSP) {
				figureStyleSP = `height:${bannerHeightSP}`;
			}

			const textStyle = {};
			if (textColor) {
				textStyle.color = textColor;
			}

			const BannerTag = hrefUrl ? 'a' : 'div';

			// ブロックprops
			const blockProps = useBlockProps.save({
				className: blockName,
			});

			return (
				<div {...blockProps}>
					<BannerTag
						href={hrefUrl || null}
						className={bannerClass}
						target={isBlank ? '_blank' : null}
						rel={rel || null}
						style={bannerStyle || null}
					>
						<figure
							className='c-bannerLink__figure'
							data-tab-style={figureStylePC || null}
							data-mobile-style={figureStyleSP || null}
						>
							<img
								src={imageUrl}
								className='c-bannerLink__img -no-lb'
								alt={imageAlt}
							/>
						</figure>
						<div className={textClass} style={textStyle || null}>
							<RichText.Content
								tagName='div'
								className='c-bannerLink__title'
								value={bannerTitle}
							/>
							{!RichText.isEmpty(bannerDescription) && (
								<RichText.Content
									tagName='div'
									className='c-bannerLink__description'
									value={bannerDescription}
								/>
							)}
						</div>
					</BannerTag>
				</div>
			);
		},
	},
];
