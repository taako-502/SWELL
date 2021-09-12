/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';

/**
 * @SWELL dependencies
 */
import hasClass from '@swell-guten/utils/hasClass';
import setClass from '@swell-guten/utils/setClass';

const lbOptions = [
	{
		label: '全体設定に従う',
		value: '',
	},
	{
		label: 'オンにする',
		value: 'u-lb-on',
	},
	{
		label: 'オフにする',
		value: 'u-lb-off',
	},
];

/**
 * ギャラリーブロック用HOC
 */
export default ({ attributes, setAttributes }) => {
	const nowClass = attributes.className || '';

	let selectedLbOption = lbOptions.find((option) => hasClass(nowClass, option.value));
	if (selectedLbOption) {
		selectedLbOption = selectedLbOption.value;
	}

	return (
		<InspectorControls>
			<PanelBody title='追加設定' initialOpen={true} className='swl-panel'>
				<SelectControl
					label='クリックして拡大する機能'
					value={selectedLbOption}
					help='デフォルトでオンにするかオフにするかは、SWELL設定から変更できます。'
					options={lbOptions}
					onChange={(val) => {
						const newClass = setClass(nowClass, val, ['u-lb-on', 'u-lb-off']);
						setAttributes({ className: newClass });
					}}
				/>
			</PanelBody>
		</InspectorControls>
	);
};
