import DOM from './data/domData';

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
	const params = new URLSearchParams();
	params.append('postid', postID);

	fetch(window.swellVars.restUrl + 'swell-ct-pv', {
		method: 'POST',
		body: params,
	}).then((response) => {
		if (response.ok) {
			// console.log などで一回 response.json() 確認で使うと、responseJSONでbodyがlockされるので注意
			return response.json();
		}
		throw new TypeError('Failed ajax!');
	});
};
