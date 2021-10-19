/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { toggleFormat } from '@wordpress/rich-text';
import { RichTextToolbarButton } from '@wordpress/block-editor';

const name = 'loos/code-dir';
const title = 'ソースコード(DIR)';
export const codeDir = {
	name,
	title,
	tagName: 'code',
	className: 'dir_name',
	edit: ({ isActive, value, onChange }) => {
		return (
			<RichTextToolbarButton
				name='swell-controls'
				title={title}
				icon='category'
				isActive={isActive}
				onClick={() => {
					return onChange(toggleFormat(value, { type: name }));
				}}
			/>
		);
	},
};
