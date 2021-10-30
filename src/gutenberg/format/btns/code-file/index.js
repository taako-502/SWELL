/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { toggleFormat } from '@wordpress/rich-text';
import { RichTextToolbarButton } from '@wordpress/block-editor';

const name = 'loos/code-file';
const title = 'ソースコード(FILE)';

export const codeFile = {
	name,
	title,
	tagName: 'code',
	className: 'file_name',
	edit: ({ isActive, value, onChange }) => {
		return (
			<RichTextToolbarButton
				name='swell-controls'
				title={title}
				icon='media-default'
				isActive={isActive}
				onClick={() => {
					return onChange(toggleFormat(value, { type: name }));
				}}
			/>
		);
	},
};
