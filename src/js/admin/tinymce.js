(function () {
	console.log('SWELL: Loaded tinymce.min.js');

	const tinymce = window.tinymce;

	if (!tinymce) return;

	tinymce.create('tinymce.plugins.swellButtons', {
		// eslint-disable-next-line no-unused-vars
		init(editor, url) {
			const insertCode = (code) => {
				editor.execCommand('mceInsertContent', false, code);
			};

			const insertColmuns2 = (className) => {
				const colClass = `wp-block-columns has-2-columns ${className}`;
				insertCode(
					`<div class="${colClass.trim()}"><div class="wp-block-column"><p>カラム１</p></div><div class="wp-block-column"><p>カラム2</p></div></div>`
				);
			};

			const insertColmuns3 = (className) => {
				const colClass = `wp-block-columns has-3-columns ${className}`;
				insertCode(
					`<div class="${colClass.trim()}"><div class="wp-block-column"><p>カラム１</p></div><div class="wp-block-column"><p>カラム2</p></div><div class="wp-block-column"><p>カラム3</p></div></div>`
				);
			};
			const insertCapBox = (className) => {
				insertCode(
					`<div class="cap_box ${className}"><div class="cap_box_ttl">キャプション</div><div class="cap_box_content"><p>コンテンツ</p></div></div>`
				);
			};

			//ふきだしセットリスト生成
			const balloonSetList = [
				{
					text: '標準',
					onclick() {
						insertCode('[ふきだし]ここにテキストを入力[/ふきだし]');
					},
				},
			];
			const swellBalloons = window.swellBalloons;
			if (swellBalloons) {
				Object.keys(swellBalloons).forEach(function (key) {
					const balData = swellBalloons[key];
					balloonSetList.push({
						text: balData.title,
						onclick() {
							const code = `[ふきだし set="${balData.title}"]ここにテキストを入力[/ふきだし]`;
							insertCode(code);
						},
					});
				});
			}

			//ブログパーツリスト生成
			const partsSetList = [];
			const swellBlogParts = window.swellBlogParts;
			if (swellBlogParts) {
				Object.keys(swellBlogParts).forEach(function (key) {
					const balData = swellBlogParts[key];
					partsSetList.push({
						text: balData.title,
						onclick() {
							insertCode(`[blog_parts id="${key}"]`);
						},
					});
				});
			}

			//広告タグリスト生成
			const adSetList = [];
			const normalAdList = [];
			const affiliateAdList = [];
			const amazonAdList = [];
			const rankingAdList = [];
			const swellAdTags = window.swellAdTags;
			if (swellAdTags) {
				Object.keys(swellAdTags).forEach(function (key) {
					const adTagData = swellAdTags[key];
					if ('normal' === adTagData.type) {
						normalAdList.push({
							text: adTagData.title,
							onclick() {
								insertCode(`[ad_tag id="${key}"]`);
							},
						});
					} else if ('affiliate' === adTagData.type) {
						affiliateAdList.push({
							text: adTagData.title,
							onclick() {
								insertCode(`[ad_tag id="${key}"]`);
							},
						});
					} else if ('amazon' === adTagData.type) {
						amazonAdList.push({
							text: adTagData.title,
							onclick() {
								insertCode(`[ad_tag id="${key}"]`);
							},
						});
					} else if ('ranking' === adTagData.type) {
						rankingAdList.push({
							text: adTagData.title,
							onclick() {
								insertCode(`[ad_tag id="${key}"]`);
							},
						});
					}
				});

				if (normalAdList.length > 0) {
					adSetList.push({
						text: 'バナー型',
						menu: normalAdList,
					});
				}
				if (affiliateAdList.length > 0) {
					adSetList.push({
						text: 'アフィリエイト型',
						menu: affiliateAdList,
					});
				}
				if (amazonAdList.length > 0) {
					adSetList.push({
						text: 'Amazon型',
						menu: amazonAdList,
					});
				}
				if (rankingAdList.length > 0) {
					adSetList.push({
						text: 'ランキング型',
						menu: rankingAdList,
					});
				}
			}

			const shortcodeSelectMenu = [
				{
					text: '関連記事',
					onclick() {
						insertCode('[post_link id="00"]');
					},
				},
				{
					text: 'フルワイドコンテンツ',
					onclick() {
						insertCode(
							'[full_wide_content bg="#f7f7f7"]<p>ここにコンテンツを入力</p>[/full_wide_content]'
						);
					},
				},
			];

			if (balloonSetList.length > 0) {
				shortcodeSelectMenu.push({
					text: 'ふきだし',
					menu: balloonSetList,
				});
			}

			if (partsSetList.length > 0) {
				shortcodeSelectMenu.push({
					text: 'ブログパーツ',
					menu: partsSetList,
				});
			}

			if (adSetList.length > 0) {
				shortcodeSelectMenu.push({
					text: '広告タグ',
					menu: adSetList,
				});
			}

			const th = '<th>項目</th>';
			const td = '<td>テキストここに入力</td>';

			editor.addButton('shortcode_select', {
				title: '特殊パーツ',
				text: '特殊パーツ',
				type: 'menubutton',
				menu: [
					{
						text: 'ショートコード ',
						menu: shortcodeSelectMenu,
					},
					{
						text: 'テーブル',
						menu: [
							{
								text: 'ノーマルテーブル',
								onclick() {
									insertCode(
										`<table><tbody><tr>${th}${td}</tr><tr>${th}${td}</tr></tbody></table>`
									);
								},
							},
							{
								text: 'ヘッド付きテーブル',
								onclick() {
									insertCode(
										`<table><thead><tr><th>行の説明</th><th>行の説明</th></tr></thead><tbody><tr>${th}${td}</tr><tr>${th}${td}</tr></tbody></table>`
									);
								},
							},
							{
								text: 'シンプルテーブル',
								onclick() {
									insertCode(
										`<figure class="is-style-simple"><table><tbody><tr>${th}${td}</tr><tr>${th}${td}</tr></tbody></table></figure>`
									);
								},
							},
							{
								text: 'シンプルテーブル(ヘッド付き)',
								onclick() {
									insertCode(
										`<figure class="is-style-simple"><table><thead><tr><th>行の説明</th><th>行の説明</th></tr></thead><tbody><tr>${th}${td}</tr><tr>${th}${td}</tr></tbody></table></figure>`
									);
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
									insertColmuns2('');
								},
							},
							{
								text: '幅2:1',
								onclick() {
									insertColmuns2('first_big');
								},
							},
							{
								text: '幅1:2',
								onclick() {
									insertColmuns2('last_big');
								},
							},
							{
								text: 'スマホも2列を維持',
								onclick() {
									insertColmuns2('sp_column2');
								},
							},
							{
								text: 'スマホも2列 - 幅2:1',
								onclick() {
									insertColmuns2('sp_column2 first_big');
								},
							},
							{
								text: 'スマホも2列 - 幅1:2',
								onclick() {
									insertColmuns2('sp_column2 last_big');
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
									insertColmuns3('');
								},
							},
							{
								text: '幅2:1:1',
								onclick() {
									insertColmuns3('first_big');
								},
							},
							{
								text: '幅1:1:2',
								onclick() {
									insertColmuns3('last_big');
								},
							},
							{
								text: 'スマホ最大２列 - 幅2:1:1',
								onclick() {
									insertColmuns3('sp_column2 first_big');
								},
							},
							{
								text: 'スマホ最大２列 - 幅1:1:2',
								onclick() {
									insertColmuns3('sp_column2 last_big');
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
									insertCapBox('big_ttl');
								},
							},
							{
								text: 'キャプション小',
								onclick() {
									insertCapBox('is-style-small_ttl');
								},
							},
							{
								text: 'キャプション枠上',
								onclick() {
									insertCapBox('is-style-onborder_ttl');
								},
							},
							{
								text: 'キャプション枠上2',
								onclick() {
									insertCapBox('is-style-onborder_ttl2');
								},
							},
						],
					},
				],
			});
		},
		// eslint-disable-next-line no-unused-vars
		createControl(n, cm) {
			return null;
		},
	});

	tinymce.PluginManager.add('swellButtons', tinymce.plugins.swellButtons); //プラグインID,プラグイン関数名
})();
