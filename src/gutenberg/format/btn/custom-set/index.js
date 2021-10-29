/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { registerFormatType, applyFormat } from '@wordpress/rich-text';
import { RichTextToolbarButton } from '@wordpress/block-editor';

/**
 * @Internal dependencies
 */
import icon from './icon';

const formatSets = window?.swellVars?.customFormatSets;

if (formatSets && formatSets.length) {
	formatSets.forEach((formatSet, idx) => {
		const index = idx + 1;
		registerFormatType(`loos/custom-set${index}`, {
			title: `書式セット${index}`,
			tagName: 'span',
			className: `swl-custom-set${index}`,
			edit: ({ value, onChange, isActive }) => {
				return (
					<RichTextToolbarButton
						name='swell-controls'
						title={`書式セット${index}`}
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
	});
}
