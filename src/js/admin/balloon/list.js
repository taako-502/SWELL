/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { useEffect, useState, useCallback } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { Button } from '@wordpress/components';
import { addQueryArgs } from '@wordpress/url';

/**
 * @SWELL dependencies
 */
import { swellApiPath } from './index';
import BalloonListItem from './list-item';

// 並び替えアニメーション用の処理
const setSwitchAnimation = (li1, li2, phase) => {
	if (null === li1 || null === li2) return;

	if (1 === phase) {
		li1.classList.add('-to-next');
		li2.classList.add('-to-prev');
	} else if (2 === phase) {
		li1.classList.add('-hide');
		li2.classList.add('-hide');
	} else if (3 === phase) {
		li1.classList.add('-show');
		li2.classList.add('-show');
		li1.classList.remove('-hide');
		li2.classList.remove('-hide');
	} else if (4 === phase) {
		li1.classList.remove('-show');
		li2.classList.remove('-show');
		li1.classList.remove('-to-next');
		li2.classList.remove('-to-prev');
	}
};

export default function BalloonList() {
	// REST APIの通信中かどうか
	const [isApiLoaded, setIsApiLoaded] = useState(false);

	// 複製・削除処理中かどうか
	const [isWaiting, setIsWaiting] = useState(false);

	// REST API レスポンスメッセージ
	const [apiMessage, setApiMessage] = useState();

	// ふきだしデータの配列 (全部)
	const [balloonList, setBalloonList] = useState([]);

	// ふきだしデータの配列（絞り込み後）
	const [filteredBalloonList, setFilteredBalloonList] = useState([]);

	// 絞り込み検索ワード
	const [searchWord, setSearchWord] = useState('');

	// ふきだしデータの初回セット
	useEffect(() => {
		apiFetch({
			path: swellApiPath,
			method: 'GET',
		}).then((res) => {
			setIsApiLoaded(true);
			setBalloonList(res);
			setFilteredBalloonList(res); //初期状態は setBalloonList = setFilteredBalloonList
		});
	}, []);

	// ふきだしデータの絞り込み検索
	const searchBalloon = useCallback(
		(_s) => {
			const escapedWord = _s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); // 正規表現向けの文字をエスケープ
			const regEx = new RegExp(escapedWord.toLowerCase().trim());

			setFilteredBalloonList(
				(balloonList || []).filter(({ title }) => {
					return title.toLowerCase().match(regEx);
				})
			);
		},
		[balloonList]
	);

	// ふきだしリストの更新・絞り込み検索結果を反映
	useEffect(() => {
		if (!isApiLoaded) return;

		if (searchWord) {
			searchBalloon(searchWord);
		} else {
			setFilteredBalloonList(balloonList);
		}
	}, [balloonList, searchWord]);

	// ふきだしデータの複製
	const copyBalloon = useCallback(
		(id) => {
			if (!id) return;

			setIsWaiting(true);

			// データ
			apiFetch({
				path: `${swellApiPath}-copy`,
				method: 'POST',
				data: { id },
			})
				.then((res) => {
					// state更新

					setBalloonList([res, ...balloonList]);

					setApiMessage({
						status: 'updated',
						text: res.message || '複製しました。',
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
		},
		[balloonList]
	);

	// ふきだしデータの削除
	const deleteBalloon = useCallback(
		(id) => {
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
		},
		[balloonList]
	);

	// ふきだしデータの並び替え
	const swapBallons = useCallback(
		(idx, direction) => {
			if (idx === undefined || !direction) return;
			if (direction !== 'prev' && direction !== 'next') return;

			// 並び替える二つの吹き出しをセット
			const balloon1 =
				direction === 'prev' ? filteredBalloonList[idx - 1] : filteredBalloonList[idx];
			const balloon2 =
				direction === 'prev' ? filteredBalloonList[idx] : filteredBalloonList[idx + 1];

			if (!balloon1 || !balloon2) return;

			const li1 = document.querySelector(
				`.swl-setting-balloon__item[data-id="${balloon1.id}"]`
			);
			const li2 = document.querySelector(
				`.swl-setting-balloon__item[data-id="${balloon2.id}"]`
			);

			setSwitchAnimation(li1, li2, 1);

			setTimeout(() => {
				setSwitchAnimation(li1, li2, 2);

				// 並び替え
				apiFetch({
					path: `${swellApiPath}-sort`,
					method: 'POST',
					data: { balloon1, balloon2 },
				})
					.then((res) => {
						// state更新
						setBalloonList(res);

						// アニメーション用クラスのつけ外し
						setTimeout(() => {
							setSwitchAnimation(li1, li2, 3);
						}, 100);

						setTimeout(() => {
							setSwitchAnimation(li1, li2, 4);
						}, 1100);
					})
					.catch((res) => {
						setApiMessage({
							status: 'error',
							text: res.message || 'エラーが発生しました。',
						});
					});
			}, 400);
		},
		[filteredBalloonList]
	);

	// null返すのが早すぎると、「Rendered more hooks than during the previous render.」エラーになる。
	if (!isApiLoaded) {
		return null;
	}

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
			<div className='swl-setting__body swl-setting-balloon' disabled={isWaiting}>
				<div className='swl-setting__controls'>
					<a className='components-button is-primary swl-setting__new' href={newEditUrl}>
						新規ふきだし追加
					</a>
					<input
						className='swl-setting__search'
						type='text'
						placeholder='ふきだしセットを検索...'
						value={searchWord}
						onChange={(e) => {
							setSearchWord(e.target.value); // 検索ワードのstate更新
						}}
					/>
				</div>
				{!filteredBalloonList.length ? (
					<p>ふきだしデータがまだありません。</p>
				) : (
					<ul className='swl-setting-balloon__list'>
						{filteredBalloonList.map((balloonData, idx) => {
							return (
								<BalloonListItem
									key={idx}
									isLast={idx === filteredBalloonList.length - 1}
									{...{
										idx,
										balloonData,
										copyBalloon,
										deleteBalloon,
										swapBallons,
									}}
								/>
							);
						})}
					</ul>
				)}
			</div>
		</>
	);
}
