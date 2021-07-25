// https://make.wordpress.org/core/2021/02/22/changes-in-wordpress-data-api/

/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';
import { registerStore } from '@wordpress/data';

/**
 * @Self dependencies
 */
import { swellStore, swellApiPath } from '@swell-guten/config';

const DEFAULT_STATE = {
	blockSettings: {},
};

const actions = {
	setSettings(val) {
		return {
			type: 'SET_SETTINGS',
			val,
		};
	},

	setSetting(key, val) {
		return {
			type: 'SET_SETTING',
			key,
			val,
		};
	},

	fetchFromAPI(path) {
		return {
			type: 'FETCH_FROM_API',
			path,
		};
	},
};

registerStore(swellStore, {
	reducer(state = DEFAULT_STATE, action) {
		switch (action.type) {
			case 'SET_SETTING':
				return {
					...state,
					blockSettings: {
						...state.blockSettings,
						[action.key]: action.val,
					},
				};
			case 'SET_SETTINGS':
				return {
					...state,
					blockSettings: action.val,
				};
		}

		return state;
	},

	actions,

	selectors: {
		// getSetting(state, key) {
		//     const { blockSettings } = state;
		//     return blockSettings[key] || false;
		// },
		getSettings(state) {
			const { blockSettings } = state;
			return blockSettings || [];
		},
	},

	controls: {
		FETCH_FROM_API(action) {
			if (!apiFetch) return null;
			return apiFetch({ path: action.path });
		},
	},

	// selectors が最初に呼び出されたときに実行される処理？
	resolvers: {
		// *getSetting(key) {
		//     const path = swellApiPath;
		//     const blockSettings = yield actions.fetchFromAPI(path) || [];
		//     // 取得してきた設定値
		//     const settingVal = blockSettings[key];
		//     return actions.setSetting(key, settingVal);
		// },

		*getSettings() {
			const path = swellApiPath;
			const blockSettings = yield actions.fetchFromAPI(path) || [];
			// console.log('@resolvers', blockSettings);

			return actions.setSettings(blockSettings);
		},
	},
});
