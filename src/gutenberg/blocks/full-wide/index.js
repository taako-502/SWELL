/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import { useMemo } from '@wordpress/element';
import { InspectorControls, InnerBlocks, BlockControls } from '@wordpress/block-editor';

/**
 * @SWELL dependencies
 */
import { iconColor } from '@swell-guten/config';
import { FullWideSVG } from './_svg';
import FullWideSidebar from './_sidebar';
import FullWideToolbar from './_toolbar';
import deprecated from './_deprecated';
import example from './_example';

/**
 * @Others dependencies
 */
import classnames from 'classnames';
import hexToRgba from 'hex-to-rgba';

/**
 * ブロック名
 */
const blockName = 'swell-block-fullWide';

/**
 * クラスをセットする関数
 */
const getBlockClass = (attributes) => {
	const { bgImageUrl, isFixBg, isParallax, pcPadding, spPadding } = attributes;

	let blockClass = blockName;
	if (bgImageUrl) {
		blockClass = classnames(blockClass, 'has-bg-img');
		// かつ、固定背景がオンの時
		if (isFixBg) {
			blockClass = classnames(blockClass, '-fixbg');
		}
		if (isParallax) {
			blockClass = classnames(blockClass, '-parallax');
		}
	}

	blockClass = classnames(blockClass, `pc-py-${pcPadding}`);
	blockClass = classnames(blockClass, `sp-py-${spPadding}`);
	return blockClass;
};

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
const getBlockStyle = (attributes, bgColor) => {
	const { bgImageUrl, bgFocalPoint, textColor } = attributes;

	const style = {};
	if (bgImageUrl) {
		if (bgFocalPoint) {
			style.backgroundPosition = `${bgFocalPoint.x * 100}% ${bgFocalPoint.y * 100}%`;
		}
	}

	if (textColor) style.color = textColor;
	if (bgColor) style.backgroundColor = bgColor;

	return style;
};

// カスタムブロックの登録
registerBlockType('loos/full-wide', {
	title: 'フルワイド',
	description: __('フルワイド幅でコンテンツを配置できます。', 'swell'),
	icon: {
		foreground: iconColor,
		src: 'align-wide',
	},
	category: 'swell-blocks',
	keywords: ['swell', 'fullwide'],
	supports: {
		anchor: true,
		className: false,
	},
	example,
	attributes: {
		align: {
			type: 'string',
			default: 'full',
		},
		bgColor: {
			type: 'string',
			default: '#f7f7f7',
		},
		textColor: {
			type: 'string',
			default: '',
		},
		bgImageUrl: {
			type: 'string',
			default: '',
		},
		bgImageID: {
			type: 'number',
			default: 0,
		},
		bgOpacity: {
			type: 'number',
			default: 100,
		},
		contentSize: {
			type: 'string',
			default: 'article',
		},
		pcPadding: {
			type: 'string',
			default: '60',
		},
		spPadding: {
			type: 'string',
			default: '40',
		},
		bgFocalPoint: {
			type: 'object',
		},
		isFixBg: {
			type: 'boolean',
			default: false,
		},
		isParallax: {
			type: 'boolean',
			default: false,
		},
		topSvgType: {
			type: 'string',
			default: 'line',
		},
		topSvgLevel: {
			type: 'number',
			default: 0,
		},
		bottomSvgType: {
			type: 'string',
			default: 'line',
		},
		bottomSvgLevel: {
			type: 'number',
			default: 0,
		},
		isReTop: {
			type: 'boolean',
			default: false,
		},
		isReBottom: {
			type: 'boolean',
			default: false,
		},
	},
	getEditWrapperProps(attributes) {
		const { contentSize } = attributes;
		return { 'data-align': 'full', 'data-content-size': contentSize };
	},

	edit: ({ attributes, setAttributes, className }) => {
		const {
			bgImageUrl,
			topSvgLevel,
			bottomSvgLevel,
			topSvgType,
			bottomSvgType,
			isReTop,
			isReBottom,
		} = attributes;

		// クラス名
		let blockClass = getBlockClass(attributes);
		blockClass = classnames(blockClass, className);

		// 背景色
		const bgColor = useMemo(() => getBgColor(attributes), [attributes]);

		// スタイルデータ
		const style = useMemo(() => {
			const _style = getBlockStyle(attributes, bgColor);
			if (bgImageUrl) {
				_style.backgroundImage = 'url(' + bgImageUrl + ')';
			}
			return _style;
		}, [bgColor, attributes]);

		return (
			<>
				<InspectorControls>
					<FullWideSidebar {...{ attributes, setAttributes }} />
				</InspectorControls>
				<BlockControls>
					<FullWideToolbar {...{ attributes, setAttributes }} />
				</BlockControls>
				<div className={blockClass} style={style || null}>
					{0 !== topSvgLevel && !bgImageUrl && (
						<FullWideSVG
							position='top'
							heightLevel={topSvgLevel}
							fillColor={bgColor}
							type={topSvgType}
							isRe={isReTop}
							isEdit={true}
						/>
					)}
					<div className={`${blockName}__inner`}>
						<InnerBlocks
							template={[
								[
									'core/heading',
									{
										className: 'is-style-section_ttl',
									},
								],
							]}
						/>
					</div>
					{0 !== bottomSvgLevel && !bgImageUrl && (
						<FullWideSVG
							position='bottom'
							heightLevel={bottomSvgLevel}
							fillColor={bgColor}
							type={bottomSvgType}
							isRe={isReBottom}
							isEdit={true}
						/>
					)}
				</div>
			</>
		);
	},

	save: ({ attributes }) => {
		const {
			bgImageUrl,
			contentSize,
			topSvgLevel,
			bottomSvgLevel,
			topSvgType,
			bottomSvgType,
			isReTop,
			isReBottom,
		} = attributes;

		const bgColor = getBgColor(attributes);
		// styleデータ
		const style = getBlockStyle(attributes, bgColor);

		// クラス名
		let blockClass = getBlockClass(attributes);
		blockClass = classnames(blockClass, 'alignfull');
		if (bgImageUrl) {
			blockClass = classnames(blockClass, 'lazyload');
		}

		// inner要素のクラス名
		let innerClass = `${blockName}__inner`;
		if ('full' !== contentSize) {
			innerClass = classnames(innerClass, `l-${contentSize}`);
		}

		return (
			<div className={blockClass} style={style || null} data-bg={bgImageUrl || null}>
				{0 !== topSvgLevel && !bgImageUrl && (
					<FullWideSVG
						position='top'
						heightLevel={topSvgLevel}
						fillColor={bgColor}
						type={topSvgType}
						isRe={isReTop}
						isEdit={false}
					/>
				)}

				<div className={innerClass}>
					<InnerBlocks.Content />
				</div>
				{0 !== bottomSvgLevel && !bgImageUrl && (
					<FullWideSVG
						position='bottom'
						heightLevel={bottomSvgLevel}
						fillColor={bgColor}
						type={bottomSvgType}
						isRe={isReBottom}
						isEdit={false}
					/>
				)}
			</div>
		);
	},

	deprecated,
});
