/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { registerFormatType, toggleFormat } from '@wordpress/rich-text';
import { RichTextToolbarButton } from '@wordpress/block-editor';

// icon
import { swellIcon } from '@swell-guten/icon';

const fontSizes = [
	{
		name: 'xs',
		label: '極小',
		shortcut: 'xs',
	},
	{
		name: 's',
		label: '小',
		shortcut: 's',
	},
	// {
	//     name: 'm',
	//     label: '中',
	//     shortcut: '',
	// },
	{
		name: 'l',
		label: '大',
		shortcut: 'l',
	},
	{
		name: 'xl',
		label: '特大',
		shortcut: 'xl',
	},
];

fontSizes.forEach((data) => {
	const fillName = 'swellFontSize';
	const formatName = `loos/fz-${data.name}`;
	const formatClass = `fs_${data.name}`;
	const formatTitle = data.label;
	const btnIcon = swellIcon.fsIcons[data.name];

	// 登録
	registerFormatType(formatName, {
		title: formatTitle,
		tagName: 'span',
		className: formatClass,

		edit: ({ isActive, value, onChange }) => {
			const onToggle = () => {
				return onChange(toggleFormat(value, { type: formatName }));
			};
			return (
				<RichTextToolbarButton
					name={fillName}
					title={<span className={formatClass}>{formatTitle}</span>}
					icon={btnIcon}
					isActive={isActive}
					onClick={onToggle}
				/>
			);
		},
	});
});
