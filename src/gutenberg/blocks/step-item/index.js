/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useMemo } from '@wordpress/element';
import { registerBlockType } from '@wordpress/blocks';
import {
	RichText,
	InnerBlocks,
	InspectorControls,
	PanelColorSettings,
} from '@wordpress/block-editor';
import { PanelBody, BaseControl, CheckboxControl, TextControl } from '@wordpress/components';

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
 * 各種データ生成
 */
const getStepData = (stepClass, numColor, isShapeFill) => {
	let ttlFz = 'u-fz-l';
	let numClass = `${blockName}__number`;
	let shapeClass = '__shape';
	const numStyle = {};
	const shapeStyle = {};

	if ('big' === stepClass) {
		if (numColor) {
			numStyle.color = numColor;
		} else {
			numClass = classnames(numClass, 'u-col-main');
		}
	} else if ('small' === stepClass) {
		ttlFz = 'u-fz-m';
		if (numColor) {
			shapeStyle.color = numColor;
		} else {
			shapeClass = classnames(shapeClass, 'u-col-main');
		}
		if (isShapeFill) {
			shapeStyle.background = 'currentColor';
		}
	} else if (numColor) {
		numStyle.backgroundColor = numColor;
	} else {
		numClass = classnames(numClass, 'u-bg-main');
	}

	return {
		ttlFz,
		numStyle,
		numClass,
		shapeStyle,
		shapeClass,
	};
};

/**
 * ステップ項目
 */
const blockName = 'swell-block-step';
registerBlockType('loos/step-item', {
	apiVersion: 1,
	title: 'ステップ項目',
	icon: {
		foreground: iconColor,
		src: blockIcon.stepItem,
	},
	category: 'swell-blocks',
	parent: ['loos/step'],
	supports: {
		anchor: true,
		className: false, //.wp-block-[ブロック名]の削除
	},
	attributes: {
		title: {
			source: 'html',
			selector: '.swell-block-step__title',
		},
		stepLabel: {
			type: 'string',
			default: '',
		},
		theLabel: {
			type: 'string',
			default: '',
		},
		theNum: {
			type: 'string',
			default: '',
		},
		numColor: {
			type: 'string',
			default: '',
		},
		stepClass: {
			type: 'string',
			default: 'default',
		},

		isHideLabel: {
			type: 'boolean',
			default: false,
		},
		isHideNum: {
			type: 'boolean',
			default: false,
		},
		isShapeFill: {
			type: 'boolean',
			default: false,
		},
		isPreview: {
			type: 'boolean',
			default: 'false',
		},
	},
	edit: (props) => {
		const { className, attributes, setAttributes } = props;
		const {
			title,
			numColor,
			stepClass,
			stepLabel,
			isHideLabel,
			isHideNum,
			isShapeFill,
			isPreview,
			theNum,
			theLabel,
		} = attributes;

		// 子ブロックの設定
		const blockClass = classnames(className, `${blockName}__item`);

		// ステップデータ
		const { ttlFz, numStyle, numClass, shapeStyle, shapeClass } = useMemo(
			() => getStepData(stepClass, numColor, isShapeFill),
			[stepClass, numColor, isShapeFill]
		);

		const isShowShape = 'small' === stepClass || isPreview;
		const thisBlockStepLabel = isHideLabel ? '' : theLabel || stepLabel;

		return (
			<>
				<InspectorControls>
					<PanelBody title='STEPテキストの上書き設定'>
						<TextControl
							label={`「${stepLabel}」部分のテキスト`}
							value={theLabel}
							onChange={(val) => {
								setAttributes({ theLabel: val });
							}}
						/>
						<BaseControl>
							<CheckboxControl
								label='テキストを非表示にする'
								checked={isHideLabel}
								onChange={(val) => setAttributes({ isHideLabel: val })}
							/>
						</BaseControl>

						<TextControl
							label={`番号部分のテキスト`}
							value={theNum}
							onChange={(val) => {
								setAttributes({ theNum: val });
							}}
						/>
						<BaseControl>
							<CheckboxControl
								label='テキストを非表示にする'
								checked={isHideNum}
								onChange={(val) => setAttributes({ isHideNum: val })}
							/>
						</BaseControl>
					</PanelBody>
					{'small' === stepClass && (
						<PanelBody title='ステップ項目の設定'>
							<CheckboxControl
								label='シェイプを塗りつぶす'
								checked={isShapeFill}
								onChange={(checked) => {
									setAttributes({ isShapeFill: checked });
								}}
							/>
						</PanelBody>
					)}
					<PanelColorSettings
						title='ステップ番号のカラー設定'
						initialOpen={true}
						colorSettings={[
							{
								value: numColor,
								label: '色',
								onChange: (value) => {
									setAttributes({ numColor: value });
								},
							},
						]}
					></PanelColorSettings>
				</InspectorControls>
				<div className={blockClass}>
					<div
						className={numClass}
						style={numStyle || null}
						data-num={isHideNum ? '' : theNum || null}
						data-hide={isHideNum && isHideLabel ? '1' : null}
					>
						{isShowShape ? (
							<span
								className={shapeClass}
								role='presentation'
								style={shapeStyle}
							></span>
						) : null}
						{thisBlockStepLabel ? (
							<span className='__label'>{thisBlockStepLabel}</span>
						) : null}
					</div>
					<RichText
						// placeholder='タイトルを書く...'
						placeholder={__('Title', 'swell') + '...'}
						className={`${blockName}__title ${ttlFz}`}
						tagName='div'
						value={title}
						onChange={(val) => setAttributes({ title: val })}
					/>
					<div className={`${blockName}__body`}>
						<InnerBlocks template={[['core/paragraph']]} />
					</div>
				</div>
			</>
		);
	},
	save: ({ attributes }) => {
		const {
			title,
			numColor,
			stepClass,
			stepLabel,
			isShapeFill,
			isHideLabel,
			isHideNum,
			theNum,
			theLabel,
		} = attributes;

		// ステップ番号の色設定やスタイル
		const { ttlFz, numStyle, numClass, shapeStyle, shapeClass } = getStepData(
			stepClass,
			numColor,
			isShapeFill
		);

		const thisBlockStepLabel = isHideLabel ? '' : theLabel || stepLabel;

		return (
			<div className={`${blockName}__item`}>
				<div
					className={numClass}
					style={numStyle || null}
					data-num={isHideNum ? '' : theNum || null}
					data-hide={isHideNum && isHideLabel ? '1' : null}
				>
					{'small' === stepClass ? (
						<span className={shapeClass} role='presentation' style={shapeStyle}></span>
					) : null}
					{thisBlockStepLabel ? (
						<span className='__label'>{thisBlockStepLabel}</span>
					) : null}
				</div>

				{!!title && (
					<div className={`${blockName}__title ${ttlFz}`}>
						<RichText.Content value={title} />
					</div>
				)}

				<div className={`${blockName}__body`}>
					<InnerBlocks.Content />
				</div>
			</div>
		);
	},
});
