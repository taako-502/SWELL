import DOM from './data/domData';
import { postRestApi } from '@swell-js/helper/callRestApi';

/**
 * PVカウント処理
 */
export const pvCount = () => {
	if (null === DOM.content) return;

	const postID = DOM.content.getAttribute('data-postid');
	if (!postID) return;

	const isCount = DOM.content.getAttribute('data-pvct');
	if (!isCount) return;

	// 受け渡すデータ
	const params = { postid: postID };
	postRestApi('swell-ct-pv', params);
};
