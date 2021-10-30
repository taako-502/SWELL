/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { toggleFormat, applyFormat, removeFormat } from '@wordpress/rich-text';
import { RichTextToolbarButton } from '@wordpress/block-editor';
import { button } from '@wordpress/icons';

const name = 'loos/inline-btn';
const title = 'インラインボタン';

export const inlineBtn = {
	name,
	title,
	tagName: 'span',
	className: 'swl-inline-btn',
	attributes: {
		className: 'class',
	},
	edit: ({ isActive, value, onChange }) => {
		return (
			<RichTextToolbarButton
				name='swell-controls'
				title={title}
				icon={button}
				isActive={isActive}
				onClick={() => {
					const activeFormats = value.activeFormats;

					// const newContent = insertObject(value, {
					// 	type: 'loos/inline-btn',
					// 	attributes: { class: 'hoge', href: '###', content: 'attrcontent' },
					// });

					let newContent = toggleFormat(value, {
						type: 'loos/inline-btn',
						attributes: { className: 'is-style-btn_normal red_' },
					});

					// リンクされているかどうか
					const oldLinkFormat = activeFormats.find((format) => {
						return format.type === 'core/link';
					});

					if (oldLinkFormat) {
						// .swl-inline-btn > a にするために一旦外してから
						newContent = removeFormat(newContent, { type: 'core/link' });

						// 再度applyFormat
						newContent = applyFormat(newContent, {
							type: 'core/link',
							attributes: oldLinkFormat.attributes,
						});
					} else {
						newContent = applyFormat(newContent, {
							type: 'core/link',
							attributes: { url: '###' },
						});
					}
					onChange(newContent);
				}}
			/>
		);
	},
};
