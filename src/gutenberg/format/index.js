/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { useSelect } from '@wordpress/data';
import { registerFormatType } from '@wordpress/rich-text';
import { BlockFormatControls } from '@wordpress/block-editor';
import { Slot, ToolbarGroup } from '@wordpress/components';

// コンポーネント
import SwellDropdownMenu from './components/SwellDropdownMenu';

import './btn/clear'; // 書式クリア
import './btn/code-dir'; // フォルダアインコン付きコード
import './btn/code-file'; // ファイルアインコン付きコード
import './btn/bg-color'; // 背景色
import './btn/marker'; // 背景色
import './btn/font-size'; // フォントサイズ
import './btn/mini-note'; // 注釈ボタン
import './btn/nowrap'; // nowrapボタン
import './btn/custom'; // カスタムボタン
import './btn/custom-set'; // カスタムボタン

// ドロップダウン化
import './doropdown/shortcode'; // ショートコード用ドロップダウンメニューの登録

// swell設定ファイル
import { swellIcon } from '@swell-guten/icon';
import { swellStore } from '@swell-guten/config';

/**
 * ドロップダウンメニューたち
 */
registerFormatType('loos/inline-tools', {
	title: 'SWELLインラインツール',
	tagName: 'swl-dropdown', //なんか指定しないとでてこない
	className: null,
	edit: () => {
		const swellBlockSettings = useSelect((select) => {
			return select(swellStore).getSettings();
		}, []);

		return (
			<BlockFormatControls>
				<div className='block-editor-format-toolbar swell-format-toolbar'>
					<ToolbarGroup>
						<SwellDropdownMenu
							fillName='swell-controls'
							icon={swellIcon.swellFavi}
							label='SWELL装飾'
						/>
						{swellBlockSettings.show_shortcode_toolbtn && (
							<SwellDropdownMenu
								fillName='swellShortcode'
								icon={swellIcon.shortcode}
								label='ショートコード'
							/>
						)}

						{['bg-color', 'swl-marker', 'swl-fz'].map((format) => (
							<Slot name={`RichText.ToolbarControls.${format}`} key={format} />
						))}
					</ToolbarGroup>
				</div>
			</BlockFormatControls>
		);
	},
});
