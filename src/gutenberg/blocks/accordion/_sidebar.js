/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
// import { memo } from '@wordpress/element';
import { PanelBody, Button, ButtonGroup, BaseControl } from '@wordpress/components';

/**
 * Settings
 */
const iconSetData = [
	{
		set: 1,
		closed: 'icon-arrow_drop_down',
		opened: 'icon-arrow_drop_up',
	},
	{
		set: 2,
		closed: 'icon-chevron-small-down',
		opened: 'icon-chevron-small-up',
	},
	{ set: 3, closed: 'icon-plus', opened: 'icon-minus' },
];

export default ({ iconOpened, updateIcons }) => {
	return (
		<PanelBody title='アコーディオン設定'>
			<BaseControl>
				<BaseControl.VisualLabel>
					{__('アイコンセットを選択', 'swell')}
				</BaseControl.VisualLabel>
				<small className='button_group_help'>( オープン時 / クローズ時 )</small>
				<ButtonGroup className='swl-btns--padSmall'>
					{iconSetData.map((iconSet) => {
						const isActive = iconSet.opened === iconOpened;
						return (
							<Button
								isLarge
								isPrimary={isActive}
								onClick={() => {
									updateIcons({
										iconOpened: iconSet.opened,
										iconClosed: iconSet.closed,
									});
								}}
								key={`iconkey_${iconSet.set}`}
							>
								<i className={`${iconSet.opened} u-fz-l`}></i>/
								<i className={`${iconSet.closed} u-fz-l`}></i>
							</Button>
						);
					})}
				</ButtonGroup>
			</BaseControl>
		</PanelBody>
	);
};
