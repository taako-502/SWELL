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
import { Icon, close, shortcode, stack, chevronLeft, chevronRight } from '@wordpress/icons';

/**
 * @SWELL dependencies
 */
import { swellApiPath, iconPlaceholder } from './index';

/**
 * @Others dependencies
 */
import classnames from 'classnames';

export default function BalloonList() {
	// REST APIの通信中かどうか
	const [isApiLoaded, setIsApiLoaded] = useState(false);

	// 複製・削除処理中かどうか
	const [isWaiting, setIsWaiting] = useState(false);

	const [showCodeId, setShowCodeId] = useState();

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
			(balloonList || []).filter(({ title }) => {
				return title.toLowerCase().match(regEx);
			})
		);

		setShowCodeId();
	}, [balloonList, searchWord]);

	// ふきだしデータの複製
	const copyBalloon = (id) => {
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
				setShowCodeId();
			})
			.catch((res) => {
				setApiMessage({
					status: 'error',
					text: res.message || 'エラーが発生しました。',
				});
				setIsWaiting(false);
				setShowCodeId();
			});
	};

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
					setShowCodeId();
				})
				.catch((res) => {
					setApiMessage({
						status: 'error',
						text: res.message || 'エラーが発生しました。',
					});
					setIsWaiting(false);
					setShowCodeId();
				});
		}
	};

	// ふきだしデータの並び替え
	const sortBalloon = (idx, direction) => {
		if (idx === undefined || !direction) return;
		if (direction !== 'prev' && direction !== 'next') return;

		// 並び替える二つの吹き出しをセット
		const balloon1 =
			direction === 'prev' ? filteredBalloonList[idx - 1] : filteredBalloonList[idx];
		const balloon2 =
			direction === 'prev' ? filteredBalloonList[idx] : filteredBalloonList[idx + 1];

		if (!balloon1 || !balloon2) return;

		const li1 = document.querySelector(`.swl-setting-balloon__item[data-id="${balloon1.id}"]`);
		li1.classList.add('-to-next');

		const li2 = document.querySelector(`.swl-setting-balloon__item[data-id="${balloon2.id}"]`);
		li2.classList.add('-to-prev');

		setTimeout(() => {
			li1.classList.add('-hide');
			li2.classList.add('-hide');

			// 並び替え
			apiFetch({
				path: `${swellApiPath}-sort`,
				method: 'POST',
				data: { balloon1, balloon2 },
			})
				.then((res) => {
					// state更新
					setBalloonList(res);
					setShowCodeId();

					setTimeout(() => {
						li1.classList.remove('-hide');
						li1.classList.add('-show');
						li2.classList.remove('-hide');
						li2.classList.add('-show');
					}, 100);

					setTimeout(() => {
						li1.classList.remove('-show');
						li2.classList.remove('-show');
						li1.classList.remove('-to-next');
						li2.classList.remove('-to-prev');
					}, 1100);
				})
				.catch((res) => {
					setApiMessage({
						status: 'error',
						text: res.message || 'エラーが発生しました。',
					});
					setShowCodeId();
				});
		}, 400);
	};

	if (!isApiLoaded) {
		return null;
	}

	// ショートコード名
	const balCode = _x('speech_balloon', 'code', 'swell');

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
							setSearchWord(e.target.value);
						}}
					/>
				</div>
				{!filteredBalloonList.length ? (
					<p>ふきだしデータがありません。</p>
				) : (
					<ul className='swl-setting-balloon__list'>
						{filteredBalloonList.map(({ id, title, data }, idx) => {
							const editUrl = addQueryArgs(editBaseUrl, { id });
							const tag = `[${balCode} id="${id}"]${__(
								'ふきだしテキストをここに入力',
								'swell'
							)}[/${balCode}]`;

							const balloonPreview = (
								<div
									className={`c-balloon -bln-${data.align}`}
									data-col={data.col}
									aria-hidden='true'
								>
									<div className={`c-balloon__icon -${data.shape}`}>
										<img
											src={data.icon || iconPlaceholder}
											alt=''
											className='c-balloon__iconImg'
											width='80px'
										/>
										<span className='c-balloon__iconName'>{data.name}</span>
									</div>
									<div
										className={`c-balloon__body -${data.type} -border-${data.border}`}
									>
										<div className='c-balloon__text'>
											ふきだしテキスト
											<span className='c-balloon__shapes'>
												<span className='c-balloon__before'></span>
												<span className='c-balloon__after'></span>
											</span>
										</div>
									</div>
								</div>
							);

							return (
								<li
									key={idx}
									className={classnames('swl-setting-balloon__item', {
										'show-code': showCodeId === id,
									})}
									data-id={id}
								>
									{idx !== 0 && (
										<Button
											className='swl-setting-balloon__arrow -prev'
											label='前に移動'
											onClick={() => {
												sortBalloon(idx, 'prev');
											}}
										>
											<Icon icon={chevronLeft} />
										</Button>
									)}
									{idx !== filteredBalloonList.length - 1 && (
										<Button
											className='swl-setting-balloon__arrow -next'
											label='次に移動'
											onClick={() => {
												sortBalloon(idx, 'next');
											}}
										>
											<Icon icon={chevronRight} />
										</Button>
									)}
									<div key={idx} className='swl-setting-balloon__item__inner'>
										<div className='swl-setting-balloon__btns'>
											<Button
												className='swl-setting-balloon__copyBtn swl-setting-balloon__btn'
												label='ショートコードを表示する'
												onClick={() => {
													if (showCodeId === id) {
														setShowCodeId(undefined);
													} else {
														setShowCodeId(id);
														// ショートコードの表示を待ってから全選択する
														setTimeout(function () {
															document
																.querySelector(
																	`.swl-setting-balloon__item[data-id="${id}"] .swl-setting__codeCopyBox`
																)
																.select();
														}, 100);
													}
												}}
											>
												<Icon
													icon={showCodeId === id ? close : shortcode}
												/>
											</Button>
											<Button
												isSecondary
												className='swl-setting-balloon__copy swl-setting-balloon__btn'
												label='このセットを複製する'
												icon={stack}
												onClick={() => {
													copyBalloon(id);
												}}
											/>
											<Button
												isDestructive
												className='swl-setting-balloon__delete swl-setting-balloon__btn'
												label='このセットを削除する'
												icon={close}
												onClick={() => {
													deleteBalloon(id);
												}}
											/>
										</div>
										<a href={editUrl} className='swl-setting-balloon__link'>
											<div className='swl-setting-balloon__ttl'>{title}</div>
											{balloonPreview}
										</a>
										<div
											className='swl-setting-balloon__code'
											role='button'
											tabIndex='0'
											onClick={() => {
												setShowCodeId();
											}}
											onKeyDown={(event) => {
												event.stopPropagation();
												// check keys if you want
												if (13 === event.keyCode) {
													setShowCodeId();
												}
											}}
										>
											<input
												className='swl-setting__codeCopyBox code'
												type='text'
												readOnly
												value={tag}
												onClick={(event) => {
													event.stopPropagation();
												}}
												onFocus={(event) => {
													event.stopPropagation();
													event.target.select();
												}}
											/>
										</div>
									</div>
								</li>
							);
						})}
					</ul>
				)}
				<a className='components-button is-primary' href={newEditUrl}>
					新規ふきだし追加
				</a>
			</div>
		</>
	);
}
