/**
 * @WordPress dependencies
 */
import { useState } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { Button } from '@wordpress/components';

/**
 * @SWELL dependencies
 */
import { swellApiPath } from './index';

export default function BalloonMigrate({ setIsMigrate }) {
	// 移行処理が始まっているかどうか
	const [isWaiting, setIsWaiting] = useState(false);

	// REST API レスポンスメッセージ
	const [errMessage, setErrMessage] = useState('');

	return (
		<>
			<h1 className='swl-setting__title'>ふきだしセット一覧</h1>
			<hr className='wp-header-end' />
			{errMessage && (
				<div className={`notice is-dismissible error`}>
					<p>{errMessage}</p>
				</div>
			)}
			<div className='swl-setting__body swl-setting-balloon' disabled={isWaiting}>
				<p>
					旧バージョンの古いデータが残っています。以下のボタンからデータの変換を行ってください。
				</p>
				<Button
					isPrimary
					className=''
					onClick={() => {
						if (isWaiting) return;
						// eslint-disable-next-line no-alert
						if (window.confirm('本当にデータを移行してもいいですか？')) {
							setIsWaiting(true);

							apiFetch({
								path: swellApiPath,
								method: 'PATCH',
							})
								.then((res) => {
									// console.log(res.code);
									if ('ok' === res.status) {
										// データ移行モードを終了
										setIsMigrate(false);
									}
									setErrMessage('データ移行に失敗しました。');
								})
								.catch((res) => {
									setErrMessage(res.message);
								});
						}
					}}
				>
					旧データを新データへ移行する
				</Button>
				<p>
					※データの変換を行うと、SWELLのバージョンをダウングレードした時にふきだしが正常に呼び出せなくなる可能性があります。
					<br />
					バックアップを取ってから実行してください。
				</p>
			</div>
		</>
	);
}
