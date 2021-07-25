/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useSelect } from '@wordpress/data';
import { useMemo, useCallback } from '@wordpress/element';
import { useEntityProp } from '@wordpress/core-data';
// import { InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	ToggleControl,
	TextControl,
	ColorPalette,
	BaseControl,
	ButtonGroup,
	Button,
	TextareaControl,
} from '@wordpress/components';

/**
 * @SWELL dependencies
 */
import hasClass from '@swell-guten/utils/hasClass';
import setClass from '@swell-guten/utils/setClass';
import setBlankRel from '@swell-guten/utils/setBlankRel';
import SwellIconPicker from '@swell-guten/components/SwellIconPicker';

/**
 * 設定
 */
const colorClasses = ['red_', 'blue_', 'green_'];
const sizOptions = [
	{ className: '-size-s', label: '小', key: 'small' },
	{ className: '', label: '標準', key: 'normal' },
	{ className: '-size-l', label: '大', key: 'large' },
];

export default ({ attributes, setAttributes, clientId }) => {
	const { rel, isNewTab, imgUrl, htmlTags, isCount, btnId, iconName } = attributes;
	let nowClass = attributes.className || '';

	const hasHtml = '' !== htmlTags;

	// スタイルがmore_btnかどうか
	const isMoreBtn = hasClass(nowClass, 'is-style-more_btn');

	// サイズ設定を持つかどうか
	const hasBtnSize = -1 !== nowClass.indexOf('-size-');

	// 投稿タイプを取得
	const postType = useSelect((select) => select('core/editor').getCurrentPostType(), []);
	const isBlockParts = 'wp_block' === postType || 'blog_parts' === postType;

	// クリックデータを取得
	const [meta, setMeta] = useEntityProp('postType', postType, 'meta');
	const btnClickedData = useMemo(() => {
		let btnData = null;
		if (meta) {
			const metaBtnCv = meta.swell_btn_cv_data;
			let btnCvData = metaBtnCv || '{}';
			btnCvData = JSON.parse(btnCvData);
			btnData = btnCvData[btnId] || null;
		}

		if (null === btnData) {
			return <p>まだ計測データはありません。</p>;
		}

		const ctClicked = btnData.click || 0;
		const ctPv = btnData.pv || 0;
		const ctImp = btnData.imp || 0;

		let impPerPV = (ctImp / ctPv) * 10000;
		impPerPV = Math.round(impPerPV) / 100;

		let clickPerImp = (ctClicked / ctImp) * 10000;
		clickPerImp = Math.round(clickPerImp) / 100;

		return (
			<>
				<p className='__data'>
					ボタン設置後のPV数：<b>{ctPv}回</b>
				</p>
				<p className='__data'>
					ボタンの表示回数：<b>{ctImp}回</b>
				</p>
				<p className='__data'>
					ボタンのクリック数：<b>{ctClicked}回</b>
				</p>
				<p className='__data'>
					このボタンまで読まれた割合：<b>{impPerPV}%</b>
				</p>
				<p className='__data'>
					表示回数に対するクリック率：<b>{clickPerImp}%</b>
				</p>
			</>
		);
	}, [meta]);

	// 現在の色
	const selectedColor = useMemo(() => {
		let _selectedColor = '';
		for (let i = 0; i < colorClasses.length; i++) {
			const color = colorClasses[i];
			if (hasClass(nowClass, color)) {
				_selectedColor = color.replace('_', '');
				break;
			}
		}
		return _selectedColor;
	}, [nowClass]);

	// アイコンのonclick処理
	const onIconClick = useCallback((val, isSelected) => {
		if (isSelected) {
			setAttributes({ iconName: '' });
		} else {
			setAttributes({ iconName: val });
		}
	}, []);

	const cvSetting = isBlockParts ? (
		<div>ブログパーツ・再利用ブロックでは計測機能は利用できません。</div>
	) : (
		<>
			<ToggleControl
				label={__('クリック率を計測する')}
				checked={isCount}
				onChange={(value) => {
					setAttributes({ isCount: value });
					// trueの時、ブロックIDもセットする。
					if (value && !btnId) {
						let theBtnId = clientId.split('-');
						theBtnId = theBtnId[0];
						setAttributes({ btnId: theBtnId });
					}
				}}
			/>
			{isCount && (
				<>
					<TextControl
						label='ボタンID'
						placeholder='計測用ID'
						help='※ 他のボタンブロックと同じ値にならないように設定してください。'
						value={btnId || ''}
						onChange={(value) => {
							setAttributes({ btnId: value });
							if (!value) {
								setAttributes({ isCount: false });
							}
						}}
					/>
					<div className='swell-button-data'>
						<div className='__title'>このボタンの計測結果</div>
						{btnClickedData}
					</div>
				</>
			)}
		</>
	);

	return (
		<>
			{!isMoreBtn && (
				<>
					<PanelBody title='ボタンカラー設定' initialOpen={true}>
						<div>
							<ColorPalette
								value={selectedColor}
								disableCustomColors={true}
								colors={[
									{ name: '赤', color: 'red' },
									{ name: '青', color: 'blue' },
									{ name: '緑', color: 'green' },
								]}
								onChange={(val) => {
									if (val) val += '_';
									const newClass = setClass(nowClass, val, colorClasses);
									setAttributes({ className: newClass });
								}}
							/>
						</div>
					</PanelBody>
					<PanelBody title='ボタンサイズ設定' initialOpen={true}>
						<BaseControl>
							<ButtonGroup className='swl-btns--minWidth'>
								{sizOptions.map((size) => {
									const sizeClass = size.className;
									let isPrimary = false;

									if ('' === sizeClass) {
										// ボタンサイズの指定がない時、「標準」にフォーカス
										isPrimary = !hasBtnSize;
									} else {
										// それ以外は、自身のクラス名をブロック持っていればにフォーカス
										isPrimary = -1 !== nowClass.indexOf(sizeClass);
									}
									return (
										<Button
											isLarge
											isSecondary={!isPrimary}
											isPrimary={isPrimary}
											onClick={() => {
												// サイズ関係のクラスをひとまず削除
												nowClass = nowClass.replace('-size-s', '');
												nowClass = nowClass.replace('-size-l', '');
												nowClass = nowClass.trim();

												if ('' !== sizeClass) {
													//サイズ指定があればクラス名を付与
													nowClass = nowClass + ' ' + sizeClass;
												}

												// classname更新
												setAttributes({
													className: nowClass,
												});
											}}
											key={`key_${size.key}`}
										>
											{`${size.label}`}
										</Button>
									);
								})}
							</ButtonGroup>
						</BaseControl>
					</PanelBody>
				</>
			)}
			<PanelBody title='リンク設定' initialOpen={true} className={hasHtml ? 'u-none' : null}>
				<>
					<ToggleControl
						label={__('Open in new tab')}
						checked={isNewTab}
						onChange={(value) => {
							const newRel = setBlankRel(value, rel);
							setAttributes({
								isNewTab: value,
								rel: newRel,
							});
						}}
					/>
					<TextControl
						label={__('Link rel')}
						value={rel || ''}
						onChange={(value) => {
							setAttributes({ rel: value });
						}}
					/>
				</>
			</PanelBody>
			<PanelBody title='アイコン設定' initialOpen={true}>
				<SwellIconPicker iconName={iconName} onClick={onIconClick} />
				<BaseControl>
					<TextControl
						label={__('アイコンのクラス', 'swell')}
						help='※ Font Awesome のアイコンも一部利用可能です。(svgで出力されるため、cssなどの読み込み不要で使えます)'
						// type='url'
						value={iconName}
						onChange={(val) => {
							setAttributes({ iconName: val });
						}}
					/>
				</BaseControl>
			</PanelBody>
			<PanelBody title='広告タグ設定' initialOpen={true}>
				{!hasHtml && (
					<TextControl
						label='計測用imgタグの画像URL'
						placeholder='計測用imgタグのsrc属性値を入力'
						help='※ 実際には画像は表示されません。'
						value={imgUrl || ''}
						onChange={(value) => {
							setAttributes({ imgUrl: value });
						}}
					/>
				)}
				<TextareaControl
					label='広告タグを直接入力'
					help='テキストリンクの広告タグをそのままペーストしてご利用いただけます。'
					value={htmlTags}
					rows='8'
					onChange={(theTag) => {
						setAttributes({ htmlTags: theTag });
						if ('' === theTag) {
							// タグをクリアした時
							setAttributes({ imgUrl: '' });
							setAttributes({ rel: '' });
							setAttributes({ rel: '' });
							setAttributes({ content: '' });
						}
					}}
				/>
			</PanelBody>
			<PanelBody title='ボタンの計測設定' initialOpen={true}>
				{cvSetting}
			</PanelBody>
		</>
	);
};
