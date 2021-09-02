const webpack = require('webpack');
const path = require('path');
const defaultConfig = require('@wordpress/scripts/config/webpack.config');

/**
 * CleanWebpackPlugin （ビルド先のほかのファイルを勝手に削除するやつ） はオフに。
 */
defaultConfig.plugins.shift();

for (let i = 0; i < defaultConfig.plugins.length; i++) {
	const pluginInstance = defaultConfig.plugins[i];
	if ('DependencyExtractionWebpackPlugin' === pluginInstance.constructor.name) {
		defaultConfig.plugins.splice(i, i);
	}
}

let entryFiles = {};
let srcDir = 'js';
let distDir = 'js';
if ('front' === process.env.TYPE) {
	entryFiles = [
		'main',
		'main_with_pjax',
		'prefetch',
		'front/set_luminous',
		'front/set_rellax',
		'front/set_olstart',
		'front/set_urlcopy',
		'front/set_mv',
		'front/set_post_slider',
		'front/set_sp_headnav',
		'front/set_sp_headnav_loop',
	];
} else if ('admin' === process.env.TYPE) {
	srcDir = 'js/admin';
	distDir = 'js/admin';
	entryFiles = [
		'admin_script',
		'ad_tag',
		'balloon',
		'colorpicker',
		'count_title',
		'mediauploader',
		'settings',
		'tinymce',
	];
} else if ('customizer' === process.env.TYPE) {
	srcDir = 'js/customizer';
	distDir = 'js/customizer';
	entryFiles = ['customizer-controls', 'responsive-device-preview'];
}

const entryPoints = {};
entryFiles.forEach((name) => {
	entryPoints[name] = path.resolve('./src', srcDir, `${name}.js`);
});

/**
 * exports
 */
module.exports = {
	...defaultConfig, //@wordpress/scriptを引き継ぐ

	mode: 'production', // より圧縮させる

	entry: entryPoints,

	output: {
		path: path.resolve('./build', distDir),
		filename: '[name].min.js',
	},

	resolve: {
		alias: {
			'@swell-js': path.resolve(__dirname, 'src/js/'),
		},
	},
	plugins: [...defaultConfig.plugins, new webpack.EnvironmentPlugin(['TYPE'])],
	// performance: { hints: false },
	devtool: 'none',
};
