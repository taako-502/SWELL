/* eslint no-console: 0 */
/* eslint no-unused-vars: 0 */

/**
 * REST APIへレスポンスを投げる
 */
export const postRestApi = async (route, params, doneFunc = undefined, failFunc = undefined) => {
	// restUrlを正常に取得できるか
	const restUrl = window?.swellVars?.restUrl;
	if (restUrl === undefined) return;

	// パラメータ処理
	const bodyParams = new URLSearchParams();
	Object.keys(params).forEach((key) => {
		bodyParams.append(key, params[key]);
	});

	const data = {
		method: 'POST',
		body: bodyParams,
	};

	fetch(restUrl + route, data)
		.then((response) => {
			if (response.ok) {
				return response.json();
			}
			throw new TypeError(`Failed callRestApi: ${response.status}`);
		})
		.then((jsonData) => {
			if (doneFunc) {
				doneFunc(jsonData); // jsonData: fetch で response.json()された値
			}
		})
		.catch((error) => {
			console.error(error);
			console.error('route: ' + route, params);
			if (failFunc) {
				failFunc();
			}
		});
};
