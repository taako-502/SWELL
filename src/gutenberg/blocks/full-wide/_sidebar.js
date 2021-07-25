/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	MediaUpload,
	MediaUploadCheck,
	// MediaPlaceholder,
} from '@wordpress/block-editor';
import {
	PanelBody,
	ToggleControl,
	TextControl,
	ColorPicker,
	ColorPalette,
	BaseControl,
	RangeControl,
	// RadioControl,
	CheckboxControl,
	ButtonGroup,
	Button,
	Popover,
	FocalPointPicker,
} from '@wordpress/components';
import { useState } from '@wordpress/element';

/**
 * 設定
 */
const textColorSet = [
	{
		name: '白',
		color: '#fff',
	},
	{
		name: '黒',
		color: '#000',
	},
];

const pcPaddings = ['0', '20', '40', '60', '80'];
const spPaddings = ['0', '20', '40', '60', '80'];
const sizeData = [
	{
		label: '記事',
		value: 'article',
	},
	{
		label: 'サイト幅',
		value: 'container',
	},
	{
		label: 'フルワイド',
		value: 'full',
	},
];
const svgTypes = [
	{
		label: '斜線',
		value: 'line',
	},
	{
		label: '円',
		value: 'circle',
	},
	{
		label: '波',
		value: 'wave',
	},
	{
		label: 'ジグザグ',
		value: 'zigzag',
	},
];

