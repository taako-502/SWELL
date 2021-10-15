/**
 * @WordPress dependencies
 */
import { useState, useEffect, createInterpolateElement } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { Button, ButtonGroup, TextControl } from '@wordpress/components';
import { addQueryArgs } from '@wordpress/url';
import { Icon, close, arrowLeft } from '@wordpress/icons';
import { RichText, MediaUpload, ColorPalette } from '@wordpress/block-editor';

/**
 * @SWELL dependencies
 */
import { swellApiPath, iconPlaceholder } from './index';

/**
 * 設定
 */
const shapeBtns = [
	{
		label: '枠なし',
		val: 'square',
	},
	{
		label: '枠あり',
		val: 'circle',
	},
];

// 形
const typeBtns = [
	{
		label: '発言',
		val: 'speaking',
	},
	{
		label: '心の声',
		val: 'thinking',
	},
];

// 方向
const alignBtns = [
	{
		label: '左',
		val: 'left',
	},
	{
		label: '右',
		val: 'right',
	},
];

//ふきだしの線
const borderBtns = [
	{
		label: 'なし',
		val: 'none',
	},
	{
		label: 'あり',
		val: 'on',
	},
];

//色
const colors = [
	{
		name: 'グレー',
		color: 'var(--color_bln_gray)',
	},
	{
		name: 'グリーン',
		color: 'var(--color_bln_green)',
	},
	{
		name: 'ブルー',
		color: 'var(--color_bln_blue)',
	},
	{
		name: 'レッド',
		color: 'var(--color_bln_red)',
	},
	{
		name: 'イエロー',
		color: 'var(--color_bln_yellow)',
	},
];

