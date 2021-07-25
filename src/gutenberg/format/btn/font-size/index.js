/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { useSelect } from '@wordpress/data';
import { useState, useMemo } from '@wordpress/element';
import { registerFormatType, getActiveFormat } from '@wordpress/rich-text';
import { RichTextToolbarButton } from '@wordpress/block-editor';

/**
 * @Self dependencies
 */
import FormatPopover from './_popover';
import { swellIcon } from '@swell-guten/icon';
import { swellStore } from '@swell-guten/config';

const formatName = 'loos/font-size';
const formatTitle = 'フォントサイズ';

const fontSizes = [
	{
		label: 'XS',
		val: 'xs',
	},
	{
		label: 'S',
		val: 's',
	},
	// {
	//     label: 'M', -> アイコンがない...！
	//     val: 'm',
	// },
	{
		label: 'L',
		val: 'l',
	},
	{
		label: 'XL',
		val: 'xl',
	},
];

registerFormatType(formatName, {
	title: formatTitle,
	tagName: 'span',
	className: 'swl-fz',
	attributes: {
		// style: 'style',
		class: 'class',
	},
	edit: ({ isActive, value, onChange }) => {
		if (useState === undefined) return null;

		const [isAddingColor, setIsAddingColor] = useState(false);

		// SWELLストアから設定を取得
		const isShowTop = useSelect((select) => {
			const swellBlockSettings = select(swellStore).getSettings();
			return swellBlockSettings.show_fz_top;
		}, []);

		// ボタンの表示位置
		const btnKey = isShowTop || isActive ? 'swl-fz' : 'swl-fz-not-active';
		const btnName = isShowTop || isActive ? 'swl-fz' : 'swell-controls';

		// アイコンの下線の色
		const activeSize = useMemo(() => {
			const activeColorFormat = getActiveFormat(value, formatName);
			if (!activeColorFormat) {
				return '';
			}

			// クラス名から判断する
			const currentClass = activeColorFormat.attributes.class;
			if (currentClass) {
				if (-1 !== currentClass.indexOf('u-fz-xs')) {
					return 'xs';
				}
				if (-1 !== currentClass.indexOf('u-fz-s')) {
					return 's';
				}
				if (-1 !== currentClass.indexOf('u-fz-l')) {
					return 'l';
				}
				if (-1 !== currentClass.indexOf('u-fz-xl')) {
					return 'xl';
				}
			}

			return '';
		}, [value, fontSizes]);

		return (
			<>
				<RichTextToolbarButton
					key={btnKey}
					name={btnName}
					title={formatTitle}
					className='format-library-text-color-button'
					icon={
						<>
							{swellIcon.fontSize}
							{isActive && (
								<span
									className='format-library-text-color-button__indicator'
									style={{ backgroundColor: 'currentColor' }}
								/>
							)}
						</>
					}
					onClick={() => {
						setIsAddingColor(true);
					}}
				/>
				{isAddingColor && (
					<FormatPopover
						name={formatName}
						className='components-inline-color-popover -swl-fz'
						isAddingColor={isAddingColor}
						value={value}
						isActive={isActive}
						onChange={onChange}
						onClose={() => setIsAddingColor(false)}
						fontSizes={fontSizes}
						activeSize={activeSize}
					/>
				)}
			</>
		);
	},
});
