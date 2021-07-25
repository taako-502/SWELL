/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useMemo, RawHTML } from '@wordpress/element';
import { registerBlockType } from '@wordpress/blocks';
import {
	RichText,
	URLInput,
	BlockControls,
	BlockAlignmentToolbar,
	InspectorControls,
} from '@wordpress/block-editor';

/**
 * @SWELL dependencies
 */
import example from './_example';
import ButtonSidebar from './_sidebar';
import { iconColor } from '@swell-guten/config';

/**
 * @others dependencies
 */
import classnames from 'classnames';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

/* eslint jsx-a11y/no-autofocus: 0 */

/**
 * 普通のアイコンと fontawesome を分けるための関数
 */
const sliceIconData = (iconClass) => {
	let iconData;

	// FAだったら配列が返される
	if (null !== iconClass.match(/fas |fab |far /)) {
		iconData = iconClass.split(' ');
		iconData[1] = iconData[1].replace('fa-', '');
		return iconData;
	}

	// FA以外は普通に文字列のまま
	return iconClass;
};

/**
 * ボタンブロック
 */
const blockName = 'swell-block-button';

registerBlockType('loos/button', {
	title: 'SWELLボタン',
	description: __('カスタマイザーのデータと連携する特殊なボタンブロックです。', 'swell'),
	icon: {
		foreground: iconColor,
		src: 'button',
	},
	category: 'swell-blocks',
	keywords: ['swell', 'button'],
	supports: {
		anchor: true,
		className: false,
		// align: ['left', 'right'],
	},
	example,
	styles: [
		// ブロック要素のスタイルを設定
		{
			name: 'btn_normal',
			label: 'ノーマル',
			isDefault: true,
		},
		{
			name: 'btn_solid',
			label: '立体',
		},
		{
			name: 'btn_shiny',
			label: 'キラッと',
		},
		{
			name: 'btn_line',
			label: 'アウトライン',
		},
		{
			name: 'more_btn',
			label: 'MOREボタン',
		},
	],
	attributes: {
		content: {
			type: 'string',
			source: 'html',
			selector: '.swell-block-button__link > span',
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
		iconName: {
			type: 'string',
			default: '',
		},
	},

	edit: (props) => {
		const { attributes, className, setAttributes, isSelected, clientId } = props;

		const { hrefUrl, content, btnAlign, htmlTags, btnId, iconName } = attributes;

		// デフォルトのクラスをnormalにセット。
		if (!className) setAttributes({ className: 'is-style-btn_normal' });

		const blockClass = classnames(blockName, className);

		const hasHtml = !!htmlTags;

		let isDoubleRegisterdId = false;
		if (!window.swlBtnsData) window.swlBtnsData = {};

		if (!!btnId) {
			window.swlBtnsData[clientId] = btnId;

			// 他のブロックにも同じbtnIdがセットされていれば、2重登録ということになる。
			const swlBtnsDataKeys = Object.keys(window.swlBtnsData);
			swlBtnsDataKeys.forEach((blockID) => {
				if (blockID !== clientId && window.swlBtnsData[blockID] === btnId) {
					// かつ、後ろ側のブロックにだけアラート。
					const otherBlockNum = swlBtnsDataKeys.indexOf(blockID);
					const thisBlockNum = swlBtnsDataKeys.indexOf(clientId);
					if (otherBlockNum < thisBlockNum) {
						isDoubleRegisterdId = true;
					}
				}
			});
		}

		// HTML直接入力時
		if (hasHtml) {
			return (
				<>
					<InspectorControls>
						<ButtonSidebar {...{ attributes, setAttributes, clientId }} />
					</InspectorControls>
					<div className={classnames(blockClass, '-prev')} data-align={btnAlign || null}>
						<RawHTML>{htmlTags}</RawHTML>
						{/* <div dangerouslySetInnerHTML={{ __html: htmlTags }}></div> */}
						{isSelected && <div className='__prevLabel'>※ HTMLタグ直接入力中</div>}
					</div>
				</>
			);
		}

		// アイコン
		const iconTag = useMemo(() => {
			if (!iconName) return null;
			const iconData = sliceIconData(iconName);

			if (typeof iconData === 'string') {
				return <i className={iconName + ' __icon'}></i>;
			}
			return <FontAwesomeIcon icon={iconData} className='__icon' />;
		}, [iconName]);

		// 通常モード
		return (
			<>
				<BlockControls>
					<BlockAlignmentToolbar
						value={btnAlign}
						onChange={(val) => {
							val = val || '';
							setAttributes({ btnAlign: val });
						}}
						controls={['left', 'right']}
					/>
				</BlockControls>
				<InspectorControls>
					<ButtonSidebar {...{ attributes, setAttributes, clientId }} />
				</InspectorControls>
				<div className={blockClass} data-align={btnAlign || null}>
					{isDoubleRegisterdId && (
						<div className='swell-button-alert'>※ 別のボタンIDを指定してください。</div>
					)}
					{/* <div>このボタンのクリック数 : {thisBtnData}</div> */}
					<div className={`${blockName}__wrapper`}>
						<a
							className={`${blockName}__link`}
							data-has-icon={iconTag ? '1' : null}
							href='###'
						>
							{iconTag}
							<RichText
								tagName='span'
								placeholder={__('Text…', 'swell')}
								value={content}
								withoutInteractiveFormatting={false}
								onChange={(newContent) => {
									setAttributes({
										content: newContent,
									});
								}}
							/>
						</a>
					</div>
					{isSelected && (
						<div className='swl-input--url'>
							<span className='__label'>href = </span>
							<URLInput
								className='__input'
								value={hrefUrl}
								onChange={(url) => {
									setAttributes({ hrefUrl: url });
								}}
								disableSuggestions={!isSelected}
								autoFocus={false}
								isFullWidth
							/>
						</div>
					)}
				</div>
			</>
		);
	},

	save: ({ attributes }) => {
		const {
			hrefUrl,
			isNewTab,
			rel,
			content,
			imgUrl,
			btnAlign,
			htmlTags,
			btnId,
			iconName,
		} = attributes;
		const hasHtml = !!htmlTags;

		// タグ直接入力モード
		if (hasHtml) {
			return (
				<div
					className={classnames('swell-block-button', '-html')}
					data-align={btnAlign || null}
					data-id={btnId || null}
				>
					<RawHTML>{htmlTags}</RawHTML>
				</div>
			);
		}

		let iconTag = null;
		if (iconName) {
			const iconData = sliceIconData(iconName);
			if (typeof iconData === 'string') {
				iconTag = <i className={iconName + ' __icon'}></i>;
			} else {
				iconTag = <FontAwesomeIcon icon={iconData} className='__icon' />;
			}
		}

		return (
			<div
				className='swell-block-button'
				data-align={btnAlign || null}
				data-id={btnId || null}
			>
				<a
					href={hrefUrl}
					target={isNewTab ? '_blank' : null}
					rel={rel || null}
					className='swell-block-button__link'
					data-has-icon={iconTag ? '1' : null}
				>
					{iconTag}
					<RichText.Content tagName='span' value={content} />
				</a>
				{imgUrl && (
					<img
						src={imgUrl}
						className='swell-block-button__img'
						width='1'
						height='1'
						alt=''
					/>
				)}
			</div>
		);
	},

	deprecated: [
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
							// dangerouslySetInnerHTML={{ __html: htmlTags }}
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
	],
});
