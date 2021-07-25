/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { registerFormatType, toggleFormat } from '@wordpress/rich-text';
import { RichTextToolbarButton } from '@wordpress/block-editor';

/**
 * @Self dependencies
 */
import theIcon from './_icon';

const formatName = 'loos/nowrap';
const formatTitle = '折り返し禁止';

registerFormatType(formatName, {
	title: formatTitle,
	tagName: 'span',
	className: 'u-nowrap',
	edit: ({ isActive, value, onChange }) => {
		return (
			<RichTextToolbarButton
				name='swell-controls'
				title={formatTitle}
				icon={theIcon}
				isActive={isActive}
				onClick={() => {
					return onChange(toggleFormat(value, { type: formatName }));
				}}
			/>
		);
	},
});
