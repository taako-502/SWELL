/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useMemo } from '@wordpress/element';
import { registerBlockType } from '@wordpress/blocks';
import { RichText, InspectorControls, useBlockProps } from '@wordpress/block-editor';

/**
 * @SWELL dependencies
 */
import metadata from './block.json';
import deprecated from './deprecated';
import transforms from './transforms';
import BalloonSidebar from './_sidebar';
import getBlockIcon from '@swell-guten/utils/getBlockIcon';

/**
 * @Others dependencies
 */
import classnames from 'classnames';

/**
 * ふきだしブロック
 */
const blockName = 'swell-block-balloon';
registerBlockType(metadata.name, {
	icon: getBlockIcon('screenoptions'),
	edit: ({ attributes, setAttributes }) => {
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

		// ブロックProps
		const blockProps = useBlockProps({
			className: blockName,
		});

		return (
			<>
				<InspectorControls>
					<BalloonSidebar {...{ attributes, setAttributes }} />
				</InspectorControls>
				<div {...blockProps}>
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
	transforms,
	deprecated,
});
