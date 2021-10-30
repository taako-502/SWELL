/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { useSelect } from '@wordpress/data';
import { toggleFormat } from '@wordpress/rich-text';
import { RichTextToolbarButton } from '@wordpress/block-editor';

/**
 * @Self dependencies
 */
import theIcon from './icon';

const name = 'loos/nowrap';
const title = '折り返し禁止';

export const nowrap = {
	name,
	title,
	tagName: 'span',
	className: 'u-nowrap',
	edit: ({ isActive, value, onChange }) => {
		// 選択中のブロックを取得
		const selectedBlockName = useSelect((select) => {
			const selectedBlock = select('core/block-editor').getSelectedBlock();
			return selectedBlock.name || '';
		}, []);

		// テーブルブロック以外では表示しない
		if ('core/table' !== selectedBlockName) return null;

		return (
			<RichTextToolbarButton
				name='swell-controls'
				title={title}
				icon={theIcon}
				isActive={isActive}
				onClick={() => {
					return onChange(toggleFormat(value, { type: name }));
				}}
			/>
		);
	},
};
