/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { memo } from '@wordpress/element';
import { PanelBody, Button, ButtonGroup } from '@wordpress/components';

/**
 * @Inner dependencies
 */
// import classnames from 'classnames';
import hasClass from '@swell-guten/utils/hasClass';
import setClass from '@swell-guten/utils/setClass';

const borderSet = [
	{
		label: '01',
		value: '-border01',
	},
	{
		label: '02',
		value: '-border02',
	},
	{
		label: '03',
		value: '-border03',
	},
	{
		label: '04',
		value: '-border04',
	},
];

const classArray = ['has-border', '-border01', '-border02', '-border03', '-border04'];

/**
 * BorderPanel
 * className は attributes.className
 */
export default memo(({ className, setAttributes }) => {
	const nowClass = className || '';

	let nowBorderSet = '';
	const swellBtnsBorder = (
		<ButtonGroup className='swl-style-ctrls'>
			{borderSet.map((border) => {
				const theBorderClass = border.value;
				let isSelected = false;
				if (hasClass(nowClass, 'has-border') && hasClass(nowClass, theBorderClass)) {
					isSelected = true;
					nowBorderSet = theBorderClass;
				}
				return (
					<div className='__btnBox' key={'key_' + theBorderClass}>
						<button
							type='button'
							id={'swellbtn' + theBorderClass}
							className='__btn u-none'
							onClick={() => {
								const selectedClass = isSelected ? '' : theBorderClass;
								const newClass = setClass(
									nowClass,
									selectedClass,
									classArray,
									'has-border'
								);
								setAttributes({ className: newClass });
							}}
						></button>
						<label
							htmlFor={'swellbtn' + theBorderClass}
							className='__labelBtn'
							data-selected={isSelected}
						>
							{' '}
							<span className='__prevWrap'>
								<span className={'__prev has-border ' + theBorderClass} />
							</span>
							<span className='__prevTitle'>{border.label}</span>
						</label>
					</div>
				);
			})}
			<div className='__clearBtn'>
				<Button
					isSmall
					onClick={() => {
						const newClass = setClass(nowClass, '', classArray, 'has-border');
						setAttributes({ className: newClass });
					}}
				>
					{__('ボーダーをクリア', 'swell')}
				</Button>
			</div>
			<div className='__description'>
				<a href={window.swellVars.adminUrl + 'admin.php?page=swell_settings_editor#border'}>
					ボーダーセット管理ページ
				</a>
				からカスタマイズ可能です。
			</div>
		</ButtonGroup>
	);

	return (
		<PanelBody
			title={
				<>
					ボーダー設定{' '}
					<span className='swell-panel-border-prev component-color-indicator'>
						<span className={'has-border ' + nowBorderSet}></span>
					</span>
				</>
			}
			className='swl-panel'
			initialOpen={true}
		>
			{swellBtnsBorder}
		</PanelBody>
	);
});
