/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useMemo } from '@wordpress/element';
import { PanelBody, RadioControl, ToggleControl } from '@wordpress/components';

/**
 * 設定
 */
const iconStyles = {
	'col-text': 'テキスト色',
	'col-main': 'メインカラー',
	'col-custom': 'カスタムカラー',
	'fill-text': 'テキスト色（塗り潰し）',
	'fill-main': 'メインカラー（塗り潰し）',
	'fill-custom': 'カスタムカラー（塗り潰し）',
};

/**
 * FAQコントロール
 */
export default function ({ attributes, setAttributes }) {
	const { iconRadius, qIconStyle, aIconStyle, outputJsonLd } = attributes;

	// Qアイコンの色
	const qIconOptions = useMemo(() => {
		const _qIconOptions = [];
		Object.keys(iconStyles).forEach((key) => {
			_qIconOptions.push({
				label: (
					<span className='faq_prev_q' data-q={key} key={`qkey${key}`}>
						<span className='faq_label_ faq_q'>
							<i>{iconStyles[key]}</i>
						</span>
					</span>
				),
				value: key,
			});
		});

		return _qIconOptions;
	}, []);

	// Aアイコンの色
	const aIconOptions = useMemo(() => {
		const _aIconOptions = [];
		Object.keys(iconStyles).forEach((key) => {
			_aIconOptions.push({
				label: (
					<span className='faq_prev_a' data-a={key} key={`akey${key}`}>
						<span className='faq_label_ faq_a'>
							<i>{iconStyles[key]}</i>
						</span>
					</span>
				),
				value: key,
			});
		});
		return _aIconOptions;
	}, []);

	// パネル生成
	return (
		<PanelBody title='FAQ設定' initialOpen={true} className='faq-controles'>
			<ToggleControl
				label={__('構造化データを出力する', 'swell')}
				help={__('検索結果にリッチリザルトを表示したい時にオンにしてください。', 'swell')}
				checked={outputJsonLd}
				onChange={(val) => {
					setAttributes({ outputJsonLd: val });
				}}
			/>
			<RadioControl
				label='Q / Aのアイコンの形'
				selected={iconRadius}
				options={[
					{
						label: <span className='faq_label_square' title='四角'></span>,
						value: '',
					},
					{
						label: <span className='faq_label_rounded' title='角丸'></span>,
						value: 'rounded',
					},
					{
						label: <span className='faq_label_circle' title='円形'></span>,
						value: 'circle',
					},
				]}
				onChange={(val) => {
					setAttributes({ iconRadius: val });
				}}
			/>
			<RadioControl
				label='Qアイコンの色'
				selected={qIconStyle}
				options={qIconOptions}
				onChange={(val) => {
					setAttributes({ qIconStyle: val });
				}}
			/>
			<RadioControl
				label='Aアイコンの色'
				selected={aIconStyle}
				options={aIconOptions}
				onChange={(val) => {
					setAttributes({ aIconStyle: val });
				}}
			/>
		</PanelBody>
	);
}
