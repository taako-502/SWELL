/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { useSelect } from '@wordpress/data';
import { registerFormatType } from '@wordpress/rich-text';
import { BlockFormatControls } from '@wordpress/block-editor';
import { Slot, ToolbarGroup } from '@wordpress/components';

/**
 * @SWELL dependencies
 */
import { swellIcon } from '@swell-guten/icon';
import { swellStore } from '@swell-guten/config';

/**
 * @Self dependencies
 */
import shortcodeIcon from './shortcode/icon';
import SwellDropdownMenu from './components/SwellDropdownMenu';

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
								icon={shortcodeIcon}
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
