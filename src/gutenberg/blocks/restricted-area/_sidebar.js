/**
 * @WordPress dependencies
 */
import { dateI18n, __experimentalGetSettings } from '@wordpress/date';
import { useState } from '@wordpress/element';
import {
	PanelBody,
	ToggleControl,
	BaseControl,
	RadioControl,
	CheckboxControl,
	Button,
	Popover,
	DateTimePicker,
	Icon,
} from '@wordpress/components';
import { calendar } from '@wordpress/icons';

/**
 * 設定
 */
const loggedInStatuses = [
	{
		label: '非ログインユーザー',
		value: 'noLoggedIn',
	},
	{
		label: 'ログインユーザー',
		value: 'loggedIn',
	},
];

const targetRoles = [
	{
		label: '管理者',
		value: 'administrator',
	},
	{
		label: '編集者',
		value: 'editor',
	},
	{
		label: '投稿者',
		value: 'author',
	},
	{
		label: '寄稿者',
		value: 'contributor',
	},
	{
		label: '購読者',
		value: 'subscriber',
	},
];

/**
 * 制限エリアコントロール
 */
export default ({ attributes, setAttributes }) => {
	const { roles, isRole, isLoggedIn, isDateTime, startDateTime, endDateTime } = attributes;

	// ポップオーバー用のステート
	const [isOpenStartDateTime, setIsOpenStartDateTime] = useState(false);
	const [isOpenEndDateTime, setIsOpenEndDateTime] = useState(false);

	// 日時の表示設定を取得
	const settings = __experimentalGetSettings();

	// 12時間表記（AM/PM）かどうか
	// const is12Hour = false;
	const is12Hour = /a(?!\\)/i.test(
		settings.formats.time.toLowerCase().replace(/\\\\/g, '').split('').reverse().join('')
	);

	// 表示用の日付を設定にあわせてフォーマット
	const formattedStartDateTime = startDateTime
		? dateI18n(`${settings.formats.date} ${settings.formats.time}`, startDateTime)
		: '未設定';
	const formattedEndDateTime = endDateTime
		? dateI18n(`${settings.formats.date} ${settings.formats.time}`, endDateTime)
		: '未設定';

	return (
		<>
			<PanelBody title='制限設定' initialOpen={true}>
				<ToggleControl
					label='ログイン状態で制限する'
					checked={isRole}
					onChange={(val) => {
						setAttributes({ isRole: val });
					}}
				/>
				{isRole && (
					<>
						<RadioControl
							label='コンテンツを閲覧できるユーザー'
							selected={isLoggedIn ? 'loggedIn' : 'noLoggedIn'}
							options={loggedInStatuses}
							onChange={(val) => {
								setAttributes({ isLoggedIn: 'loggedIn' === val });
							}}
						/>
						{isLoggedIn && (
							<BaseControl className='checkbox_group'>
								{targetRoles.map((targetRole) => {
									return (
										<CheckboxControl
											label={targetRole.label}
											key={`key_${targetRole.value}`}
											checked={roles[targetRole.value]}
											onChange={(val) => {
												setAttributes({
													roles: {
														...roles,
														[targetRole.value]: val,
													},
												});
											}}
										/>
									);
								})}
							</BaseControl>
						)}
					</>
				)}
				<hr />
				<ToggleControl
					label='表示期間を制限する'
					checked={isDateTime}
					onChange={(val) => {
						setAttributes({ isDateTime: val });
					}}
				/>
				{isDateTime && (
					<>
						<BaseControl className='swl-dateTimePicker'>
							<BaseControl.VisualLabel>開始</BaseControl.VisualLabel>
							<Button
								icon={calendar}
								isTertiary
								onClick={() => {
									setIsOpenStartDateTime(true);
								}}
							>
								{formattedStartDateTime}
							</Button>
							{isOpenStartDateTime && (
								<Popover
									onClose={() => {
										setIsOpenStartDateTime(false);
									}}
								>
									<DateTimePicker
										currentDate={startDateTime}
										is12Hour={is12Hour}
										onChange={(val) => {
											setAttributes({ startDateTime: val });
											if (!val) {
												setIsOpenStartDateTime(false);
											}
										}}
									/>
								</Popover>
							)}
						</BaseControl>
						<div className='swl-dash'>〜</div>
						<BaseControl className='swl-dateTimePicker'>
							<BaseControl.VisualLabel>終了</BaseControl.VisualLabel>
							<Button
								icon={calendar}
								isTertiary
								onClick={() => {
									setIsOpenEndDateTime(true);
								}}
							>
								{formattedEndDateTime}
							</Button>
							{isOpenEndDateTime && (
								<Popover
									onClose={() => {
										setIsOpenEndDateTime(false);
									}}
								>
									<DateTimePicker
										currentDate={endDateTime}
										is12Hour={is12Hour}
										onChange={(val) => {
											setAttributes({ endDateTime: val });
											if (!val) {
												setIsOpenEndDateTime(false);
											}
										}}
									/>
								</Popover>
							)}
						</BaseControl>
					</>
				)}
			</PanelBody>
		</>
	);
};
