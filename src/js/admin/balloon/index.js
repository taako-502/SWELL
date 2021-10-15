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

const BalloonMenu = () => {
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

	// モード設定（新規追加/編集画面 or 一覧画面）
	const mode = params.id || isNewEdit ? 'edit' : 'list';

	const [id, setId] = useState(params.id);

	return (
		<>
			{mode === 'edit' && <BalloonEdit {...{ id, setId }} />}
			{mode === 'list' && <BalloonList />}
		</>
	);
};

render(<BalloonMenu />, document.getElementById('swell_setting_page'));
