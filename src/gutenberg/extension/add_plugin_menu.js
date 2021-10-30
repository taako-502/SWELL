/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';
import { registerPlugin } from '@wordpress/plugins';
import { useSelect, useDispatch } from '@wordpress/data';
import { PluginSidebar, PluginSidebarMoreMenuItem } from '@wordpress/edit-post';
import { PanelBody, BaseControl, CheckboxControl } from '@wordpress/components';

/**
 * @Self dependencies
 */
import { swellStore, swellApiPath } from '@swell-guten/config';
import { swellIcon } from '@swell-guten/icon';

/* eslint no-console: 0 */

// データベースを更新する処理
const updateSwellBlockSettings = (key, val, log = false) => {
	apiFetch({
		path: swellApiPath,
		method: 'POST',
		data: {
			key,
			val,
		},
	}).then((res) => {
		if (log) console.log(res);
	});
};

const SwlPluginSidebar = () => {
	const swellBlockSettings = useSelect((select) => {
		return select(swellStore).getSettings();
	}, []);

	// store
	const { setSetting } = useDispatch(swellStore);

	// 更新処理
	const updateSettings = (key, val) => {
		setSetting(key, val); // store 更新
		updateSwellBlockSettings(key, val); // データベースも更新
	};

	return (
		<>
			<PluginSidebar name='swell-sidebar' icon={swellIcon.swellFavi} title='SWELL設定'>
				<PanelBody title='ブロックツールバーの表示設定' initialOpen={true}>
					<BaseControl>
						<CheckboxControl
							label='デバイスコントロールを表示'
							checked={swellBlockSettings.show_device_toolbtn}
							onChange={(val) => {
								updateSettings('show_device_toolbtn', val);
							}}
						/>
						<CheckboxControl
							label='マージンコントロールを表示'
							checked={swellBlockSettings.show_margin_toolbtn}
							onChange={(val) => {
								updateSettings('show_margin_toolbtn', val);
							}}
						/>
						<CheckboxControl
							label='ショートコード挿入ボタンを表示'
							checked={swellBlockSettings.show_shortcode_toolbtn}
							onChange={(val) => {
								updateSettings('show_shortcode_toolbtn', val);
							}}
						/>
						<CheckboxControl
							label='「マーカー」を1階層上に表示'
							checked={swellBlockSettings.show_marker_top}
							onChange={(val) => {
								updateSettings('show_marker_top', val);
							}}
						/>
						<CheckboxControl
							label='「フォントサイズ」を1階層上に表示'
							checked={swellBlockSettings.show_fz_top}
							onChange={(val) => {
								updateSettings('show_fz_top', val);
							}}
						/>
						<CheckboxControl
							label='「背景色」を1階層上に表示'
							checked={swellBlockSettings.show_bgcolor_top}
							onChange={(val) => {
								updateSettings('show_bgcolor_top', val);
							}}
						/>
					</BaseControl>
				</PanelBody>
				<PanelBody title='その他の表示設定' initialOpen={true}>
					<BaseControl>
						<CheckboxControl
							label='ヘッダーツールバーに投稿へのリンクを表示する'
							checked={swellBlockSettings.show_header_postlink}
							onChange={(val) => {
								updateSettings('show_header_postlink', val);
							}}
						/>
					</BaseControl>
				</PanelBody>
			</PluginSidebar>
			<PluginSidebarMoreMenuItem target='swell-sidebar' icon={swellIcon.swellFavi}>
				{__('SWELL設定', 'swell')}
			</PluginSidebarMoreMenuItem>
		</>
	);
};

registerPlugin('swell-sidebar', { render: SwlPluginSidebar });
