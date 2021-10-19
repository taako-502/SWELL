/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { useSelect } from '@wordpress/data';
import { useState, useMemo } from '@wordpress/element';
import { removeFormat } from '@wordpress/rich-text';
import { RichTextToolbarButton } from '@wordpress/block-editor';
import { Icon } from '@wordpress/components';

/**
 * @SWELL dependencies
 */
import { swellStore } from '@swell-guten/config';
import getActiveColor from '../../helper/getActiveColor';

/**
 * @Self dependencies
 */
import FormatPopover from './popover';

const name = 'loos/bg-color';
const title = '背景色';

export const bgColor = {
	name,
	title,
	tagName: 'span',
	className: 'swl-bg-color',
	attributes: {
		style: 'style',
		class: 'class',
	},
	edit: ({ isActive, value, onChange }) => {
		const [isAdding, setIsAdding] = useState(false);

		// SWELLストアから設定を取得
		const isShowTop = useSelect((select) => {
			const swellBlockSettings = select(swellStore).getSettings();
			return swellBlockSettings.show_bgcolor_top;
		}, []);

		// ボタンの表示位置
		const btnKey = isShowTop || isActive ? 'bg-color' : 'bg-color-not-active';
		const btnName = isShowTop || isActive ? 'bg-color' : 'swell-controls';

		// カラーパレットの設定を読み取っている？
		const { colors, disableCustomColors } = useSelect((select) => {
			const blockEditorSelect = select('core/block-editor');
			let settings;
			if (blockEditorSelect && blockEditorSelect.getSettings) {
				settings = blockEditorSelect.getSettings();
			} else {
				settings = {};
			}
			return {
				colors: settings.colors || [],
				disableCustomColors: settings.disableCustomColors,
			};
		});

		// アイコンの下線の色
		const colorIndicatorStyle = useMemo(() => {
			const activeColor = getActiveColor(name, value, colors);
			if (!activeColor) {
				return undefined;
			}
			return {
				backgroundColor: activeColor,
			};
		}, [value, colors]);

		// hasColorsToChoose : カラーパレットが有効化どうか。
		const hasColorsToChoose = !colors || true !== disableCustomColors;
		if (!hasColorsToChoose && !isActive) {
			// カラーパレットが無効、かつ設定もない時は null
			return null;
		}

		return (
			<>
				<RichTextToolbarButton
					key={btnKey}
					name={btnName}
					title={title}
					className='format-library-text-color-button'
					icon={
						<>
							<Icon icon='admin-appearance' />
							{isActive && (
								<span
									className='format-library-text-color-button__indicator'
									style={colorIndicatorStyle}
								/>
							)}
						</>
					}
					// カラーパレットが無効だけど過去の設定があれば、removeFormatさせる。
					onClick={() => {
						if (hasColorsToChoose) {
							setIsAdding(true);
						} else {
							onChange(removeFormat(value, name));
						}
					}}
				/>
				{isAdding && (
					<FormatPopover
						{...{ name, value, isActive, onChange, isAdding, colors }}
						className='components-inline-color-popover'
						onClose={() => setIsAdding(false)}
					/>
				)}
			</>
		);
	},
};
