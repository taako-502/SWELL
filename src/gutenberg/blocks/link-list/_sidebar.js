/**
 * @WordPress dependencies
 */
import { MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import {
	PanelBody,
	ColorPalette,
	ButtonGroup,
	Button,
	TextControl,
	BaseControl,
	RadioControl,
	ToggleControl,
} from '@wordpress/components';
import { useCallback } from '@wordpress/element';

/**
 * @SWELL dependencies
 */
import SwellIconPicker from '@swell-guten/components/SwellIconPicker';
import setBlankRel from '@swell-guten/utils/setBlankRel';

/**
 * 設定
 */

// アイコンの位置
const iconPosOptions = [
	{
		label: '左',
		val: 'left',
	},
	{
		label: '右',
		val: 'right',
	},
];

// アイコンリスト
const icons = [
	'icon-arrow_drop_down',
	'icon-chevron-small-down',
	'icon-minus',
	'icon-plus',
	'icon-chevron-small-right',
	'icon-check',
];

// アイキャッチの並び順
const orderCatchOptions = [
	{
		label: '画像 → テキスト',
		value: 'media-text',
	},
	{
		label: 'テキスト → 画像',
		value: 'text-media',
	},
];

// リストスタイル
const typeList = [
	{
		label: 'ボタン',
		val: 'button',
	},
	{
		label: '下線',
		val: 'underline',
	},
];

// ボタンカラー
const btnColorSet = [
	{ name: '赤', color: 'red' },
	{ name: '青', color: 'blue' },
	{ name: '緑', color: 'green' },
];

export default ({ attributes, setAttributes }) => {
	const {
		catchOrder,
		catchImageUrl,
		catchImageID,
		listType,
		listIconPos,
		listIconName,
		btnIconPos,
		btnIconName,
		btnColor,
		btnIsNewTab,
		btnRel,
	} = attributes;

	// リスト項目のアイコン更新
	const onlistIconClick = useCallback((val, isSelected) => {
		if (isSelected) {
			setAttributes({ listIconName: '' });
		} else {
			setAttributes({ listIconName: val });
		}
	}, []);

	// ボタンアイコン更新
	const onBtnIconClick = useCallback((val, isSelected) => {
		if (isSelected) {
			setAttributes({ btnIconName: '' });
		} else {
			setAttributes({ btnIconName: val });
		}
	}, []);

	return (
		<>
			<PanelBody title='アイキャッチ設定' initialOpen={false}>
				<RadioControl
					label='並び順'
					selected={catchOrder}
					options={orderCatchOptions}
					onChange={(val) => {
						setAttributes({ catchOrder: val });
					}}
				/>
				<TextControl
					className='u-mb-5'
					label='画像URL'
					value={catchImageUrl}
					placeholder='URLを直接入力できます'
					onChange={(val) => {
						if ('' === val) {
							// 画像削除されたら
							setAttributes({ catchImageUrl: '' });
						} else {
							setAttributes({ catchImageUrl: val });
						}
					}}
				/>
				<div className='swl-btns--media u-mb-20'>
					<MediaUploadCheck>
						<MediaUpload
							onSelect={(media) => {
								// 画像がなければ
								if (!media || !media.url) {
									setAttributes({
										catchImageUrl: '',
										catchImageID: 0,
										catchImageAlt: '',
									});
									return;
								}
								setAttributes({
									catchImageUrl: media.url,
									catchImageID: media.id,
									catchImageAlt: media.alt,
								});
							}}
							allowedTypes={'image'}
							value={catchImageID}
							render={({ open }) => (
								<Button isPrimary onClick={open}>
									メディアから選択
								</Button>
							)}
						/>
					</MediaUploadCheck>
					<Button
						isSecondary
						className='swl-btn--delete'
						onClick={() => {
							setAttributes({
								catchImageUrl: '',
								catchImageID: 0,
								catchImageAlt: '',
							});
						}}
					>
						削除
					</Button>
				</div>
			</PanelBody>
			<PanelBody title='リスト設定' initialOpen={false}>
				<BaseControl>
					<BaseControl.VisualLabel>リストタイプ</BaseControl.VisualLabel>
					<ButtonGroup>
						{typeList.map((btn) => {
							const isSelected = btn.val === listType;
							return (
								<Button
									isSecondary={!isSelected}
									isPrimary={isSelected}
									onClick={() => setAttributes({ listType: btn.val })}
									key={`key_link_list_type_${btn.val}`}
								>
									{btn.label}
								</Button>
							);
						})}
					</ButtonGroup>
				</BaseControl>
				<BaseControl>
					<BaseControl.VisualLabel>アイコン設定</BaseControl.VisualLabel>
					<SwellIconPicker
						icons={icons}
						iconName={listIconName}
						onClick={onlistIconClick}
					/>
					<TextControl
						label='アイコンのクラス'
						help='※ Font Awesome のアイコンも一部利用可能です。(svgで出力されるため、cssなどの読み込み不要で使えます)'
						value={listIconName}
						onChange={(val) => {
							setAttributes({ listIconName: val });
						}}
					/>
				</BaseControl>
				{listIconName && (
					<BaseControl>
						<BaseControl.VisualLabel>アイコンの位置</BaseControl.VisualLabel>
						<ButtonGroup>
							{iconPosOptions.map((pos) => {
								const isSelected = pos.val === listIconPos;
								return (
									<Button
										isSecondary={!isSelected}
										isPrimary={isSelected}
										onClick={() => setAttributes({ listIconPos: pos.val })}
										key={`key_link_list_icon_pos_${pos.val}`}
									>
										{pos.label}
									</Button>
								);
							})}
						</ButtonGroup>
					</BaseControl>
				)}
			</PanelBody>
			<PanelBody title='ボタン設定' initialOpen={false}>
				<BaseControl>
					<BaseControl.VisualLabel>アイコン設定</BaseControl.VisualLabel>
					<SwellIconPicker
						icons={icons}
						iconName={btnIconName}
						onClick={onBtnIconClick}
					/>
					<TextControl
						label='アイコンのクラス'
						help='※ Font Awesome のアイコンも一部利用可能です。(svgで出力されるため、cssなどの読み込み不要で使えます)'
						value={btnIconName}
						onChange={(val) => {
							setAttributes({ btnIconName: val });
						}}
					/>
				</BaseControl>
				{btnIconName && (
					<BaseControl>
						<BaseControl.VisualLabel>アイコンの位置</BaseControl.VisualLabel>
						<ButtonGroup>
							{iconPosOptions.map((pos) => {
								const isSelected = pos.val === btnIconPos;
								return (
									<Button
										isSecondary={!isSelected}
										isPrimary={isSelected}
										onClick={() => setAttributes({ btnIconPos: pos.val })}
										key={`key_link_list_icon_pos_${pos.val}`}
									>
										{pos.label}
									</Button>
								);
							})}
						</ButtonGroup>
					</BaseControl>
				)}
				<BaseControl>
					<BaseControl.VisualLabel>カラー設定</BaseControl.VisualLabel>
					<ColorPalette
						value={btnColor}
						colors={btnColorSet}
						disableCustomColors={true}
						onChange={(val) => {
							setAttributes({ btnColor: val });
						}}
					/>
				</BaseControl>
				<BaseControl>
					<BaseControl.VisualLabel>リンク設定</BaseControl.VisualLabel>
					<ToggleControl
						label='新しいタブで開く'
						checked={btnIsNewTab}
						onChange={(value) => {
							const newRel = setBlankRel(value, btnRel);
							setAttributes({
								btnIsNewTab: value,
								btnRel: newRel,
							});
						}}
					/>
					<TextControl
						label='リンク rel 属性'
						value={btnRel || ''}
						onChange={(value) => {
							setAttributes({ btnRel: value });
						}}
					/>
				</BaseControl>
			</PanelBody>
		</>
	);
};
