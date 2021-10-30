/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { toggleFormat } from '@wordpress/rich-text';
import { RichTextToolbarButton } from '@wordpress/block-editor';

/**
 * @Self dependencies
 */
import icon from './icon';

const name = 'loos/mini-note';
const title = '注釈';

export const note = {
	name,
	title,
	tagName: 'small',
	className: 'mininote',
	edit: ({ isActive, value, onChange }) => {
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
};
