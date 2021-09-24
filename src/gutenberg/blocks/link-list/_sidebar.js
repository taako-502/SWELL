/**
 * @WordPress dependencies
 */
import { PanelBody, ButtonGroup, Button, TextControl, BaseControl } from '@wordpress/components';
import { useCallback } from '@wordpress/element';

/**
 * @SWELL dependencies
 */
import SwellIconPicker from '@swell-guten/components/SwellIconPicker';

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

export default ({ attributes, setAttributes }) => {
	const { listType, listIconPos, listIconName } = attributes;

	// リスト項目のアイコン更新
	const onlistIconClick = useCallback((val, isSelected) => {
		if (isSelected) {
			setAttributes({ listIconName: '' });
		} else {
			setAttributes({ listIconName: val });
		}
	}, []);

	return (
		<>
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
		</>
	);
};
