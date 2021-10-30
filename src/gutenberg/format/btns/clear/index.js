/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
// import { useState, useMemo } from '@wordpress/element';
// import { useSelect } from '@wordpress/data';
import { removeFormat } from '@wordpress/rich-text';
import { RichTextToolbarButton } from '@wordpress/block-editor';

const name = 'loos/clear';
const title = '書式クリア';

export const clear = {
	name,
	title,
	tagName: 'span',
	className: 'swl-clear-fomat',
	edit: ({ isActive, value, onChange }) => {
		// 全フォーマット取得
		// const formatTypes = useSelect((select) => select('core/rich-text').getFormatTypes());
		return (
			<RichTextToolbarButton
				name='swell-controls'
				icon='editor-removeformatting'
				title={title}
				isActive={isActive}
				onClick={() => {
					const activeFormats = value.activeFormats;
					if (0 === activeFormats.length) return;

					let newValue = value;
					activeFormats.forEach((format) => {
						newValue = removeFormat(newValue, format.type);
					});
					onChange(newValue);
				}}
			/>
		);
	},
};
