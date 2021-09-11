/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useEffect, useMemo, RawHTML } from '@wordpress/element';
import { registerBlockType } from '@wordpress/blocks';
import {
	RichText,
	URLInput,
	BlockControls,
	BlockAlignmentToolbar,
	InspectorControls,
	useBlockProps,
} from '@wordpress/block-editor';

/**
 * @SWELL dependencies
 */
import metadata from './block.json';
import deprecated from './deprecated';
import blockIcon from './_icon';
import ButtonSidebar from './_sidebar';
import getBlockIcon from '@swell-guten/utils/getBlockIcon';
import sliceIconData from '@swell-guten/utils/sliceIconData';

/**
 * @others dependencies
 */
import classnames from 'classnames';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

/**
 * ボタンブロック
 */
const blockName = 'swell-block-button';
registerBlockType(metadata.name, {
	icon: getBlockIcon(blockIcon),
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
	edit: (props) => {
		const { attributes, setAttributes, isSelected, clientId } = props;

		const { className, hrefUrl, content, btnAlign, htmlTags, btnId, isCount, iconName } =
			attributes;

		// デフォルトのクラスをnormalにセット。
		if (!className) setAttributes({ className: 'is-style-btn_normal' });

		const hasHtml = !!htmlTags;

		// アイコン
		const iconTag = useMemo(() => {
			if (!iconName) return null;
			const iconData = sliceIconData(iconName);

			if (typeof iconData === 'string') {
				return <i className={iconName + ' __icon'}></i>;
			}
			return <FontAwesomeIcon icon={iconData} className='__icon' />;
		}, [iconName]);

		useEffect(() => {
			if (!btnId) return;

			const sameKeyBlocks = document.querySelectorAll(
				`.swell-block-button[data-id="${btnId}"]`
			);

			if (sameKeyBlocks.length > 1) {
				const newID = clientId.split('-');
				setAttributes({ btnId: newID[0] || '' });
			}
		}, [clientId]);

		// ブロックprops
		const blockProps = useBlockProps({
			className: classnames(blockName, {
				'-prev': hasHtml,
			}),
			'data-align': btnAlign || null,
			'data-id': isCount ? btnId : null,
		});

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
				<div {...blockProps}>
					{hasHtml ? (
						// HTML編集モード
						<>
							<RawHTML>{htmlTags}</RawHTML>
							{isSelected && <div className='__prevLabel'>※ HTMLタグ直接入力中</div>}
						</>
					) : (
						// 通常モード
						<>
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
										withoutInteractiveFormatting // リンクボタン非表示にできる
										// identifier='text' // コアのボタンブロックはこれも指定してた
										onChange={(newContent) => {
											setAttributes({ content: newContent });
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
						</>
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
			isCount,
			btnId,
			iconName,
		} = attributes;

		const hasHtml = !!htmlTags;

		// ブロックprops
		const blockProps = useBlockProps.save({
			className: classnames(blockName, { '-html': hasHtml }),
			'data-align': btnAlign || null,
			'data-id': isCount ? btnId : null,
		});

		// タグ直接入力モード
		if (hasHtml) {
			return (
				<div {...blockProps}>
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

		/* eslint react/jsx-no-target-blank: 0 */
		return (
			<div {...blockProps}>
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
	deprecated,
});
