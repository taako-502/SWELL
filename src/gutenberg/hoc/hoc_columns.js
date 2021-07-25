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

const styleObj = {
	border_gray: '線あり（グレー）',
	border_main: '線あり（メイン色）',
	shadow_on: '影をつける',
};

/**
 * カラムブロック用HOC
 */
export default function ({ attributes, setAttributes }) {
	const nowClass = attributes.className || '';

	const styleOptions = [
		{
			label: (
				<>
					<div className='prev_wrap_'>
						<div className='prev_ wp-block-columns'>
							<div className='wp-block-column'></div>
						</div>
					</div>
					<span className='title_'>デフォルト</span>
				</>
			),
			value: '',
		},
	];

	const styleKeys = Object.keys(styleObj);
	styleKeys.forEach((key) => {
		styleOptions.push({
			label: (
				<>
					<div className='prev_wrap_' key={`prevColumns_${key}`}>
						<div className={'prev_ wp-block-columns ' + key}>
							<div className='wp-block-column' data-type='core/column'></div>
						</div>
					</div>
					<span className='title_' key={`ttlkey_${key}`}>
						{styleObj[key]}
					</span>
				</>
			),
			value: key,
		});
	});

	let selectedStyle = '';
	for (let i = 0; i < styleOptions.length; i++) {
		const option = styleOptions[i];
		if (hasClass(nowClass, option.value)) {
			selectedStyle = option.value;
			break;
		}
	}

	// スマホのカラム数
	let numSelectedVal = '';
	if (-1 !== nowClass.indexOf('sp_column2')) {
		numSelectedVal = 'sp_column2';
	}

	// Radio Control
	let selectedSpMargin = '';
	const spMargins = [
		{ label: '通常(2em)', value: '' },
		{ label: '広め（4em）', value: 'sp_mb4_' },
		{ label: 'もっと広め(6em)', value: 'sp_mb6_' },
	];

	for (let i = 0; i < spMargins.length; i++) {
		const option = spMargins[i];
		if (hasClass(nowClass, option.value)) {
			selectedSpMargin = option.value;
			break;
		}
	}

	return (
		<InspectorControls>
			<PanelBody title='スタイル' initialOpen={true} className='swl-panel'>
				<RadioControl
					className='swell-style-controls'
					selected={selectedStyle}
					options={styleOptions}
					onChange={(val) => {
						const newClass = setClass(nowClass, val, styleKeys);
						setAttributes({ className: newClass });
					}}
				/>
			</PanelBody>
			<PanelBody title='カラム設定' initialOpen={true} className='swl-panel'>
				<RadioControl
					label='スマホでの列数設定'
					// help=''
					selected={numSelectedVal}
					options={[
						{ label: '1列', value: '' },
						{ label: '2列', value: 'sp_column2' },
					]}
					onChange={(val) => {
						const newClass = setClass(nowClass, val, ['sp_column2']);
						setAttributes({ className: newClass });
					}}
				/>
				<RadioControl
					label='スマホ1列時のカラム間余白量'
					// help=''
					selected={selectedSpMargin}
					options={spMargins}
					onChange={(val) => {
						const newClass = setClass(nowClass, val, ['sp_mb4_', 'sp_mb6_']);
						setAttributes({ className: newClass });
					}}
				/>
			</PanelBody>
		</InspectorControls>
	);
}
