/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { useSelect } from '@wordpress/data';
import { useState, useMemo } from '@wordpress/element';
import { getActiveFormat } from '@wordpress/rich-text';
import { RichTextToolbarButton } from '@wordpress/block-editor';

/**
 * @SWELL dependencies
 */
import { swellStore } from '@swell-guten/config';

/**
 * @Self dependencies
 */
import fontSizeIcon from './icon';
import FormatPopover from './popover';

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

const name = 'loos/font-size';
const title = 'フォントサイズ';

export const fontSize = {
	name,
	title,
	tagName: 'span',
	className: 'swl-fz',
	attributes: {
		class: 'class',
	},
	edit: ({ isActive, value, onChange }) => {
		const [isAdding, setIsAdding] = useState(false);

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
			const activeColorFormat = getActiveFormat(value, name);
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
					title={title}
					className='format-library-text-color-button'
					icon={
						<>
							{fontSizeIcon}
							{isActive && (
								<span
									className='format-library-text-color-button__indicator'
									style={{ backgroundColor: 'currentColor' }}
								/>
							)}
						</>
					}
					onClick={() => {
						setIsAdding(true);
					}}
				/>
				{isAdding && (
					<FormatPopover
						{...{ name, value, isActive, onChange, isAdding, fontSizes, activeSize }}
						className='components-inline-color-popover -swl-fz'
						onClose={() => setIsAdding(false)}
					/>
				)}
			</>
		);
	},
};
