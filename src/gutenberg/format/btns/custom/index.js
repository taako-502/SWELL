/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { toggleFormat } from '@wordpress/rich-text';
import { RichTextToolbarButton } from '@wordpress/block-editor';

/**
 * @SWELL dependencies
 */
// import { swellIcon } from '@swell-guten/icon';
import icon from './icon';

const formats = window.swellVars.customFormats || [];

const customFormats = [];
formats.forEach((format) => {
	const name = format.name;
	const title = format.title;
	customFormats.push({
		name,
		title,
		tagName: format.tagName,
		className: format.className,
		edit: ({ value, onChange, isActive }) => {
			return (
				<RichTextToolbarButton
					name='swell-controls'
					title={title}
					icon={icon}
					isActive={isActive}
					onClick={() => {
						return onChange(toggleFormat(value, { type: name }));
					}}
				/>
			);
		},
	});
});

export { customFormats };
