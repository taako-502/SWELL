/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { memo } from '@wordpress/element';
import { BaseControl, ButtonGroup, Button } from '@wordpress/components';

/**
 * アイコンリスト
 */
const icons = [
	'icon-check',
	'icon-quill',
	'icon-pen',
	'icon-hatena',
	'icon-batsu',
	'icon-light-bulb',
	'icon-megaphone',
	'icon-alert',
	'icon-info',
	'icon-blocked',
	'icon-thumb_up',
	'icon-thumb_down',
	'icon-star-full',
	'icon-heart',
	'icon-bookmarks',
	'icon-cart',
	'icon-mail',
	'icon-person',
	'icon-bubble',
	'icon-settings',
	'icon-phone',
	'icon-book',
	'icon-flag',
	'icon-posted',
	'icon-swell',
];

/**
 * SwellIconPicker
 */
const SwellIconPicker = memo(({ iconName, onClick }) => {
	return (
		<BaseControl>
			<ButtonGroup className='swl-btns--icons'>
				{icons.map((theIcon) => {
					const isSelected = theIcon === iconName;
					return (
						<Button
							isPrimary={isSelected}
							key={`icon-${theIcon}`}
							onClick={() => {
								onClick(theIcon, isSelected);
							}}
						>
							<i className={theIcon}></i>
						</Button>
					);
				})}
			</ButtonGroup>
		</BaseControl>
	);
});
export default SwellIconPicker;
