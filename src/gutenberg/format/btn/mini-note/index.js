/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { registerFormatType, toggleFormat } from '@wordpress/rich-text';
import { RichTextToolbarButton } from '@wordpress/block-editor';

/**
 * @Self dependencies
 */
import { swellIcon } from '@swell-guten/icon';

const formatName = 'loos/mini-note';
const formatTitle = '注釈';

registerFormatType(formatName, {
	title: formatTitle,
	tagName: 'small',
	className: 'mininote',
	edit: ({ isActive, value, onChange }) => {
		return (
			<RichTextToolbarButton
				name='swell-controls'
				title={formatTitle}
				icon={swellIcon.iconSmall}
				isActive={isActive}
				onClick={() => {
					return onChange(toggleFormat(value, { type: formatName }));
				}}
			/>
		);
	},
});
