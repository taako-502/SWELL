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
} from '@wordpress/block-editor';

/**
 * @SWELL dependencies
 */
import { iconColor } from '@swell-guten/config';
import blockIcon from './_icon';
import BannerLinkSidebar from './_sidebar';
import BannerLinkToolbar from './_toolbar';
import example from './_example';

/**
 * @Others dependencies
 */
import classnames from 'classnames';
import hexToRgba from 'hex-to-rgba';

//
const blockName = 'swell-block-bannerLink';
const isMobile = 600 > window.innerWidth;

/**
 * 背景色
 */
const getBgColor = (attributes) => {
	const { bgColor, bgOpacity } = attributes;
	if (0 === bgOpacity) {
		// backgroundColorなし
		return '';
	} else if (100 === bgOpacity) {
		return bgColor;
	}
	return hexToRgba(bgColor, bgOpacity / 100);
};

/**
 * スタイルをセットする関数
 */
const getBannerStyle = (attributes) => {
	const { textColor, imgRadius } = attributes;

	const style = {};

	// textColorがセットされているか
	if (textColor) {
		style.color = textColor;
	}

	// 背景色
	const bgColor = getBgColor(attributes);
	if (bgColor) {
		style.backgroundColor = bgColor;
	}

	if (0 !== imgRadius) {
		style.borderRadius = imgRadius + 'px';
	}

	return style;
};

// カスタムブロックの登録
registerBlockType('loos/banner-link', {
	title: 'バナーリンク',
	description: __('簡易的なバナー型のリンクを作成できます。', 'swell'),
	icon: {
		foreground: iconColor,
		src: blockIcon,
	},
	category: 'swell-blocks',
	keywords: ['swell', 'banner-link'],
	supports: {
		anchor: true,
		className: false,
	},
	example,
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
			// default: '',
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

	// getEditWrapperProps(attributes) {
	//     const { contentSize } = attributes;
	//     return { 'data-align': 'full', 'data-content-size': contentSize };
	// },

	edit: ({ attributes, setAttributes, className, isSelected }) => {
		const {
			alignment,
			verticalAlignment,
			hrefUrl,
			imageID,
			imageUrl,
			bannerTitle,
			bannerDescription,
			textColor,
			// isBlank,
			isBlurON,
			isShadowON,
			// imgRadius,
			bannerHeightPC,
			bannerHeightSP,
		} = attributes;

		// クラス名
		const blockClass = classnames(blockName, className);

		let bannerClass = 'c-bannerLink';
		if (isBlurON) {
			bannerClass = classnames(bannerClass, '-blur-on');
		}
		if (isShadowON) {
			bannerClass = classnames(bannerClass, '-shadow-on');
		}
		let textClass = 'c-bannerLink__text';
		if ('center' !== alignment) {
			textClass = classnames(textClass, `has-text-align-${alignment}`);
		}
		if ('center' !== verticalAlignment) {
			textClass = classnames(textClass, `is-vertically-aligned-${verticalAlignment}`);
		}

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
								// imageAlt: media.alt,
							});
						}}
						// onDoubleClick={() => {alert('double click');}}
						// onCancel={() => {alert('on cancel');}}
						accept='image/*'
						allowedTypes={['image']}
						// multiple={false}
						// dropZoneUIOnly={false}
						// notices={noticeUI}
						// onError={this.onUploadError}
						// value={{ id, src }}
						// mediaPreview={mediaPreview}
						// disableMediaButtons={!isEditing && url}
					/>
				) : (
					<div className={blockClass}>
						<div
							// href={hrefUrl}
							className={bannerClass}
							style={bannerStyle || null}
						>
							<figure className='c-bannerLink__figure' style={figureStyle || null}>
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
									placeholder='テキスト...'
									tagName='div'
									className='c-bannerLink__description'
									value={bannerDescription}
									onChange={(value) => {
										setAttributes({
											bannerDescription: value,
										});
									}}
								/>
							</div>
						</div>
					</div>
				)}

				{isSelected && (
					<div className='swl-input--url'>
						<span className='__label'>href = </span>
						<URLInput
							value={hrefUrl}
							className='__input'
							onChange={(value) => setAttributes({ hrefUrl: value })}
							disableSuggestions={!isSelected}
							// id={linkId}
							// autoFocus={false}
							isFullWidth
							hasBorder
						/>
					</div>
				)}
			</>
		);
	},

	save: ({ attributes, className }) => {
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
		const blockClass = classnames(blockName, className);
		let bannerClass = 'c-bannerLink';
		if (isBlurON) {
			bannerClass = classnames(bannerClass, '-blur-on');
		}
		if (isShadowON) {
			bannerClass = classnames(bannerClass, '-shadow-on');
		}
		if (!hrefUrl) {
			bannerClass = classnames(bannerClass, '-hov-off');
		}
		let textClass = 'c-bannerLink__text';
		if ('center' !== alignment) {
			textClass = classnames(textClass, `has-text-align-${alignment}`);
		}
		if ('center' !== verticalAlignment) {
			textClass = classnames(textClass, `is-vertically-aligned-${verticalAlignment}`);
		}

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

		return (
			<div className={blockClass}>
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
						<img src={imageUrl} className='c-bannerLink__img -no-lb' alt={imageAlt} />
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
});
