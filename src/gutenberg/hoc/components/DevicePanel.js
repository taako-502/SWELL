/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { PanelBody, BaseControl, Button, ButtonGroup } from '@wordpress/components';

/**
 * @Self
 */
import hasClass from '@swell-guten/utils/hasClass';
import setClass from '@swell-guten/utils/setClass';

/**
 * 設定
 */
const deviceControls = [
	{
		label: 'SPサイズ',
		className: 'sp_only',
	},
	{
		label: 'PCサイズ',
		className: 'pc_only',
	},
];

// クラス名だけを集めた配列
const classNames = ['sp_only', 'pc_only'];

/**
 * DevicePanel
 */
export default ({ className, setAttributes }) => {
	const nowClass = className || '';

	return (
		<PanelBody
			title='デバイス制限'
			initialOpen={hasClass(nowClass, 'sp_only') || hasClass(nowClass, 'pc_only')}
			className='swl-panel'
		>
			<BaseControl
				help={
					<small className='u-mt-15 u-lh-15 u-block'>
						※ 選択中のサイズでのみ表示されるようになります。
					</small>
				}
			>
				<BaseControl.VisualLabel>
					{__('表示するデバイスサイズ', 'swell')}
				</BaseControl.VisualLabel>
				<ButtonGroup className='swl-btns--margin swl-btns--padSmall'>
					{deviceControls.map((control) => {
						const thisClassName = control.className;
						const isPrimary = hasClass(nowClass, thisClassName);

						return (
							<Button
								isSecondary={!isPrimary}
								isPrimary={isPrimary}
								onClick={() => {
									const newClass = setClass(nowClass, thisClassName, classNames);
									setAttributes({
										className: newClass,
									});
								}}
								key={`key_${control.className}`}
							>
								{`${control.label}`}
							</Button>
						);
					})}
				</ButtonGroup>
			</BaseControl>
		</PanelBody>
	);
};
