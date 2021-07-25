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
	Button,
	Popover,
	TextareaControl,
} from '@wordpress/components';
import { useState } from '@wordpress/element';

/**
 * @SWELL dependencies
 */
import setBlankRel from '@swell-guten/utils/setBlankRel';

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

export default ({ attributes, setAttributes }) => {
	const {
		textColor,
		imageUrl,
		imageID,
		imageAlt,
		bgColor,
		bgOpacity,
		isBlank,
		rel,
		isBlurON,
		isShadowON,
		imgRadius,
		bannerHeightPC,
		bannerHeightSP,
	} = attributes;

	const [isOpenBgPicker, setIsOpenBgPicker] = useState(false);

	return (
		<>
			<PanelBody title='バナー設定'>
				<TextControl
					label='バナーの高さ（Tab/PC）'
					help='※ デバイス幅600px以上'
					value={bannerHeightPC}
					placeholder='単位を含め、半角で入力'
					onChange={(val) => {
						setAttributes({ bannerHeightPC: val });
					}}
				/>

				<TextControl
					label='バナーの高さ（Mobile）'
					help='※ デバイス幅600px未満'
					value={bannerHeightSP}
					placeholder='単位を含め、半角で入力'
					onChange={(val) => {
						setAttributes({ bannerHeightSP: val });
					}}
				/>
				<TextControl
					className='u-mb-5'
					label='画像URL'
					value={imageUrl}
					placeholder='URLを直接入力できます'
					onChange={(val) => {
						setAttributes({
							imageUrl: val,
						});
					}}
				/>

				<div className='swl-btns--media u-mb-20'>
					<MediaUploadCheck>
						<MediaUpload
							onSelect={(media) => {
								if (!media || !media.url) {
									// 画像がなければ
									setAttributes({
										imageUrl: '',
										imageID: 0,
										imageAlt: '',
									});
								} else {
									// 画像があれば
									setAttributes({
										imageUrl: media.url,
										imageID: media.id,
										imageAlt: media.alt,
									});
								}
							}}
							allowedTypes={'image'}
							value={imageID}
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
								imageUrl: '',
								imageID: 0,
								imageAlt: '',
							});
						}}
					>
						削除
					</Button>
				</div>
				<TextareaControl
					label='Altテキスト' //{__('Altテキスト')}
					value={imageAlt}
					onChange={(val) => {
						setAttributes({
							imageAlt: val,
						});
					}}
					// help={}
				/>
				<ToggleControl
					label='ブラー効果をつける'
					onChange={(value) => {
						setAttributes({
							isBlurON: value,
						});
					}}
					checked={isBlurON}
				/>
				<ToggleControl
					label='影ををつける'
					onChange={(value) => {
						setAttributes({
							isShadowON: value,
						});
					}}
					checked={isShadowON}
				/>
				<RangeControl
					label='画像の丸み'
					value={imgRadius}
					onChange={(val) => {
						setAttributes({
							imgRadius: val,
						});
					}}
					min={0}
					max={100}
				/>

				{/* {imageUrl && (
                    <FocalPointPicker
                        label={__('Focal Point Picker')}
                        url={imageUrl}
                        value={bgFocalPoint}
                        onChange={(value) => setAttributes({ bgFocalPoint: value })}
                    />
                )} */}
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
						{__('オーバーレイカラー', 'swell')}
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
					label='オーバーレイの不透明度'
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
			<PanelBody title='リンク設定'>
				<ToggleControl
					label={__('Open in new tab')}
					onChange={(value) => {
						const newRel = setBlankRel(value, rel);
						setAttributes({
							isBlank: value,
							rel: newRel,
						});
					}}
					checked={isBlank}
				/>
				<TextControl
					label={__('Link rel')}
					value={rel || ''}
					onChange={(value) => {
						setAttributes({
							rel: value,
						});
					}}
				/>
			</PanelBody>
		</>
	);
};