export default ({ attributes, setAttributes }) => {
	const {
		bgColor,
		textColor,
		bgImageUrl,
		bgImageID,
		bgOpacity,
		isFixBg,
		bgFocalPoint,
		isParallax,
		pcPadding,
		spPadding,
		contentSize,
		topSvgLevel,
		bottomSvgLevel,
		topSvgType,
		bottomSvgType,
		isReTop,
		isReBottom,
	} = attributes;

	const [isOpenBgPicker, setIsOpenBgPicker] = useState(false);

	return (
		<>
			<PanelBody title='コンテンツサイズ'>
				<BaseControl>
					<BaseControl.VisualLabel>
						{__('コンテンツの横幅をどこに揃えるか', 'swell')}
					</BaseControl.VisualLabel>
					<ButtonGroup>
						{sizeData.map((size) => {
							return (
								<Button
									isSecondary={size.value !== contentSize}
									isPrimary={size.value === contentSize}
									onClick={() => {
										setAttributes({ contentSize: size.value });
									}}
									key={`key_${size.value}`}
								>
									{size.label}
								</Button>
							);
						})}
					</ButtonGroup>
				</BaseControl>
				<BaseControl>
					<BaseControl.VisualLabel>
						{__('上下のpadding量（PC）', 'swell')}
					</BaseControl.VisualLabel>
					<ButtonGroup className='swl-btns-minWidth'>
						{pcPaddings.map((paddingVal) => {
							return (
								<Button
									isSecondary={paddingVal !== pcPadding}
									isPrimary={paddingVal === pcPadding}
									onClick={() => {
										setAttributes({
											pcPadding: paddingVal,
										});
									}}
									key={`key_${paddingVal}`}
								>
									{paddingVal}
								</Button>
							);
						})}
					</ButtonGroup>
				</BaseControl>
				<BaseControl>
					<BaseControl.VisualLabel>
						{__('上下のpadding量（SP）', 'swell')}
					</BaseControl.VisualLabel>
					<ButtonGroup className='swl-btns-minWidth'>
						{spPaddings.map((paddingVal) => {
							return (
								<Button
									isSecondary={paddingVal !== spPadding}
									isPrimary={paddingVal === spPadding}
									onClick={() => {
										setAttributes({
											spPadding: paddingVal,
										});
									}}
									key={`key_${paddingVal}`}
								>
									{paddingVal}
								</Button>
							);
						})}
					</ButtonGroup>
				</BaseControl>
			</PanelBody>
			<PanelBody title='カラー設定'>
				<BaseControl>
					<BaseControl.VisualLabel>
						{__('テキストカラー', 'swell')}
					</BaseControl.VisualLabel>
					<ColorPalette
						value={textColor}
						colors={textColorSet}
						onChange={(val) => {
							setAttributes({ textColor: val });
						}}
					/>
				</BaseControl>
				<BaseControl className='-fullWideBgColor'>
					<BaseControl.VisualLabel>
						{bgImageUrl ? 'オーバーレイカラー' : '背景色'}
					</BaseControl.VisualLabel>
					<div className='__body'>
						<div className='__color'>
							<ColorPalette
								// value={textColor}
								colors={[{ name: 'カスタムカラー', color: bgColor }]}
								disableCustomColors
								clearable={false}
								onChange={() => {
									if (isOpenBgPicker) {
										setIsOpenBgPicker(false);
									} else {
										setIsOpenBgPicker(true);
									}
								}}
							/>
							{isOpenBgPicker && (
								<Popover
									// noArrow={false}
									position='bottom'
									className='-fullWideBgColor'
									onFocusOutside={() => {
										setIsOpenBgPicker(false);
									}}
								>
									<ColorPicker
										clearable={false}
										color={bgColor}
										disableAlpha
										onChangeComplete={(val) => {
											const rgb = val.rgb;
											if (1 !== rgb.a) {
												const rgbaColor = `rgba(${rgb.r},${rgb.g},${rgb.b},${rgb.a})`;
												setAttributes({
													bgColor: rgbaColor,
												});
											} else {
												setAttributes({
													bgColor: val.hex,
												});
											}
										}}
									/>
								</Popover>
							)}
						</div>
						<div className='__label'>好きな色を選択できます。</div>
					</div>
				</BaseControl>
				<RangeControl
					label={bgImageUrl ? 'オーバーレイの不透明度' : '背景色の不透明度'}
					value={bgOpacity}
					onChange={(val) => {
						setAttributes({
							bgOpacity: val,
						});
					}}
					min={0}
					max={100}
				/>
			</PanelBody>
			<PanelBody title='背景画像の設定'>
				<TextControl
					className='u-mb-5'
					label='画像URL'
					value={bgImageUrl}
					placeholder='URLを直接入力できます'
					onChange={(val) => {
						if ('' === val) {
							// 画像削除されたら
							setAttributes({ bgImageUrl: '', bgOpacity: 100 });
						} else {
							setAttributes({
								bgImageUrl: val,
								...(100 === bgOpacity ? { bgOpacity: 50 } : {}),
							});
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
										bgImageUrl: '',
										bgImageID: 0,
										bgOpacity: 100,
									});
									return;
								}

								setAttributes({
									bgImageUrl: media.url,
									bgImageID: media.id,
									...(100 === bgOpacity ? { bgOpacity: 50 } : {}),
								});
							}}
							allowedTypes={'image'}
							value={bgImageID}
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
								bgImageUrl: '',
								bgImageID: 0,
								bgOpacity: 100,
							});
						}}
					>
						削除
					</Button>
				</div>

				{bgImageUrl && (
					<BaseControl>
						<BaseControl.VisualLabel>{__('背景効果', 'swell')}</BaseControl.VisualLabel>
						<ToggleControl
							label={__('Fixed Background')}
							checked={isFixBg}
							onChange={() => {
								setAttributes({
									isFixBg: !isFixBg, //逆転させる
									isParallax: false,
									bgFocalPoint: undefined,
									// ...(!hasParallax ? { focalPoint: undefined } : {}),
								});
							}}
						/>
						<ToggleControl
							label='パララックス効果をつける'
							checked={isParallax}
							onChange={() => {
								setAttributes({
									isParallax: !isParallax, //逆転させる
									isFixBg: false,
									bgFocalPoint: undefined,
									// ...(!hasParallax ? { focalPoint: undefined } : {}),
								});
							}}
						/>
					</BaseControl>
				)}
				{bgImageUrl && !isFixBg && !isParallax && (
					<FocalPointPicker
						label={__('Focal Point Picker')}
						url={bgImageUrl}
						value={bgFocalPoint}
						onChange={(value) => setAttributes({ bgFocalPoint: value })}
					/>
				)}
			</PanelBody>
			{!bgImageUrl && (
				<PanelBody title='上下の境界線の形状'>
					<BaseControl>
						<BaseControl.VisualLabel>
							{__('上部の境界線の形状', 'swell')}
						</BaseControl.VisualLabel>
						<ButtonGroup className='swl-btns-minWidth'>
							{svgTypes.map((type) => {
								return (
									<Button
										isSecondary={type.value !== topSvgType}
										isPrimary={type.value === topSvgType}
										onClick={() => {
											setAttributes({
												topSvgType: type.value,
											});
										}}
										key={`key_${type.value}`}
									>
										{type.label}
									</Button>
								);
							})}
						</ButtonGroup>
					</BaseControl>
					{'line' === topSvgType && (
						<BaseControl>
							<CheckboxControl
								label='逆向きにする'
								checked={isReTop}
								onChange={(val) => setAttributes({ isReTop: val })}
							/>
						</BaseControl>
					)}
					<RangeControl
						label='上部の境界線の高さレベル'
						value={topSvgLevel}
						onChange={(val) => {
							setAttributes({
								topSvgLevel: val,
							});
						}}
						min={0}
						max={5}
						step={0.1}
					/>

					<BaseControl>
						<BaseControl.VisualLabel>
							{__('下部の境界線の形状', 'swell')}
						</BaseControl.VisualLabel>
						<ButtonGroup className='swl-btns-minWidth'>
							{svgTypes.map((type) => {
								return (
									<Button
										isSecondary={type.value !== bottomSvgType}
										isPrimary={type.value === bottomSvgType}
										onClick={() => {
											setAttributes({
												bottomSvgType: type.value,
											});
										}}
										key={`key_${type.value}`}
									>
										{type.label}
									</Button>
								);
							})}
						</ButtonGroup>
					</BaseControl>
					{'line' === bottomSvgType && (
						<BaseControl>
							<CheckboxControl
								label='逆向きにする'
								checked={isReBottom}
								onChange={(val) => setAttributes({ isReBottom: val })}
							/>
						</BaseControl>
					)}
					<RangeControl
						label='下部の境界線の高さレベル'
						value={bottomSvgLevel}
						onChange={(val) => {
							setAttributes({
								bottomSvgLevel: val,
							});
						}}
						min={0}
						max={5}
						step={0.1}
					/>
				</PanelBody>
			)}
		</>
	);
};
