/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
// import { useSelect } from '@wordpress/data';
// import { useState, useMemo } from '@wordpress/element';
import { registerFormatType, toggleFormat } from '@wordpress/rich-text';
import { RichTextToolbarButton } from '@wordpress/block-editor';

const formatName = 'loos/code-file';
const formatTitle = 'ソースコード(FILE)';
registerFormatType(formatName, {
	title: formatTitle,
	tagName: 'code',
	className: 'file_name',
	edit: ({ isActive, value, onChange }) => {
		return (
			<RichTextToolbarButton
				name='swell-controls'
				title={formatTitle}
				icon='media-default'
				isActive={isActive}
				onClick={() => {
					return onChange(toggleFormat(value, { type: formatName }));
				}}
			/>
		);
	},
});
