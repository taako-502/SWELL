/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import {
	RichText,
	URLInput,
	MediaPlaceholder,
	BlockControls,
	InspectorControls,
	useBlockProps,
} from '@wordpress/block-editor';

/**
 * @SWELL dependencies
 */
import metadata from './block.json';
import deprecated from './deprecated';
import blockIcon from './_icon';
import BannerLinkSidebar from './_sidebar';
import BannerLinkToolbar from './_toolbar';
import { getBannerStyle } from './_helper';
import getBlockIcon from '@swell-guten/utils/getBlockIcon';

/**
 * @Others dependencies
 */
import classnames from 'classnames';

const isMobile = 600 > window.innerWidth;

/**
 * バナーリンク
 */
const blockName = 'swell-block-bannerLink';
registerBlockType(metadata.name, {
	icon: getBlockIcon(blockIcon),
	edit: ({ attributes, setAttributes, isSelected }) => {
		const {
			alignment,
			verticalAlignment,
			hrefUrl,
			imageID,
			imageUrl,
			bannerTitle,
			bannerDescription,
			textColor,
			isBlurON,
			isShadowON,
			bannerHeightPC,
			bannerHeightSP,
		} = attributes;

		// クラス名
		const bannerClass = classnames('c-bannerLink', {
			'-blur-on': isBlurON,
			'-shadow-on': isShadowON,
			'has-link': hrefUrl,
		});

		const textClass = classnames('c-bannerLink__text', {
			[`has-text-align-${alignment}`]: 'center' !== alignment,
			[`is-vertically-aligned-${verticalAlignment}`]: 'center' !== verticalAlignment,
		});

		// Style
		const bannerStyle = getBannerStyle(attributes);

		const figureStyle = {};
		if (!isMobile && bannerHeightPC) {
			figureStyle.height = bannerHeightPC + '';
		} else if (isMobile && bannerHeightSP) {
			figureStyle.height = bannerHeightSP + '';
		}

		const textStyle = {};
		if (textColor) {
			textStyle.color = textColor;
		}

		// ブロックprops
		const blockProps = useBlockProps({
			className: blockName,
		});

		return (
			<>
				<BlockControls>
					<BannerLinkToolbar
						{...{ imageID, imageUrl, alignment, verticalAlignment, setAttributes }}
					/>
				</BlockControls>
				<InspectorControls>
					<BannerLinkSidebar {...{ attributes, setAttributes }} />
				</InspectorControls>
				<div {...blockProps}>
					{!imageUrl ? (
						<MediaPlaceholder
							labels={{ title: __('Image') }}
							onSelect={(media) => {
								setAttributes({
									imageUrl: media.url,
									imageID: media.id,
									imageAlt: media.alt,
								});
							}}
							onSelectURL={(newURL) => {
								setAttributes({
									imageUrl: newURL,
									imageID: 0,
								});
							}}
							accept='image/*'
							allowedTypes={['image']}
						/>
					) : (
						<>
							<div className={bannerClass} style={bannerStyle || null}>
								<figure
									className='c-bannerLink__figure'
									style={figureStyle || null}
								>
									<img src={imageUrl} className='c-bannerLink__img' alt='' />
								</figure>
								<div className={textClass} style={textStyle || null}>
									<RichText
										placeholder='タイトル...'
										tagName='div'
										className='c-bannerLink__title'
										value={bannerTitle}
										onChange={(value) => {
											setAttributes({ bannerTitle: value });
										}}
									/>
									<RichText
										placeholder='サブテキスト...'
										tagName='div'
										className='c-bannerLink__description'
										value={bannerDescription}
										onChange={(value) => {
											setAttributes({ bannerDescription: value });
										}}
									/>
								</div>
							</div>
							{isSelected && (
								<div className='swl-input--url'>
									<span className='__label'>href = </span>
									<URLInput
										value={hrefUrl}
										className='__input'
										onChange={(value) => setAttributes({ hrefUrl: value })}
										disableSuggestions={!isSelected}
										isFullWidth
										hasBorder
									/>
								</div>
							)}
						</>
					)}
				</div>
			</>
		);
	},

	save: ({ attributes }) => {
		const {
			alignment,
			verticalAlignment,
			hrefUrl,
			imageID,
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
		});

		const textClass = classnames('c-bannerLink__text', {
			[`has-text-align-${alignment}`]: 'center' !== alignment,
			[`is-vertically-aligned-${verticalAlignment}`]: 'center' !== verticalAlignment,
		});
		const imgClass = classnames('c-bannerLink__img', {
			[`wp-image-${imageID}`]: imageID,
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
						<img src={imageUrl} className={imgClass} alt={imageAlt} />
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
	deprecated,
});
