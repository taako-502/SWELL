/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, RadioControl } from '@wordpress/components';
// import { useEffect } from '@wordpress/element';

/**
 * @SWELL dependencies
 */
import hasClass from '@swell-guten/utils/hasClass';
import setClass from '@swell-guten/utils/setClass';

const styleOptions = [
	{
		label: 'なし',
		value: '',
	},
	{
		label: '下線を付ける',
		value: '-list-under-dashed',
	},
	{
		label: '横並び',
		value: '-list-flex',
	},
];

/**
 * リスト用 HOC
 */
export default ({ attributes, setAttributes }) => {
	let nowClass = attributes.className || '';

	let selectedStyle = '';
	for (let i = 0; i < styleOptions.length; i++) {
		const option = styleOptions[i];
		if (hasClass(nowClass, option.value)) {
			selectedStyle = option.value;
			break;
		}
	}

	// 後方互換
	if (hasClass(nowClass, 'border_bottom')) {
		selectedStyle = '-list-under-dashed';

		// border_bottom を -list-under-dashed へ。
		nowClass = nowClass.replace('border_bottom', '-list-under-dashed');
		setAttributes({ className: nowClass });
	}

	// スタイルが当たっているかどうか
	const hasStyle =
		-1 !== nowClass.indexOf('is-style-') && !hasClass(nowClass, 'is-style-default');

	return (
		<InspectorControls>
			<PanelBody title='リストの追加スタイル' initialOpen={true} className='swl-panel'>
				<RadioControl
					selected={selectedStyle}
					help={
						!hasStyle && (
							<span className='u-mt-15 u-lh-15 u-block swl-error-message'>
								※ デフォルトスタイルでは使用しないでください。
							</span>
						)
					}
					options={styleOptions}
					onChange={(val) => {
						const newClass = setClass(nowClass, val, [
							'-list-under-dashed',
							'-list-flex',
						]);
						setAttributes({ className: newClass });
					}}
				/>
			</PanelBody>
		</InspectorControls>
	);
};
