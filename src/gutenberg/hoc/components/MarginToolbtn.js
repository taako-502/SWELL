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

/**
 * 設定
 */
const mbControls = [
	{
		icon: <span className='swl-null-icon'></span>,
		size: '0',
		title: '無し(0)',
		mbClass: 'u-mb-0',
	},
	{
		icon: <span className='swl-null-icon'></span>,
		size: 'S',
		title: '小(1em)',
		mbClass: 'u-mb-10',
	},

	{
		icon: <span className='swl-null-icon'></span>,
		size: 'M',
		title: '中(2em)',
		mbClass: 'u-mb-20',
	},
	{
		icon: <span className='swl-null-icon'></span>,
		size: 'L',
		title: '大(4em)',
		mbClass: 'u-mb-40',
	},
	{
		icon: <span className='swl-null-icon'></span>,
		size: 'XL',
		title: '特大(6em)',
		mbClass: 'u-mb-60',
	},
];

// クラス名だけを集めた配列を生成する
const classNames = mbControls.map((control) => {
	return control.mbClass;
});

/**
 * MarginToolbtn
 */
export default ({ className, setAttributes }) => {
	const nowClass = className || '';

	// 現在アクティブなボタンを検出
	const activeBtn = useMemo(() => {
		let _activeBtn = null;
		mbControls.forEach((control) => {
			if (hasClass(nowClass, control.mbClass)) {
				_activeBtn = control;
			}
		});
		return _activeBtn;
	}, [nowClass]);

	const activeClass = activeBtn ? activeBtn.mbClass : '';

	return (
		<ToolbarGroup
			className='swell-toolbar'
			isCollapsed={true}
			icon={
				activeBtn ? (
					<span className='swl-toolbtn--margin'>
						{swellIcon.mbCtrolSeted}
						{activeBtn.size}
					</span>
				) : (
					swellIcon.mbCtrol
				)
			}
			label='ブロック下の余白量'
			controls={mbControls.map((control) => {
				const { mbClass } = control; //mapでループ中のボタンのクラス
				const isActive = activeClass === mbClass;

				return {
					...control,
					isActive,
					onClick: () => {
						const newClass = setClass(nowClass, mbClass, classNames, 'u-mb-ctrl');
						setAttributes({ className: newClass });
					},
				};
			})}
		/>
	);
};
