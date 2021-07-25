/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { registerFormatType } from '@wordpress/rich-text';
import { RichTextToolbarButton } from '@wordpress/block-editor';

// icon
import { swellIcon } from '@swell-guten/icon';

const shortcodeBtns = [
	{
		name: 'ad',
		label: '記事内広告',
		tag: '[ad]',
		icon: swellIcon.shortcode,
	},
	{
		name: 'spbr',
		label: 'スマホだけ改行',
		tag: '[spbr]',
		icon: swellIcon.shortcode,
	},
	{
		name: 'pcbr',
		label: 'PCだけ改行',
		tag: '[pcbr]',
		icon: swellIcon.shortcode,
	},
	{
		name: 'icon',
		label: 'アイコン用ショートコード',
		tag: '[icon class="アイコンクラス名"]',
		icon: swellIcon.shortcode,
	},
	{
		name: 'stars',
		label: '評価用スター',
		tag: '[review_stars 4.5/5]',
		icon: swellIcon.shortcode,
	},
	{
		name: 'login',
		label: 'ログイン時限定コンテンツ',
		tag: '[only_login]ログイン時にだけ表示したいコンテンツ[/only_login]',
		icon: swellIcon.shortcode,
	},
];

shortcodeBtns.forEach((data) => {
	const fillName = 'swellShortcode';
	const formatName = `loos/shortcode-${data.name}`;
	const formatTitle = data.label;
	const btnIcon = data.icon;

	// 登録
	registerFormatType(formatName, {
		title: formatTitle,
		tagName: 'span',
		className: `shortcode-${data.name}`,

		edit: ({ isActive, value, onChange }) => {
			return (
				<RichTextToolbarButton
					name={fillName}
					title={formatTitle}
					icon={btnIcon}
					isActive={isActive}
					onClick={() => {
						return onChange(
							// ※ insert部分を外部で関数化するとエラーになってしまう。
							wp.richText.insert(value, data.tag, value.start, value.end)
						);
					}}
				/>
			);
		},
	});
});
