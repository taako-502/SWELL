/**
 * @wordpress
 */
// import { __ } from '@wordpress/i18n';
import { useMemo } from '@wordpress/element';
import { ToolbarGroup } from '@wordpress/components';

/**
 * インポート
 */
// import classnames from 'classnames';
import { swellIcon } from '@swell-guten/icon';
import hasClass from '@swell-guten/utils/hasClass';
import setClass from '@swell-guten/utils/setClass';

const deviceControls = [
	{
		icon: swellIcon.device.sp,
		title: 'スマホサイズでのみ表示する',
		deviceClass: 'sp_only',
	},
	{
		icon: swellIcon.device.pc,
		title: 'PCサイズでのみ表示する',
		deviceClass: 'pc_only',
	},
];
// クラス名だけを集めた配列
const classNames = ['sp_only', 'pc_only'];

/**
 * DeviceToolbtn
 */
export default ({ className, setAttributes }) => {
	const nowClass = className || '';

	// 現在アクティブなボタンを検出
	const activeBtn = useMemo(() => {
		let _activeBtn = null;
		deviceControls.forEach((control) => {
			if (hasClass(nowClass, control.deviceClass)) {
				_activeBtn = control;
			}
		});
		return _activeBtn;
	}, [nowClass]);

	const activeClass = activeBtn ? activeBtn.deviceClass : '';

	return (
		<ToolbarGroup
			className='swell-toolbar'
			isCollapsed={true}
			icon={activeBtn ? activeBtn.icon : swellIcon.device.btn}
			label='デバイスコントロール'
			controls={deviceControls.map((control) => {
				const { deviceClass } = control;
				const isActive = activeClass === deviceClass;

				return {
					...control,
					isActive,
					onClick: () => {
						const newClass = setClass(nowClass, deviceClass, classNames);
						setAttributes({
							className: newClass,
						});
					},
				};
			})}
		/>
	);
};
