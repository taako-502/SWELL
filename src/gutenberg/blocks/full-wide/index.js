/**
 * @WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { useMemo } from '@wordpress/element';
import {
	InspectorControls,
	InnerBlocks,
	BlockControls,
	useBlockProps,
	__experimentalUseInnerBlocksProps as useInnerBlocksProps,
} from '@wordpress/block-editor';

/**
 * @SWELL dependencies
 */
import metadata from './block.json';
import deprecated from './deprecated';
import blockIcon from './_icon';
import { FullWideSVG } from './_svg';
import FullWideSidebar from './_sidebar';
import FullWideToolbar from './_toolbar';
import getBlockIcon from '@swell-guten/utils/getBlockIcon';

/**
 * @Others dependencies
 */
import classnames from 'classnames';
import hexToRgba from 'hex-to-rgba';

const TEMPLATE = [['core/heading', { className: 'is-style-section_ttl' }]];

/**
 * ブロック名
 */
const blockName = 'swell-block-fullWide';

/**
 * クラスをセットする関数
 */
const getBlockClass = (attributes) => {
	const { bgImageUrl, isFixBg, isParallax, pcPadding, spPadding } = attributes;

	return classnames(`pc-py-${pcPadding}`, `sp-py-${spPadding}`, {
		'has-bg-img': bgImageUrl || null,
		'-fixbg': (bgImageUrl && isFixBg) || null,
		'-parallax': (bgImageUrl && isParallax) || null,
	});
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
	if (bgImageUrl && bgFocalPoint) {
		style.backgroundPosition = `${bgFocalPoint.x * 100}% ${bgFocalPoint.y * 100}%`;
	}

	if (textColor) style.color = textColor;
	if (bgColor) style.backgroundColor = bgColor;

	return style;
};

/**
 * フルワイドブロック
 */
registerBlockType(metadata.name, {
	icon: getBlockIcon(blockIcon.icon),
	edit: ({ attributes, setAttributes }) => {
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

		// ブロックprops
		const blockProps = useBlockProps({
			className: getBlockClass(attributes),
			style: style || null,
			'data-align': 'full',
			'data-content-size': contentSize,
		});
		const innerBlocksProps = useInnerBlocksProps(
			{
				className: classnames(`${blockName}__inner`, 'swl-inner-blocks'),
			},
			{
				template: TEMPLATE,
			}
		);

		return (
			<>
				<InspectorControls>
					<FullWideSidebar {...{ attributes, setAttributes }} />
				</InspectorControls>
				<BlockControls>
					<FullWideToolbar {...{ attributes, setAttributes }} />
				</BlockControls>
				<div {...blockProps}>
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
					<div {...innerBlocksProps} />
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

		// inner要素のクラス名
		const innerClass = classnames(`${blockName}__inner`, {
			[`l-${contentSize}`]: 'full' !== contentSize || null,
		});

		// ブロックprops
		const blockProps = useBlockProps.save({
			className: classnames(getBlockClass(attributes), 'alignfull', {
				lazyload: bgImageUrl || null,
			}),
			style: style || null,
			'data-bg': bgImageUrl || null,
		});

		return (
			<div {...blockProps}>
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
