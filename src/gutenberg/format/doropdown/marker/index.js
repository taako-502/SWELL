/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { registerFormatType, toggleFormat } from '@wordpress/rich-text';
import { RichTextToolbarButton } from '@wordpress/block-editor';

// icon
import { swellIcon } from '@swell-guten/icon';

const markers = [
	{
		name: 'orange',
		label: '橙マーカー',
		shortcut: '',
	},
	{
		name: 'yellow',
		label: '黄マーカー',
		shortcut: '',
	},
	{
		name: 'green',
		label: '緑マーカー',
		shortcut: '',
	},
	{
		name: 'blue',
		label: '青マーカー',
		shortcut: '',
	},
];

markers.forEach((data) => {
	const fillName = 'swellMarker';
	const formatName = `loos/marker-${data.name}`;
	const formatClass = `mark_${data.name}`;
	const formatTitle = data.label;
	const btnIcon = swellIcon.markerIcons[data.name];

	// 登録
	registerFormatType(formatName, {
		title: formatTitle,
		tagName: 'span',
		className: formatClass,
		edit: ({ isActive, value, onChange }) => {
			return (
				<RichTextToolbarButton
					name={fillName}
					title={<span className={formatClass}>{formatTitle}</span>}
					icon={btnIcon}
					isActive={isActive}
					onClick={() => {
						return onChange(toggleFormat(value, { type: formatName }));
					}}
				/>
			);
		},
	});
});
