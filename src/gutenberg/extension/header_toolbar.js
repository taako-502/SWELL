/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import domReady from '@wordpress/dom-ready';
import { useSelect } from '@wordpress/data';

/**
 * @Self dependencies
 */
import { swellStore } from '@swell-guten/config';

const PostlinkBtn = ({ currentPostLink, linkText }) => {
	const swellBlockSettings = useSelect((select) => {
		return select(swellStore).getSettings();
	}, []);

	// console.log('swellBlockSettings', swellBlockSettings);
	if (!swellBlockSettings) return null;

	const showHeaderPostLink = swellBlockSettings.show_header_postlink;

	if (!showHeaderPostLink) return null;
	return (
		<>
			<a href={currentPostLink} target='_blank' rel='noreferrer'>
				{linkText}
			</a>
		</>
	);
};

domReady(() => {
	setTimeout(() => {
		// ヘッダーツールバーを取得
		const headerToolbar = document.querySelector('.edit-post-header__toolbar');

		if (null === headerToolbar) return;

		// const settingToolbar = document.querySelector('.edit-post-header__settings');
		// if (null === settingToolbar) return;

		// 投稿タイプを取得
		const postType = wp.data.select('core/editor').getCurrentPostType();

		let linkText = '';
		if ('post' === postType) {
			linkText = '投稿を表示';
		} else if ('page' === postType) {
			linkText = 'ページを表示';
		} else if ('lp' === postType) {
			linkText = 'LPを表示';
		} else {
			// console.log('@headerToolbar postType', postType);
			return false;
		}

		// 投稿データ
		const currentPost = wp.data.select('core/editor').getCurrentPost();
		if (!currentPost) return;

		// URLを取得する
		const currentPostLink = currentPost.link; // currentPost.permalink_template も同じ？
		if (!currentPostLink) return;

		// ヘッダーツールバーにdiv追加
		headerToolbar.insertAdjacentHTML(
			'beforeend',
			'<div class="swl-custom-header-toolbar"></div>'
		);
		// settingToolbar.insertAdjacentHTML('afterbegin','<div class="swl-custom-header-toolbar"></div>');

		wp.element.render(
			<PostlinkBtn currentPostLink={currentPostLink} linkText={linkText} />,
			document.querySelector('.swl-custom-header-toolbar')
		);
	}, 100);
});
