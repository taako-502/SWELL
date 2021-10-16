/**
 * @WordPress dependencies
 */
import { render, useState } from '@wordpress/element';
import { addFilter } from '@wordpress/hooks';

/**
 * @SWELL dependencies
 */
import BalloonEdit from './edit';
import BalloonList from './list';
import BalloonMigrate from './migrate';

import { MediaUpload } from '@wordpress/media-utils';

// APIエンドポイント
export const swellApiPath = '/wp/v2/swell-balloon';

// アイコン画像プレースホルダ
export const iconPlaceholder =
	'https://0.gravatar.com/avatar/00000000000000000000000000000000?s=128&d=mp&r=g';

// Mediauploadコンポーネントを使えるようにする
addFilter(
	'editor.MediaUpload',
	'core/edit-post/components/media-upload/replace-media-upload',
	() => MediaUpload
);

const BalloonMenu = ({ mode }) => {
	// GETパラメータの取得
	const params = {};
	location.search
		.substring(1)
		.split('&')
		.forEach((param) => {
			const [key, ...val] = param.split('=');
			if (key !== '') {
				params[key] = decodeURI(val.join('='));
			}
		});

	// 新規追加かどうか
	const isNewEdit = 'post_new' in params;

	// 編集ページID
	const [id, setId] = useState(params.id);

	// データ移行画面にするかどうか
	const [isMigrate, setIsMigrate] = useState('migrate' === mode);

	if (isMigrate) {
		// 古いデータある時
		return <BalloonMigrate setIsMigrate={setIsMigrate} />;
	} else if (id || isNewEdit) {
		// 編集ページ
		return <BalloonEdit {...{ id, setId }} />;
	}

	// 一覧
	return <BalloonList />;
};

const root = document.getElementById('swell_setting_page');
const isOld = root.getAttribute('data-old');
if ('1' === isOld) {
	render(<BalloonMenu mode='migrate' />, root);
} else {
	render(<BalloonMenu mode='' />, root);
}
