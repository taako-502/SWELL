/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, RadioControl } from '@wordpress/components';

/**
 * @SWELL dependencies
 */
import hasClass from '@swell-guten/utils/hasClass';
import setClass from '@swell-guten/utils/setClass';

const imgSizes = [
	{
		label: '指定しない',
		value: '',
	},
	{
		label: '少し小さく表示',
		value: 'size_s',
	},
	{
		label: '小さく表示',
		value: 'size_xs',
	},
];

/**
 * 画像ブロック用HOC
 */
export default ({ attributes, setAttributes }) => {
	const nowClass = attributes.className || '';

	// console.log('hoc: img');

	let selectedImgSize = '';
	for (let i = 0; i < imgSizes.length; i++) {
		const option = imgSizes[i];
		if (hasClass(nowClass, option.value)) {
			selectedImgSize = option.value;
			break;
		}
	}

	// パネル生成
	return (
		<InspectorControls>
			<PanelBody title='追加スタイル' initialOpen={true} className='swl-panel'>
				<RadioControl
					label='画像の表示サイズ'
					selected={selectedImgSize}
					options={imgSizes}
					onChange={(val) => {
						const newClass = setClass(nowClass, val, [
							'size_s',
							'min_width20_',
							'size_xs',
						]);
						setAttributes({ className: newClass });
					}}
				/>
			</PanelBody>
		</InspectorControls>
	);
};
