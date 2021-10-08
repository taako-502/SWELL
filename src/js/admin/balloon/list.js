/**
 * 「ふきだし」一覧コンテンツ
 */

/**
 * @WordPress dependencies
 */
import { __, _x } from '@wordpress/i18n';
import { useEffect, useState } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { Button } from '@wordpress/components';
import { addQueryArgs } from '@wordpress/url';
import { close } from '@wordpress/icons';

/**
 * @SWELL dependencies
 */
import { swellApiPath, iconPlaceholder } from './index';

export default function BalloonList() {
	// REST APIの通信中かどうか
	const [isApiLoaded, setIsApiLoaded] = useState(false);

	// 削除処理中かどうか
	const [isWaiting, setIsWaiting] = useState(false);

	// REST API レスポンスメッセージ
	const [apiMessage, setApiMessage] = useState();

	// ふきだしデータの配列
	const [balloonList, setBalloonList] = useState([]);

	// ふきだしデータの配列（絞り込み後）
	const [filteredBalloonList, setFilteredBalloonList] = useState([]);

	// 絞り込み検索ワード
	const [searchWord, setSearchWord] = useState('');

	// ふきだしデータの取得
	useEffect(() => {
		apiFetch({
			path: swellApiPath,
			method: 'GET',
		}).then((res) => {
			setIsApiLoaded(true);
			setBalloonList(res);
			setFilteredBalloonList(res);
		});
	}, []);

	// ふきだしデータの絞り込み検索
	useEffect(() => {
		if (!isApiLoaded) {
			return;
		}

		// 正規表現向けの文字をエスケープ
		const escapedSearchWord = searchWord.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');

		const regEx = new RegExp(escapedSearchWord.toLowerCase().trim());

		setFilteredBalloonList(
			balloonList.filter(({ title }) => {
				return title.toLowerCase().match(regEx);
			})
		);
	}, [balloonList, searchWord]);

	// ふきだしデータの削除
	const deleteBalloon = (id) => {
		if (!id) return;

		// eslint-disable-next-line no-alert
		if (window.confirm('本当に削除してもいいですか？')) {
			setIsWaiting(true);

			// データ削除
			apiFetch({
				path: swellApiPath,
				method: 'DELETE',
				data: { id },
			})
				.then((res) => {
					// state更新
					const newBalloonList = balloonList.filter((balloon) => {
						return balloon.id !== id;
					});
					setBalloonList(newBalloonList);

					setApiMessage({
						status: 'updated',
						text: res.message || '削除しました。',
					});

					setIsWaiting(false);
				})
				.catch((res) => {
					setApiMessage({
						status: 'error',
						text: res.message || 'エラーが発生しました。',
					});
					setIsWaiting(false);
				});
		}
	};

	if (!isApiLoaded) {
		return null;
	}

	// ショートコード名
	const balCode = _x('speech_balloon', 'code', 'swell');

	// エディター設定URL
	// const settingUrl = addQueryArgs('admin.php', {
	// 	page: 'swell_settings_editor',
	// 	tab: 'balloon',
	// });

	// ふきだし編集基本リンク
	const editBaseUrl = addQueryArgs('admin.php', {
		page: 'swell_balloon',
	});

	// 新規ふきだし追加リンク
	const newEditUrl = addQueryArgs('admin.php', {
		page: 'swell_balloon',
		post_new: null,
	});

	return (
		<>
			<h1 className='swl-setting__title'>ふきだしセット一覧</h1>
			<hr className='wp-header-end' />
			{apiMessage && !isWaiting && (
				<div className={`notice is-dismissible ${apiMessage.status}`}>
					<p>{apiMessage.text}</p>
					<Button
						className='notice-dismiss'
						onClick={() => {
							setApiMessage();
						}}
					>
						<span className='screen-reader-text'>この通知を非表示にする。</span>
					</Button>
				</div>
			)}
			<div className='swl-setting__body'>
				<div className='swell_settings_balloon' disabled={isWaiting}>
					<input
						className='swell_settings_balloon__search'
						type='text'
						placeHolder='ふきだしを検索...'
						value={searchWord}
						onChange={(e) => {
							setSearchWord(e.target.value);
						}}
					/>
					{!filteredBalloonList.length ? (
						<p>ふきだしデータがありません。</p>
					) : (
						<ul className='swell_settings_balloon__list'>
							{filteredBalloonList.map(({ id, title, data }, idx) => {
								const editUrl = addQueryArgs(editBaseUrl, { id });
								const tag = `[${balCode} id="${id}"]${__(
									'Your text…',
									'swell'
								)}[/${balCode}]`;

								return (
									<li key={idx} className='swell_settings_balloon__item'>
										<a href={editUrl} className='swell_settings_balloon__link'>
											<div className='swell_settings_balloon__ttl'>
												{title}
											</div>
											<div
												className={`c-balloon -bln-${data.align}`}
												data-col={data.col}
											>
												<div className={`c-balloon__icon -${data.shape}`}>
													<img
														src={data.icon || iconPlaceholder}
														alt=''
														className='c-balloon__iconImg'
														width='80px'
													/>
													<span className='c-balloon__iconName'>
														{data.name}
													</span>
												</div>
												<div
													className={`c-balloon__body -${data.type} -border-${data.border}`}
												>
													<div className='c-balloon__text'>
														ふきだしの内容がここに入ります
														<span className='c-balloon__shapes'>
															<span className='c-balloon__before'></span>
															<span className='c-balloon__after'></span>
														</span>
													</div>
												</div>
											</div>
										</a>
										<input
											className='swl-setting__codeCopyBox'
											type='text'
											readOnly
											value={tag}
											onFocus={(event) => {
												event.target.select();
											}}
										/>
										<Button
											isDestructive
											className='swell_settings_balloon__delete'
											label='削除'
											icon={close}
											onClick={() => {
												deleteBalloon(id);
											}}
										/>
									</li>
								);
							})}
						</ul>
					)}
					<a className='components-button is-primary' href={newEditUrl}>
						新規ふきだし追加
					</a>
				</div>
			</div>
		</>
	);
}
