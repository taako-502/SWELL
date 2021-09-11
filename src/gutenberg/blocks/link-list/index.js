/**
 * @WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import {
	InspectorControls,
	RichText,
	URLInput,
	MediaPlaceholder,
	useBlockProps,
} from '@wordpress/block-editor';
import { useState, useEffect } from '@wordpress/element';

/**
 * @SWELL dependencies
 */
import metadata from './block.json';
import blockIcon from './_icon';
import LinkListSidebar from './_sidebar';
import getBlockIcon from '@swell-guten/utils/getBlockIcon';
import sliceIconData from '@swell-guten/utils/sliceIconData';
import List from './components/List';

/**
 * @Others dependencies
 */
import classnames from 'classnames';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

/**
 * リンクリスト
 */
const blockName = 'swell-block-linkList';
registerBlockType(metadata.name, {
	icon: getBlockIcon(blockIcon),
	edit: (props) => {
		const { attributes, setAttributes, isSelected } = props;
		const {
			catchOrder,
			catchImageUrl,
			catchTitle,
			btnIconPos,
			btnIconName,
			btnText,
			btnColor,
			btnHrefUrl,
		} = attributes;

		// リスト項目のポップアップ
		const [isItemControlOpen, setItemControlOpen] = useState(false);

		// リスト項目の選択状態
		const [actItem, setActItem] = useState();

		// ボタンの選択状態
		const [isFocusBtn, setFocusBtn] = useState(false);

		// 選択中のリスト項目やブロックの選択状態が変わった場合は、状態を初期化する
		useEffect(() => {
			setItemControlOpen(false);
			setActItem(undefined);
			setFocusBtn(false);
		}, [isSelected]);

		useEffect(() => {
			setItemControlOpen(false);
		}, [actItem]);

		// タイトル
		const CatchTtl = () => {
			return (
				<RichText
					placeholder='タイトル...'
					tagName='div'
					className={`${blockName}__title`}
					value={catchTitle}
					onChange={(value) => {
						setAttributes({ catchTitle: value });
					}}
					onClick={() => {
						setActItem(undefined);
						setFocusBtn(false);
					}}
				/>
			);
		};

		// アイコン
		const IconTag = ({ iconName }) => {
			if (!iconName) return null;
			const iconData = sliceIconData(iconName);
			if (typeof iconData === 'string') {
				return <i className={iconName + ' __icon'}></i>;
			}
			return <FontAwesomeIcon icon={iconData} className='__icon' />;
		};

		// ボタン用クラス
		const btnClass = classnames(`${blockName}__btn`, {
			[`-icon-pos-${btnIconPos}`]: !!btnIconPos && !!btnIconName,
		});

		// ブロックprops
		const blockProps = useBlockProps({
			className: blockName,
		});

		return (
			<>
				<InspectorControls>
					<LinkListSidebar {...{ attributes, setAttributes }} />
				</InspectorControls>
				<div {...blockProps}>
					<div className={`${blockName}__inner`}>
						<div className={`${blockName}__catch`}>
							{catchOrder === 'text-media' && <CatchTtl />}
							<figure className={`${blockName}__figure`}>
								{!catchImageUrl ? (
									<MediaPlaceholder
										labels='画像'
										onSelect={(media) => {
											setAttributes({
												catchImageUrl: media.url,
												catchImageID: media.id,
												catchImageAlt: media.alt,
											});
										}}
										onSelectURL={(newURL) => {
											setAttributes({
												catchImageUrl: newURL,
												catchImageID: 0,
												catchImageAlt: '',
											});
										}}
										accept='image/*'
										allowedTypes={['image']}
									/>
								) : (
									<img
										src={catchImageUrl}
										className={`${blockName}__img`}
										alt=''
									/>
								)}
							</figure>
							{catchOrder === 'media-text' && <CatchTtl />}
						</div>
						<List
							{...{
								blockName,
								isSelected,
								attributes,
								setAttributes,
								actItem,
								setActItem,
								setFocusBtn,
								isItemControlOpen,
								setItemControlOpen,
							}}
						/>
						<a
							className={btnClass}
							href='###'
							onClick={() => {
								setFocusBtn(true);
								setActItem(undefined);
							}}
						>
							{btnIconPos === 'left' && <IconTag iconName={btnIconName} />}
							<RichText
								placeholder='テキスト...'
								tagName='span'
								value={btnText}
								onChange={(value) => {
									setAttributes({ btnText: value });
								}}
							/>
							{btnIconPos === 'right' && <IconTag iconName={btnIconName} />}
						</a>
						{isFocusBtn && (
							<div className='swl-input--url'>
								<span className='__label'>href = </span>
								<URLInput
									className='__input'
									value={btnHrefUrl}
									onChange={(url) => {
										setAttributes({ btnHrefUrl: url });
									}}
									disableSuggestions={!isSelected}
									isFullWidth
								/>
							</div>
						)}
					</div>
				</div>
			</>
		);
	},

	save: ({ attributes }) => {
		const {
			catchOrder,
			catchImageUrl,
			catchImageID,
			catchImageAlt,
			catchTitle,
			linkList,
			listType,
			listIconPos,
			listIconName,
			btnIconPos,
			btnIconName,
			btnText,
			btnColor,
			btnHrefUrl,
			btnIsNewTab,
			btnRel,
		} = attributes;

		// タイトル
		const CatchTtl = () => {
			return (
				<RichText.Content
					tagName='div'
					className={`${blockName}__title`}
					value={catchTitle}
				/>
			);
		};

		// アイコン
		const IconTag = ({ iconName }) => {
			if (!iconName) return null;
			const iconData = sliceIconData(iconName);
			if (typeof iconData === 'string') {
				return <i className={iconName + ' __icon'}></i>;
			}
			return <FontAwesomeIcon icon={iconData} className='__icon' />;
		};

		// 画像用クラス名
		const catchImgClass = classnames(`${blockName}__img`, {
			[`wp-image-${catchImageID}`]: catchImageID,
		});

		// ボタン用クラス
		const btnClass = classnames(`${blockName}__btn`, {
			[`-icon-pos-${btnIconPos}`]: !!btnIconPos && !!btnIconName,
		});

		// リスト用クラス
		const listClass = classnames(`${blockName}__list`, {
			[`-type-${listType}`]: !!listType,
			[`-icon-pos-${listIconPos}`]: !!listIconName && !!listIconPos,
		});

		// ブロックprops
		const blockProps = useBlockProps.save({
			className: blockName,
		});

		/* eslint react/jsx-no-target-blank: 0 */
		return (
			<div {...blockProps}>
				<div className={`${blockName}__inner`}>
					{(catchTitle || catchImageUrl) && (
						<div className={`${blockName}__catch`}>
							{catchTitle && catchOrder === 'text-media' && <CatchTtl />}
							{catchImageUrl && (
								<figure className={`${blockName}__figure`}>
									<img
										src={catchImageUrl}
										className={catchImgClass}
										alt={catchImageAlt}
									/>
								</figure>
							)}
							{catchTitle && catchOrder === 'media-text' && <CatchTtl />}
						</div>
					)}
					{linkList.length > 0 && (
						<ul className={listClass}>
							{linkList.map((item, idx) => {
								return (
									<li className={`${blockName}__item`} key={idx}>
										<a
											className={`${blockName}__link`}
											href={item.url}
											target={item.target}
											rel={item.rel || null}
										>
											{listIconPos === 'left' && (
												<IconTag iconName={listIconName} />
											)}
											<RichText.Content tagName='span' value={item.text} />
											{listIconPos === 'right' && (
												<IconTag iconName={listIconName} />
											)}
										</a>
									</li>
								);
							})}
						</ul>
					)}
					{btnText && (
						<a
							className={btnClass}
							href={btnHrefUrl}
							target={btnIsNewTab ? '_blank' : null}
							rel={btnRel || null}
						>
							{btnIconPos === 'left' && <IconTag iconName={btnIconName} />}
							<RichText.Content tagName='span' value={btnText} />
							{btnIconPos === 'right' && <IconTag iconName={btnIconName} />}
						</a>
					)}
				</div>
			</div>
		);
	},
});
