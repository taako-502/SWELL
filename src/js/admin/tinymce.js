(function () {
	console.log('SWELL: Loaded tinymce.js');

	const tinymce = window.tinymce;

	// 投稿エディター上でのみデータが取れるので、それ以外は何もせず返す
	if (!window.swellBalloons) return;

	tinymce.create('tinymce.plugins.swellButtons', {
		init(editor, url) {
			//ふきだしセットリスト生成
			const swellBalloons = window.swellBalloons;
			const balloonSetList = [];
			Object.keys(swellBalloons).forEach(function (key) {
				const balData = swellBalloons[key];
				balloonSetList.push({
					text: balData.title,
					onclick() {
						const shortcode =
							'[ふきだし set="' + balData.title + '"]ここにテキストを入力[/ふきだし]';
						editor.execCommand('mceInsertContent', false, shortcode);
					},
				});
			});

			//ブログパーツリスト生成
			const swellBlogParts = window.swellBlogParts;
			const partsSetList = [];

			Object.keys(swellBlogParts).forEach(function (key) {
				const balData = swellBlogParts[key];
				partsSetList.push({
					text: balData.title,
					onclick() {
						const shortcode = '[blog_parts id="' + key + '"]';
						editor.execCommand('mceInsertContent', false, shortcode);
					},
				});
			});

			//広告タグリスト生成
			const swellAdTags = window.swellAdTags;
			const normalAdList = [];
			const affiliateAdList = [];
			const amazonAdList = [];
			const rankingAdList = [];

			Object.keys(swellAdTags).forEach(function (key) {
				const adTagData = swellAdTags[key];
				if ('normal' === adTagData.type) {
					normalAdList.push({
						text: adTagData.title,
						onclick() {
							const shortcode = '[ad_tag id="' + key + '"]';
							editor.execCommand('mceInsertContent', false, shortcode);
						},
					});
				} else if ('affiliate' === adTagData.type) {
					affiliateAdList.push({
						text: adTagData.title,
						onclick() {
							const shortcode = '[ad_tag id="' + key + '"]';
							editor.execCommand('mceInsertContent', false, shortcode);
						},
					});
				} else if ('amazon' === adTagData.type) {
					amazonAdList.push({
						text: adTagData.title,
						onclick() {
							const shortcode = '[ad_tag id="' + key + '"]';
							editor.execCommand('mceInsertContent', false, shortcode);
						},
					});
				} else if ('ranking' === adTagData.type) {
					rankingAdList.push({
						text: adTagData.title,
						onclick() {
							const shortcode = '[ad_tag id="' + key + '"]';
							editor.execCommand('mceInsertContent', false, shortcode);
						},
					});
				}
			});

			editor.addButton('shortcode_select', {
				title: '特殊パーツ',
				text: '特殊パーツ',
				type: 'menubutton',
				menu: [
					{
						text: 'ショートコード ',
						menu: [
							{
								text: '関連記事',
								onclick() {
									const shortcode = '[post_link id="00"]';
									editor.execCommand('mceInsertContent', false, shortcode);
								},
							},
							{
								text: 'ふきだし',
								menu: balloonSetList,
							},
							{
								text: 'ブログパーツ',
								menu: partsSetList,
							},
							{
								text: '広告タグ',
								menu: [
									{
										text: 'バナー型',
										menu: normalAdList,
									},
									{
										text: 'アフィリエイト型',
										menu: affiliateAdList,
									},
									{
										text: 'Amazon型',
										menu: amazonAdList,
									},
									{
										text: 'ランキング型',
										menu: rankingAdList,
									},
								],
							},
							{
								text: 'フルワイドコンテンツ',
								onclick() {
									const shortcode =
										'[full_wide_content bg="#f7f7f7"]<p>ここにコンテンツを入力</p>[/full_wide_content]';
									editor.execCommand('mceInsertContent', false, shortcode);
								},
							},
						],
					},
					{
						text: 'テーブル',
						menu: [
							{
								text: 'ノーマルテーブル',
								onclick() {
									const return_text =
										'<table><tbody><tr><th>項目</th><td>項目の説明文をここに入力</td></tr><tr><th></th><td></td></tr></tbody></table>';
									editor.execCommand('mceInsertContent', false, return_text);
								},
							},
							{
								text: 'ヘッド付きテーブル',
								onclick() {
									const return_text =
										'<table><thead><tr><th>行の説明</th><th>行の説明</th></tr></thead><tbody><tr><th>項目</th><td>項目の説明文をここに入力</td></tr><tr><th></th><td></td></tr></tbody></table>';
									editor.execCommand('mceInsertContent', false, return_text);
								},
							},
							{
								text: 'シンプルテーブル',
								onclick() {
									const return_text =
										'<table class="is-style-simple"><tbody><tr><th>項目</th><td>項目の説明文をここに入力</td></tr><tr><th></th><td></td></tr></tbody></table>';
									editor.execCommand('mceInsertContent', false, return_text);
								},
							},
							{
								text: 'シンプルテーブル(ヘッド付き)',
								onclick() {
									const return_text =
										'<table class="is-style-simple"><thead><tr><th>行の説明</th><th>行の説明</th></tr></thead><tbody><tr><th>項目</th><td>項目の説明文をここに入力</td></tr><tr><th></th><td></td></tr></tbody></table>';
									editor.execCommand('mceInsertContent', false, return_text);
								},
							},
						],
					},
					{
						text: '2カラム',
						menu: [
							{
								text: '通常',
								onclick() {
									const colClass = 'wp-block-columns has-2-columns';
									const return_text =
										'<div class="' +
										colClass +
										'"><div class="wp-block-column"><p>カラム１</p></div><div class="wp-block-column"><p>カラム2</p></div></div>';
									editor.execCommand('mceInsertContent', false, return_text);
								},
							},
							{
								text: '幅2:1',
								onclick() {
									const colClass = 'wp-block-columns has-2-columns first_big';
									const return_text =
										'<div class="' +
										colClass +
										'"><div class="wp-block-column"><p>カラム１</p></div><div class="wp-block-column"><p>カラム2</p></div></div>';
									editor.execCommand('mceInsertContent', false, return_text);
								},
							},
							{
								text: '幅1:2',
								onclick() {
									const colClass = 'wp-block-columns has-2-columns last_big';
									const return_text =
										'<div class="' +
										colClass +
										'"><div class="wp-block-column"><p>カラム１</p></div><div class="wp-block-column"><p>カラム2</p></div></div>';
									editor.execCommand('mceInsertContent', false, return_text);
								},
							},
							{
								text: 'スマホも2列を維持',
								onclick() {
									const colClass = 'wp-block-columns has-2-columns sp_column2';
									const return_text =
										'<div class="' +
										colClass +
										'"><div class="wp-block-column"><p>カラム１</p></div><div class="wp-block-column"><p>カラム2</p></div></div>';
									editor.execCommand('mceInsertContent', false, return_text);
								},
							},
							{
								text: 'スマホも2列 - 幅2:1',
								onclick() {
									const colClass =
										'wp-block-columns has-2-columns sp_column2 first_big';
									const return_text =
										'<div class="' +
										colClass +
										'"><div class="wp-block-column"><p>カラム１</p></div><div class="wp-block-column"><p>カラム2</p></div></div>';
									editor.execCommand('mceInsertContent', false, return_text);
								},
							},
							{
								text: 'スマホも2列 - 幅1:2',
								onclick() {
									const colClass =
										'wp-block-columns has-2-columns sp_column2 last_big';
									const return_text =
										'<div class="' +
										colClass +
										'"><div class="wp-block-column"><p>カラム１</p></div><div class="wp-block-column"><p>カラム2</p></div></div>';
									editor.execCommand('mceInsertContent', false, return_text);
								},
							},
						],
					},
					{
						text: '3カラム',
						menu: [
							{
								text: '通常',
								onclick() {
									const colClass = 'wp-block-columns has-3-columns';
									const return_text =
										'<div class="' +
										colClass +
										'"><div class="wp-block-column"><p>カラム１</p></div><div class="wp-block-column"><p>カラム2</p></div><div class="wp-block-column"><p>カラム3</p></div></div>';
									editor.execCommand('mceInsertContent', false, return_text);
								},
							},
							{
								text: '幅2:1:1',
								onclick() {
									const colClass = 'wp-block-columns has-3-columns first_big';
									const return_text =
										'<div class="' +
										colClass +
										'"><div class="wp-block-column"><p>カラム１</p></div><div class="wp-block-column"><p>カラム2</p></div><div class="wp-block-column"><p>カラム3</p></div></div>';
									editor.execCommand('mceInsertContent', false, return_text);
								},
							},
							{
								text: '幅1:1:2',
								onclick() {
									const colClass = 'wp-block-columns has-3-columns last_big';
									const return_text =
										'<div class="' +
										colClass +
										'"><div class="wp-block-column"><p>カラム１</p></div><div class="wp-block-column"><p>カラム2</p></div><div class="wp-block-column"><p>カラム3</p></div></div>';
									editor.execCommand('mceInsertContent', false, return_text);
								},
							},
							{
								text: 'スマホ最大２列 - 幅2:1:1',
								onclick() {
									const colClass =
										'wp-block-columns has-3-columns sp_column2 first_big';
									const return_text =
										'<div class="' +
										colClass +
										'"><div class="wp-block-column"><p>カラム１</p></div><div class="wp-block-column"><p>カラム2（スマホは２列）</p></div><div class="wp-block-column"><p>カラム3（スマホは２列）</p></div></div>';
									editor.execCommand('mceInsertContent', false, return_text);
								},
							},
							{
								text: 'スマホ最大２列 - 幅1:1:2',
								onclick() {
									const colClass =
										'wp-block-columns has-3-columns sp_column2 last_big';
									const return_text =
										'<div class="' +
										colClass +
										'"><div class="wp-block-column"><p>カラム１（スマホは２列）</p></div><div class="wp-block-column"><p>カラム2（スマホは２列）</p></div><div class="wp-block-column"><p>カラム3</p></div></div>';
									editor.execCommand('mceInsertContent', false, return_text);
								},
							},
						],
					},
					{
						text: 'キャプション付きボックス',
						menu: [
							{
								text: 'キャプション大',
								onclick() {
									const return_text =
										'<div class="cap_box big_ttl"><div class="cap_box_ttl">キャプション</div><div class="cap_box_content"><p>コンテンツ</p></div></div>';
									editor.execCommand('mceInsertContent', false, return_text);
								},
							},
							{
								text: 'キャプション小',
								onclick() {
									const return_text =
										'<div class="cap_box is-style-small_ttl"><div class="cap_box_ttl">キャプション</div><div class="cap_box_content"><p>コンテンツ</p></div></div>';
									editor.execCommand('mceInsertContent', false, return_text);
								},
							},
							{
								text: 'キャプション枠上',
								onclick() {
									const return_text =
										'<div class="cap_box is-style-onborder_ttl"><div class="cap_box_ttl">キャプション</div><div class="cap_box_content"><p>コンテンツ</p></div></div>';
									editor.execCommand('mceInsertContent', false, return_text);
								},
							},
							{
								text: 'キャプション枠上2',
								onclick() {
									const return_text =
										'<div class="cap_box is-style-onborder_ttl2"><div class="cap_box_ttl">キャプション</div><div class="cap_box_content"><p>コンテンツ</p></div></div>';
									editor.execCommand('mceInsertContent', false, return_text);
								},
							},
						],
					},
				],
			});

			// editor.addCommand('cmd_column2', function () {
			//     var return_text = '<div class="column2_box"><div class="column_item"><p>カラム１</p></div><div class="column_item"><p>カラム2</p></div></div>';
			//     editor.execCommand('mceInsertContent', false, return_text);
			// });

			// editor.addCommand('cmd_column3', function () {
			//     var return_text = '<div class="column3_box"><div class="column_item"><p>カラム１</p></div><div class="column_item"><p>カラム2</p></div><div class="column_item"><p>カラム3</p></div></div>';
			//     editor.execCommand('mceInsertContent', false, return_text);
			// });

			// editor.addCommand('cmd_table', function () {
			//     var return_text = '<table><thead><tr><th>行の説明</th><th>行の説明</th></tr></thead><tbody><tr><th>項目</th><td>項目の説明文をここに入力</td></tr><tr><th></th><td></td></tr></tbody></table>';
			//     editor.execCommand('mceInsertContent', false, return_text);
			// });
		},
		createControl(n, cm) {
			return null;
		},
	});

	tinymce.PluginManager.add('swellButtons', tinymce.plugins.swellButtons); //プラグインID,プラグイン関数名
	//tinymce.PluginManager.add('original_tinymce_button_plugin', tinymce.plugins.original_tinymce_button);
})();