export default function BalloonEdit({ id, setId }) {
	// タイトル
	const [title, setTitle] = useState('');

	// 入力フォームエラーメッセージ
	const [formError, setFormError] = useState();

	// ふきだしデータ
	const [balloonData, setBalloonData] = useState({
		icon: undefined,
		name: undefined,
		shape: 'square',
		type: 'speaking',
		align: 'left',
		border: 'none',
		col: 'gray',
	});

	// REST APIでのデータ読み込みが完了したかどうか
	const [isApiLoaded, setIsApiLoaded] = useState(!id);

	// 保存・削除処理中かどうか
	const [isWaiting, setIsWaiting] = useState(false);

	// REST API レスポンスメッセージ
	const [apiMessage, setApiMessage] = useState();

	// ページタイトル
	const pageTitle = id ? 'ふきだしセットを編集' : 'ふきだしセットを新規登録';

	// 保存ボタンのテキスト
	const saveLabel = id ? '更新' : '登録';

	// ふきだしデータの取得
	useEffect(() => {
		if (id) {
			apiFetch({
				path: `${swellApiPath}?id=${id}`,
				method: 'GET',
			})
				.then((res) => {
					setTitle(res.title);
					setBalloonData({ ...balloonData, ...res.data });
					setIsApiLoaded(true);
				})
				.catch(() => {
					// データが見つからなかった場合は、新規登録扱いにする
					setId();
					setIsApiLoaded(true);
				});
		}
	}, []);

	// エディター設定URL
	const settingUrl = addQueryArgs('admin.php', {
		page: 'swell_settings_editor',
		tab: 'balloon',
	});

	// ふきだし一覧リンク
	const listUrl = addQueryArgs('admin.php', {
		page: 'swell_balloon',
	});

	// 登録・更新
	const saveBalloon = (event) => {
		// 通常のフォームサブミットを停止
		event.preventDefault();

		// 入力チェック（タイトル）
		if (title === '') {
			setFormError({
				item: 'title',
				message: '※ ふきだしセットのタイトルを入力してください',
			});
			return;
		}

		setIsWaiting(true);

		apiFetch({
			path: swellApiPath,
			method: 'POST',
			data: {
				id,
				title,
				data: balloonData,
			},
		})
			.then((res) => {
				setApiMessage({
					status: 'updated',
					text: res.message || '設定を保存しました。',
				});

				// 新規登録時はIDのstateを更新
				if (res.insertId) {
					setId(res.insertId);
				}

				setIsWaiting(false);
			})
			.catch((res) => {
				setApiMessage({
					status: 'error',
					text: res.message || 'エラーが発生しました。',
				});
				setIsWaiting(false);
			});
	};

	// 複製
	const copyBalloon = (event) => {
		// 通常のフォームサブミットを停止
		event.preventDefault();

		// setIsWaiting(true);

		apiFetch({
			path: `${swellApiPath}-copy`,
			method: 'POST',
			data: { id },
		})
			.then((res) => {
				setIsWaiting(false);
				// 複製されたふきだしの編集画面に遷移
				if (res.id) {
					// ふきだし編集基本リンク
					const editUrl = addQueryArgs('admin.php', {
						page: 'swell_balloon',
						id: res.id,
					});
					window.location.href = editUrl;
				}
			})
			.catch((res) => {
				setApiMessage({
					status: 'error',
					text: res.message || 'エラーが発生しました。',
				});
				setIsWaiting(false);
			});
	};

	// ふきだしデータの削除
	const deleteBalloon = () => {
		if (!id) return;

		// eslint-disable-next-line no-alert
		if (window.confirm('本当に削除してもいいですか？')) {
			setIsWaiting(true);

			apiFetch({
				path: swellApiPath,
				method: 'DELETE',
				data: { id },
			})
				.then(() => {
					window.location.href = listUrl;
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

	/* eslint jsx-a11y/anchor-has-content: 0 */
	const colorHelp = createInterpolateElement(
		'※ ふきだしカラーは「SWELL設定」内の「<a>エディター設定</a>」から編集できます。',
		{
			a: <a href={settingUrl} target='_blank' rel='noreferrer' />,
		}
	);

	return (
		<>
			<h1 className='swl-setting__title'>{pageTitle}</h1>
			<hr className='wp-header-end' />
			{apiMessage && !isWaiting && (
				<div className={`notice is-dismissible ${apiMessage.status}`}>
					<p>
						{apiMessage.text}
						<a href={listUrl} style={{ marginLeft: '8px' }}>
							ふきだしセットの一覧に戻る
						</a>
					</p>
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
			{isApiLoaded && (
				<div className='swl-setting__body'>
					<div className='swl-setting__controls'>
						<Button disabled={isWaiting} isPrimary onClick={saveBalloon}>
							{saveLabel}
						</Button>
						{!!id && (
							<>
								<Button disabled={isWaiting} isSecondary onClick={copyBalloon}>
									複製
								</Button>
								<Button
									disabled={isWaiting}
									isDestructive
									// icon={close}
									// iconSize={16}
									onClick={deleteBalloon}
								>
									削除
								</Button>
							</>
						)}
					</div>
					<form onSubmit={saveBalloon}>
						<div className='swl-setting__editTitle'>
							<TextControl
								placeholder='ふきだしセットのタイトルを入力...'
								value={title}
								onChange={(val) => {
									setTitle(val);
									// if (val === '') {
									// 	setFormError({
									// 		item: 'title',
									// 		message: 'ふきだしセットのタイトルを入力してください',
									// 	});
									// } else {
									// 	setFormError();
									// }
								}}
							/>
							{formError?.item === 'title' && (
								<p className='swl-setting__error'>{formError.message}</p>
							)}
						</div>
						<div className='swell_settings_balloon_edit' disabled={isWaiting}>
							<div className='swell_settings_balloon_edit__inner -left'>
								<div
									className={`c-balloon -bln-${balloonData.align}`}
									data-col={balloonData.col}
								>
									<div className={`c-balloon__icon -${balloonData.shape}`}>
										{balloonData.icon && (
											<Button
												className='swell_settings_balloon_edit__iconDelete'
												isDestructive
												icon={close}
												iconSize={12}
												label='アイコン画像を削除'
												onClick={() => {
													setBalloonData({
														...balloonData,
														icon: undefined,
													});
												}}
											/>
										)}
										<MediaUpload
											allowedTypes={'image'}
											onSelect={(media) => {
												const icon = media.sizes.thumbnail
													? media.sizes.thumbnail.url
													: media.url;
												setBalloonData({
													...balloonData,
													icon,
												});
											}}
											render={({ open }) => (
												<Button
													onClick={open}
													className='swell_settings_balloon_edit__iconSelect'
													label='画像を選択'
													showTooltip={false}
												>
													<span>画像を選択</span>
												</Button>
											)}
										/>
										<img
											src={balloonData.icon || iconPlaceholder}
											alt=''
											className='c-balloon__iconImg'
											width='80px'
										/>
										<RichText
											className='c-balloon__iconName'
											value={balloonData.name}
											placeholder='アイコン名...'
											onChange={(val) => {
												setBalloonData({
													...balloonData,
													name: val,
												});
											}}
										/>
									</div>
									<div
										className={`c-balloon__body -${balloonData.type} -border-${balloonData.border}`}
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
							</div>
							<div className='swell_settings_balloon_edit__inner -right'>
								<div className='swell_settings_balloon_edit__item'>
									<div className='swell_settings_balloon_edit__subttl'>
										アイコンの丸枠
									</div>
									<ButtonGroup>
										{shapeBtns.map((btn) => {
											const isSelected = btn.val === balloonData.shape;
											return (
												<Button
													isSecondary={!isSelected}
													isPrimary={isSelected}
													onClick={() => {
														setBalloonData({
															...balloonData,
															shape: btn.val,
														});
													}}
													key={`key_baloon_shape_${btn.val}`}
												>
													{btn.label}
												</Button>
											);
										})}
									</ButtonGroup>
								</div>
								<div className='swell_settings_balloon_edit__item'>
									<div className='swell_settings_balloon_edit__subttl'>
										ふきだしの形
									</div>
									<ButtonGroup>
										{typeBtns.map((btn) => {
											const isSelected = btn.val === balloonData.type;
											return (
												<Button
													isSecondary={!isSelected}
													isPrimary={isSelected}
													onClick={() => {
														setBalloonData({
															...balloonData,
															type: btn.val,
														});
													}}
													key={`key_baloon_type_${btn.val}`}
												>
													{btn.label}
												</Button>
											);
										})}
									</ButtonGroup>
								</div>
								<div className='swell_settings_balloon_edit__item'>
									<div className='swell_settings_balloon_edit__subttl'>
										ふきだしの向き
									</div>
									<ButtonGroup>
										{alignBtns.map((btn) => {
											const isSelected = btn.val === balloonData.align;
											return (
												<Button
													isSecondary={!isSelected}
													isPrimary={isSelected}
													onClick={() => {
														setBalloonData({
															...balloonData,
															align: btn.val,
														});
													}}
													key={`key_baloon_align_${btn.val}`}
												>
													{btn.label}
												</Button>
											);
										})}
									</ButtonGroup>
								</div>
								<div className='swell_settings_balloon_edit__item'>
									<div className='swell_settings_balloon_edit__subttl'>
										ふきだしの線
									</div>
									<ButtonGroup>
										{borderBtns.map((btn) => {
											const isSelected = btn.val === balloonData.border;
											return (
												<Button
													isSecondary={!isSelected}
													isPrimary={isSelected}
													onClick={() => {
														setBalloonData({
															...balloonData,
															border: btn.val,
														});
													}}
													key={`key_baloon_border_${btn.val}`}
												>
													{btn.label}
												</Button>
											);
										})}
									</ButtonGroup>
								</div>
								<div className='swell_settings_balloon_edit__item'>
									<div className='swell_settings_balloon_edit__subttl'>
										ふきだしの色
									</div>
									<ColorPalette
										value={`var(--color_bln_${balloonData.col})`}
										colors={colors}
										disableCustomColors={true}
										clearable={false}
										onChange={(val) => {
											val = val || '';
											val = val.replace('var(--color_bln_', '');
											val = val.replace(')', '');
											setBalloonData({
												...balloonData,
												col: val,
											});
										}}
									/>
									<p className=''>{colorHelp}</p>
								</div>
							</div>
						</div>
					</form>
					<a href={listUrl} className='swl-setting__backLink'>
						<Icon icon={arrowLeft} />
						ふきだしセットの一覧に戻る
					</a>
				</div>
			)}
		</>
	);
}
