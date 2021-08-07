/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	PanelBody,
	Button,
	ButtonGroup,
	BaseControl,
	CheckboxControl,
	TextControl,
} from '@wordpress/components';
import { ColorPalette, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';

/**
 * @Self dependencies
 */
import BalloonSetsPanel from './components/BalloonSetsPanel';

/**
 * 設定
 */
const shapeBtns = [
	{
		label: '枠なし',
		val: 'square',
	},
	{
		label: '枠あり',
		val: 'circle',
	},
];

// 形
const typeBtns = [
	{
		label: '発言',
		val: 'speaking',
	},
	{
		label: '心の声',
		val: 'thinking',
	},
];

// 方向
const alignBtns = [
	{
		label: '左',
		val: 'left',
	},
	{
		label: '右',
		val: 'right',
	},
];

//ふきだしの線
const borderBtns = [
	{
		label: 'なし',
		val: 'none',
	},
	{
		label: 'あり',
		val: 'on',
	},
];

//色
const balloonColors = [
	{
		name: 'グレー',
		color: 'var(--color_bln_gray)',
	},
	{
		name: 'グリーン',
		color: 'var(--color_bln_green)',
	},
	{
		name: 'ブルー',
		color: 'var(--color_bln_blue)',
	},
	{
		name: 'レッド',
		color: 'var(--color_bln_red)',
	},
	{
		name: 'イエロー',
		color: 'var(--color_bln_yellow)',
	},
];

/**
 * 吹き出しブロック
 */
export default ({ attributes, setAttributes }) => {
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
	} = attributes;

	const typeControl = (
		<BaseControl>
			<BaseControl.VisualLabel>{__('ふきだしの形', 'swell')}</BaseControl.VisualLabel>
			<ButtonGroup>
				{typeBtns.map((btn) => {
					const isSelected = btn.val === balloonType;
					return (
						<Button
							isSecondary={!isSelected}
							isPrimary={isSelected}
							onClick={() => {
								if (isSelected) {
									setAttributes({ balloonType: '' });
								} else {
									setAttributes({ balloonType: btn.val });
								}
							}}
							key={`key_baloon_type_${btn.val}`}
						>
							{btn.label}
						</Button>
					);
				})}
			</ButtonGroup>
		</BaseControl>
	);

	const shapeControl = (
		<BaseControl>
			<BaseControl.VisualLabel>{__('アイコンの丸枠', 'swell')}</BaseControl.VisualLabel>
			<ButtonGroup>
				{shapeBtns.map((btn) => {
					const isSelected = btn.val === balloonShape;
					return (
						<Button
							isSecondary={!isSelected}
							isPrimary={isSelected}
							onClick={() => {
								if (isSelected) {
									setAttributes({ balloonShape: '' });
								} else {
									setAttributes({ balloonShape: btn.val });
								}
							}}
							key={`key_baloon_shape_${btn.val}`}
						>
							{btn.label}
						</Button>
					);
				})}
			</ButtonGroup>
		</BaseControl>
	);

	const alignControl = (
		<BaseControl>
			<BaseControl.VisualLabel>{__('吹き出しの方向', 'swell')}</BaseControl.VisualLabel>
			<ButtonGroup>
				{alignBtns.map((btn) => {
					const isSelected = btn.val === balloonAlign;
					return (
						<Button
							isSecondary={!isSelected}
							isPrimary={isSelected}
							onClick={() => {
								if (isSelected) {
									setAttributes({ balloonAlign: '' });
								} else {
									setAttributes({ balloonAlign: btn.val });
								}
							}}
							key={`key_baloon_align_${btn.val}`}
						>
							{btn.label}
						</Button>
					);
				})}
			</ButtonGroup>
		</BaseControl>
	);

	const borderControl = (
		<BaseControl>
			<BaseControl.VisualLabel>{__('ふきだしの線', 'swell')}</BaseControl.VisualLabel>
			<ButtonGroup className='swl-btns--minWidth'>
				{borderBtns.map((btn) => {
					const isSelected = btn.val === balloonBorder;
					return (
						<Button
							isSecondary={!isSelected}
							isPrimary={isSelected}
							onClick={() => {
								if (isSelected) {
									setAttributes({ balloonBorder: '' });
								} else {
									setAttributes({ balloonBorder: btn.val });
								}
							}}
							key={`key_baloon_border_${btn.val}`}
						>
							{btn.label}
						</Button>
					);
				})}
			</ButtonGroup>
		</BaseControl>
	);

	const colorControl = (
		<BaseControl>
			<BaseControl.VisualLabel>{__('ふきだしカラー', 'swell')}</BaseControl.VisualLabel>
			<ColorPalette
				className='swell-balloon-colors'
				value={`var(--color_bln_${balloonCol})`}
				colors={balloonColors}
				disableCustomColors={true}
				onChange={(val) => {
					val = val || '';
					val = val.replace('var(--color_bln_', '');
					val = val.replace(')', '');
					setAttributes({ balloonCol: val });
				}}
			/>
		</BaseControl>
	);

	return (
		<>
			<BalloonSetsPanel balloonID={balloonID} setAttributes={setAttributes} />
			<PanelBody title='ふきだし設定' initialOpen={true}>
				{typeControl}
				{alignControl}
				{borderControl}
				{colorControl}
				<BaseControl>
					<BaseControl.VisualLabel>
						{__('ふきだしとアイコンの並び', 'swell')}
					</BaseControl.VisualLabel>
					<CheckboxControl
						label='スマホ表示で縦並びにする'
						checked={'1' === spVertical}
						onChange={(checked) => {
							if (false === checked) {
								setAttributes({ spVertical: '' });
							} else {
								setAttributes({ spVertical: '1' });
							}
						}}
					/>
				</BaseControl>
			</PanelBody>
			<PanelBody title='アイコン設定' initialOpen={true}>
				{shapeControl}
				<TextControl
					label='アイコン下に表示する名前'
					value={balloonName}
					onChange={(val) => {
						setAttributes({ balloonName: val });
					}}
				/>
				<TextControl
					className='u-mb-5'
					label='アイコン画像'
					value={balloonIcon}
					id={'src_balloon_icon_' + balloonID}
					onChange={(val) => {
						setAttributes({ balloonIcon: val });
					}}
				/>
				<div className='swl-btns--media u-mb-20'>
					<MediaUploadCheck>
						<MediaUpload
							onSelect={(media) => {
								if (!media || !media.url) {
									// 画像がなければ
									setAttributes({ balloonIcon: '' });
								} else {
									// 画像があれば
									setAttributes({
										balloonIcon: media.url,
									});
								}
							}}
							allowedTypes={'image'}
							value={balloonIcon}
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
							setAttributes({ balloonIcon: '' });
						}}
					>
						削除
					</Button>
				</div>
			</PanelBody>
		</>
	);
};
