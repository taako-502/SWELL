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
import markerIcon from './icon';
import FormatPopover from './popover';

const name = 'loos/marker';
const title = 'マーカー線';

const markerColors = [
	{ name: '橙マーカー', color: 'var(--color_mark_orange)' },
	{ name: '黄マーカー', color: 'var(--color_mark_yellow)' },
	{ name: '緑マーカー', color: 'var(--color_mark_green)' },
	{ name: '青マーカー', color: 'var(--color_mark_blue)' },
];

export const marker = {
	name,
	title,
	tagName: 'span',
	className: 'swl-marker',
	attributes: {
		class: 'class',
	},
	edit: ({ isActive, value, onChange }) => {
		const [isAdding, setIsAdding] = useState(false);

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
			const activeColorFormat = getActiveFormat(value, name);
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
					title={title}
					className='format-library-text-color-button'
					icon={
						isActive ? (
							<>
								{markerIcon.active}
								<span
									className='format-library-text-color-button__indicator'
									style={{ backgroundColor: activeColor }}
								/>
							</>
						) : (
							<>{markerIcon.normal}</>
						)
					}
					onClick={() => {
						setIsAdding(true);
					}}
				/>
				{isAdding && (
					<FormatPopover
						{...{ name, value, isActive, onChange, isAdding, activeColor }}
						className='components-inline-color-popover'
						colors={markerColors}
						onClose={() => setIsAdding(false)}
					/>
				)}
			</>
		);
	},
};
