/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { applyFormat } from '@wordpress/rich-text';
import { RichTextToolbarButton } from '@wordpress/block-editor';

/**
 * @Internal dependencies
 */
import icon from './icon';

const customFormatSets = window?.swellVars?.customFormatSets;
const customSets = [];
if (customFormatSets && customFormatSets.length) {
	customFormatSets.forEach((formatSet, idx) => {
		const index = idx + 1;
		const name = `loos/custom-set${index}`;
		const title = `書式セット${index}`;
		customSets.push({
			name,
			title,
			tagName: 'span',
			className: `swl-custom-set${index}`,
			edit: ({ value, onChange, isActive }) => {
				return (
					<RichTextToolbarButton
						name='swell-controls'
						title={title}
						icon={icon}
						isActive={isActive}
						onClick={() => {
							let newFormats = value;
							formatSet.forEach((formatData) => {
								newFormats = applyFormat(newFormats, formatData);
							});
							onChange(newFormats);
						}}
					/>
				);
			},
		});

		// registerFormatType(`loos/custom-set${index}`, {
		// 	title: `書式セット${index}`,
		// 	tagName: 'span',
		// 	className: `swl-custom-set${index}`,
		// });
	});
}

export { customSets };
