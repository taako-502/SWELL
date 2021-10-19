/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { registerFormatType, applyFormat } from '@wordpress/rich-text';
import { RichTextToolbarButton } from '@wordpress/block-editor';

/**
 * @Internal dependencies
 */
import { swellIcon } from '@swell-guten/icon';

// applyFormat: https://github.com/WordPress/gutenberg/blob/trunk/packages/rich-text/src/apply-format.js
// memo: 設定値を取得
const formatSet = [
	{
		type: 'core/bold',
	},
	{
		type: 'loos/marker',
		attributes: {
			class: 'mark_orange',
		},
	},
	{
		type: 'loos/font-size',
		attributes: {
			class: 'u-fz-l',
		},
	},
	{
		type: 'core/text-color',
		attributes: {
			class: 'has-inline-color has-swl-deep-01-color',
		},
	},
];
registerFormatType('loos/custom-set1', {
	title: '書式セット01',
	tagName: 'span', // このタグが適用されるわけではない
	className: 'swl-custom-set1', // このクラスが適用されるわけではない
	edit: ({ value, onChange, isActive }) => {
		return (
			<RichTextToolbarButton
				name='swell-controls'
				title='書式セット01'
				icon={swellIcon.swellFavi}
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
