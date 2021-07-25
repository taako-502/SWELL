/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useCallback } from '@wordpress/element';
import { addQueryArgs } from '@wordpress/url';
import {
	PanelBody,
	BaseControl,
	ColorPalette,
	// ButtonGroup,
	// Button,
	TextControl,
} from '@wordpress/components';

/**
 * @SWELL dependencies
 */
import SwellIconPicker from '@swell-guten/components/SwellIconPicker';

/**
 * 設定
 */
const capBlockColors = window.capBlockColors || {};

// カラーパレットのオプションを生成
const colorsOptions = [];
Object.keys(capBlockColors).forEach((_colSet) => {
	colorsOptions.push({
		name: capBlockColors[_colSet].label,
		color: capBlockColors[_colSet].value,
	});
});

export default function ({ attributes, setAttributes }) {
	const { iconName, dataColSet } = attributes;

	const setedColorSlug = dataColSet ? capBlockColors[dataColSet].value : '';

	// アイコンのonclick処理
	const onIconClick = useCallback((val, isSelected) => {
		if (isSelected) {
			setAttributes({ iconName: '' });
		} else {
			setAttributes({ iconName: val });
		}
	}, []);

	const editorSettingUrl = addQueryArgs('admin.php', {
		page: 'swell_settings_editor',
	});

	// パネル生成
	return (
		<>
			<PanelBody title='カラーセットを選択' initialOpen={true}>
				<div>
					<ColorPalette
						value={setedColorSlug}
						disableCustomColors={true}
						colors={colorsOptions}
						onChange={(val) => {
							// val(カラーコード)からセット名のスラッグを取得する (見つからなかったらundefined)
							const selectedColSet = Object.keys(capBlockColors).find(
								(_colSet) => capBlockColors[_colSet].value === val
							);
							setAttributes({ dataColSet: selectedColSet || '' });
						}}
					/>
				</div>
				<div className='swl-helptext'>
					<a
						href={editorSettingUrl + '#colors'}
						target='_blank'
						rel='noopener noreferrer'
					>
						SWELL設定
					</a>
					から色を編集できます。
				</div>
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
		</>
	);
}
