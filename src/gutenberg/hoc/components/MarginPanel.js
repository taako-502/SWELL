/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { PanelBody, BaseControl, Button, ButtonGroup } from '@wordpress/components';

/**
 * @Self
 */
import hasClass from '@swell-guten/utils/hasClass';
import setClass from '@swell-guten/utils/setClass';

const mbControls = [
	{ className: 'u-mb-0', label: '0', key: 'mb0' },
	{ className: 'u-mb-10', label: '1', key: 'mb10' },
	{ className: 'u-mb-20', label: '2', key: 'mb20' },
	{ className: 'u-mb-30', label: '3', key: 'mb30' },
	{ className: 'u-mb-40', label: '4', key: 'mb40' },
	{ className: 'u-mb-50', label: '5', key: 'mb50' },
	{ className: 'u-mb-60', label: '6', key: 'mb60' },
];

// クラス名だけを集めた配列を生成する
const mbClassNames = mbControls.map((control) => {
	return control.className;
});

/**
 * コンポーネントを返す
 */
export default ({ className, setAttributes }) => {
	const nowClass = className || '';

	return (
		<PanelBody
			title='ブロック下の余白量'
			initialOpen={hasClass(nowClass, 'u-mb-ctrl')}
			className='swl-panel'
		>
			<BaseControl
				help={
					<small className='u-mt-15 u-lh-15 u-block'>
						※ 単位は em です。
						{/* ※ ツールバーからも設定できます */}
					</small>
				}
			>
				<ButtonGroup className='swl-btns--margin swl-btns--padSmall'>
					{mbControls.map((control) => {
						const thisClassName = control.className;
						const isPrimary = hasClass(nowClass, thisClassName);

						return (
							<Button
								isSecondary={!isPrimary}
								isPrimary={isPrimary}
								onClick={() => {
									const newClass = setClass(
										nowClass,
										thisClassName,
										mbClassNames,
										'u-mb-ctrl'
									);
									setAttributes({
										className: newClass,
									});
								}}
								key={`key_${control.key}`}
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
