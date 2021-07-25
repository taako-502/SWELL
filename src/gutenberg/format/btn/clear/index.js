/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { useSelect } from '@wordpress/data';
// import { useState, useMemo } from '@wordpress/element';
import { registerFormatType, removeFormat } from '@wordpress/rich-text';
import { RichTextToolbarButton } from '@wordpress/block-editor';

const formatName = 'loos/clear';
const formatTitle = '書式クリア';

registerFormatType(formatName, {
	title: formatTitle,
	tagName: 'span',
	className: 'swl-clear-fomat',
	edit: ({ isActive, value, onChange }) => {
		if (useSelect === undefined) return null;
		const formatTypes = useSelect((select) => select('core/rich-text').getFormatTypes());
		return (
			<RichTextToolbarButton
				name='swell-controls'
				icon='editor-removeformatting'
				title={formatTitle}
				isActive={isActive}
				onClick={() => {
					if (0 < formatTypes.length) {
						let newValue = value;
						formatTypes.map((format) => {
							newValue = removeFormat(newValue, format.name);
						});
						// newValue:removeFormatし終わった value
						onChange({ ...newValue });
					}
				}}
			/>
		);
	},
});
