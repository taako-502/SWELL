/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useMemo } from '@wordpress/element';
import { createBlock, registerBlockType } from '@wordpress/blocks';
import { RichText, InspectorControls } from '@wordpress/block-editor';

/**
 * @SWELL dependencies
 */
import { iconColor } from '@swell-guten/config';
import BalloonSidebar from './_sidebar';
import example from './_example';
import metadata from './block.json';

/**
 * @Others dependencies
 */
import classnames from 'classnames';

/**
 * metadata
 */
// const blockName = 'swell-block-balloon';
const { name, category, keywords, supports } = metadata;

/**
 * ふきだし
 */
const blockAttributes = metadata.attributes;

registerBlockType(name, {
	title: 'ふきだし',
	description: __('ふきだし形式の文章を簡単に作成できます。', 'swell'),
	icon: {
		foreground: iconColor,
		src: 'format-chat',
	},
	category,
	keywords,
	supports,
	example,
	transforms: {
		from: [
			//どのブロックタイプから変更できるようにするか
			{
				type: 'block',
				blocks: ['core/paragraph'],
				transform: (attributes) => {
					return createBlock('loos/balloon', {
						content: attributes.content,
					});
				},
			},
		],
		to: [
			//どのブロックタイプへ変更できるようにするか
			{
				type: 'block',
				blocks: ['core/paragraph'],
				transform: (attributes) => {
					return createBlock('core/paragraph', {
						content: attributes.content,
					});
				},
			},
		],
	},
	attributes: blockAttributes,
	edit: ({ attributes, setAttributes, className }) => {
		const {
			balloonID,
			balloonIcon,
			balloonName,
			balloonCol,
			balloonType,
			balloonAlign,
			balloonBorder,
			balloonShape,
			spVertical,
			content,
		} = attributes;

		const swellBalloons = window.swellBalloons || {};

		// ふきだしの各種設定データ

		const balloonData = useMemo(() => {
			const data = {
				class: '',
				icon: '',
				name: '',
				col: 'gray',
				type: 'speaking',
				align: 'left',
				border: 'none',
				shape: 'circle',
			};

			// 吹き出しセット選択時
			const setedBalloon = '0' !== balloonID ? swellBalloons[balloonID] : '';
			if (setedBalloon) {
				data.icon = setedBalloon.icon;
				data.name = setedBalloon.name;
				data.col = setedBalloon.col;
				data.type = setedBalloon.type;
				data.align = setedBalloon.align;
				data.border = setedBalloon.border;
				data.shape = setedBalloon.shape;
			}

			// attributeがあればそれぞれ上書き
			if (balloonIcon) data.icon = balloonIcon;
			if (balloonName) data.name = balloonName;
			if (balloonCol) data.col = balloonCol;
			if (balloonType) data.type = balloonType;
			if (balloonAlign) data.align = balloonAlign;
			if (balloonBorder) data.border = balloonBorder;
			if (balloonShape) data.shape = balloonShape;

			// クラス生成
			data.class = classnames('c-balloon', `-bln-${data.align}`, {
				'-sp-vertical': '' !== spVertical,
			});

			return data;
		}, [
			balloonID,
			balloonIcon,
			balloonName,
			balloonCol,
			balloonType,
			balloonAlign,
			balloonBorder,
			balloonShape,
			spVertical,
		]);

		const blockClass = classnames('swell-block-balloon', className);

		return (
			<>
				<InspectorControls>
					<BalloonSidebar {...{ attributes, setAttributes }} />
				</InspectorControls>
				<div className={blockClass}>
					<div className='balloon_prev'>
						<div className={balloonData.class} data-col={balloonData.col}>
							{balloonData.icon && (
								<div
									className={classnames(
										'c-balloon__icon',
										'-' + balloonData.shape
									)}
								>
									<img
										className='c-balloon__iconImg'
										src={balloonData.icon}
										width='80px'
										height='80px'
										alt=''
									/>
									<span className='c-balloon__iconName'>{balloonData.name}</span>
								</div>
							)}
							<div
								className={classnames(
									'c-balloon__body',
									'-' + balloonData.type,
									'-border-' + balloonData.border
								)}
							>
								<div className='c-balloon__text'>
									<RichText
										tagName='div'
										placeholder={__('Text…', 'swell')}
										value={content}
										onChange={(newContent) => {
											setAttributes({ content: newContent });
										}}
									/>
									<span className='c-balloon__shapes'>
										<span className='c-balloon__before'></span>
										<span className='c-balloon__after'></span>
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</>
		);
	},

	save: ({ attributes }) => {
		// 単純に p タグとして内容は保存しておく
		return <RichText.Content tagName='p' value={attributes.content} />;
	},

	deprecated: [
		{
			attributes: blockAttributes,
			supports: { className: false },
			save: ({ attributes }) => {
				const {
					balloonTitle,
					balloonIcon,
					balloonName,
					balloonCol,
					balloonType,
					balloonAlign,
					balloonBorder,
					balloonShape,
					spVertical,
					content,
				} = attributes;

				let props = '';
				if (balloonTitle) props += ' set="' + balloonTitle + '"';
				if (balloonIcon) props += ' icon="' + balloonIcon + '"';
				if (balloonAlign) props += ' align="' + balloonAlign + '"';
				if (balloonName) props += ' name="' + balloonName + '"';
				if (balloonCol) props += ' col="' + balloonCol + '"';
				if (balloonType) props += ' type="' + balloonType + '"';
				if (balloonBorder) props += ' border="' + balloonBorder + '"';
				if (balloonShape) props += ' icon_shape="' + balloonShape + '"';

				if ('' !== spVertical) props += ' sp_vertical="1"';

				return (
					<div className='swell-block-balloon'>
						{'[ふきだし' + props + ']'}
						<RichText.Content tagName='p' value={content} />
						{'[/ふきだし]'}
					</div>
				);
			},
		},
		{
			attributes: blockAttributes,
			// supports: { className: false, },
			save: ({ attributes }) => {
				const {
					balloonTitle,
					balloonIcon,
					balloonName,
					balloonCol,
					balloonType,
					balloonAlign,
					balloonBorder,
					balloonShape,
					spVertical,
					content,
				} = attributes;

				let props = '';
				if (balloonTitle) props += ' set="' + balloonTitle + '"';
				if (balloonIcon) props += ' icon="' + balloonIcon + '"';
				if (balloonAlign) props += ' align="' + balloonAlign + '"';
				if (balloonName) props += ' name="' + balloonName + '"';
				if (balloonCol) props += ' col="' + balloonCol + '"';
				if (balloonType) props += ' type="' + balloonType + '"';
				if (balloonBorder) props += ' border="' + balloonBorder + '"';
				if (balloonShape) props += ' icon_shape="' + balloonShape + '"';

				if ('' !== spVertical) props += ' sp_vertical="1"';

				return (
					<div>
						{'[ふきだし' + props + ']'}
						<RichText.Content tagName='p' value={content} />
						{'[/ふきだし]'}
					</div>
				);
			},
		},
	],
});
