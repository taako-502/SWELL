/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { registerFormatType, toggleFormat } from '@wordpress/rich-text';
import { RichTextToolbarButton } from '@wordpress/block-editor';

/**
 * @Internal dependencies
 */
// import { swellIcon } from '@swell-guten/icon';
import icon from './icon';

const formats = window.swellVars.customFormats || [];

formats.forEach((format) => {
	const formatName = format.name;
	const formatTitle = format.title;
	registerFormatType(formatName, {
		title: formatTitle,
		tagName: format.tagName,
		className: format.className,
		edit: ({ value, onChange, isActive }) => {
			return (
				<RichTextToolbarButton
					name='swell-controls'
					title={formatTitle}
					icon={icon}
					isActive={isActive}
					onClick={() => {
						return onChange(toggleFormat(value, { type: formatName }));
					}}
				/>
			);
		},
	});
});
