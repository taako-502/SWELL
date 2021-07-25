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

const formatName = 'loos/marker';
const formatTitle = 'マーカー線';

const markerColors = [
	{ name: '橙マーカー', color: 'var(--color_mark_orange)' },
	{ name: '黄マーカー', color: 'var(--color_mark_yellow)' },
	{ name: '緑マーカー', color: 'var(--color_mark_green)' },
	{ name: '青マーカー', color: 'var(--color_mark_blue)' },
];

registerFormatType(formatName, {
	title: formatTitle,
	tagName: 'span',
	className: 'swl-marker',
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
			return swellBlockSettings.show_marker_top;
		}, []);

		// ボタンの表示位置
		const btnKey = isShowTop || isActive ? 'swl-marker' : 'swl-marker-not-active';
		const btnName = isShowTop || isActive ? 'swl-marker' : 'swell-controls';

		// アイコンの下線の色
		const activeColor = useMemo(() => {
			const activeColorFormat = getActiveFormat(value, formatName);
			if (!activeColorFormat) {
				return '';
			}

			// クラス名から判断する
			const currentClass = activeColorFormat.attributes.class;
			if (currentClass) {
				if (-1 !== currentClass.indexOf('mark_orange')) {
					return 'var(--color_mark_orange)';
				}
				if (-1 !== currentClass.indexOf('mark_blue')) {
					return 'var(--color_mark_blue)';
				}
				if (-1 !== currentClass.indexOf('mark_green')) {
					return 'var(--color_mark_green)';
				}
				if (-1 !== currentClass.indexOf('mark_yellow')) {
					return 'var(--color_mark_yellow)';
				}
			}

			return '';
		}, [value, markerColors]);

		return (
			<>
				<RichTextToolbarButton
					key={btnKey}
					name={btnName}
					title={formatTitle}
					className='format-library-text-color-button'
					icon={
						isActive ? (
							<>
								{swellIcon.markerActive}
								<span
									className='format-library-text-color-button__indicator'
									style={{ backgroundColor: activeColor }}
								/>
							</>
						) : (
							<>{swellIcon.marker}</>
						)
					}
					onClick={() => {
						setIsAddingColor(true);
					}}
				/>
				{isAddingColor && (
					<FormatPopover
						name={formatName}
						className='components-inline-color-popover'
						isAddingColor={isAddingColor}
						value={value}
						isActive={isActive}
						onChange={onChange}
						onClose={() => setIsAddingColor(false)}
						colors={markerColors}
						activeColor={activeColor}
					/>
				)}
			</>
		);
	},
});
