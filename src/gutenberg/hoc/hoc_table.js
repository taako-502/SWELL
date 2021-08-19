/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { InspectorControls } from '@wordpress/block-editor';
import { useEffect } from '@wordpress/element';
import {
	PanelBody,
	ToggleControl,
	CheckboxControl,
	SelectControl,
	Flex,
	FlexItem,
} from '@wordpress/components';

/**
 * @SWELL dependencies
 */
import hasClass from '@swell-guten/utils/hasClass';
import setClass from '@swell-guten/utils/setClass';
import removeClass from '@swell-guten/utils/removeClass';

// 最初のtdをth風にするクラス名
// const td2th = 'td_to_th_';

// Radio Control
const minWidthOptions = [
	{
		label: '指定しない',
		value: '',
	},
	{
		label: '約10%',
		value: 'min_width10_',
	},
	{
		label: '約20%',
		value: 'min_width20_',
	},
	{
		label: '約30%',
		value: 'min_width30_',
	},
];

/**
 * テーブルブロック用HOC
 */
export default ({ attributes, setAttributes, clientId }) => {
	const { head, swlMaxWidth, swlScrollable, swlFixedHead, swlIsFixedLeft } = attributes;
	const nowClass = attributes.className || '';

	// 古いデータを置換
	useEffect(() => {
		let newClass = nowClass;
		const newAttrs = {};

		if (hasClass(newClass, 'sp_scroll_')) {
			newClass = removeClass(newClass, 'sp_scroll_');
			newAttrs.swlScrollable = 'sp';
		}

		if (hasClass(newClass, 'sp_fix_thead_')) {
			newClass = removeClass(newClass, 'sp_fix_thead_');
			newAttrs.swlFixedHead = 'sp';
		}

		newAttrs.className = newClass;
		setAttributes(newAttrs);
	}, []);

	let selectedMinWidth = '';
	for (let i = 0; i < minWidthOptions.length; i++) {
		const option = minWidthOptions[i];
		if (hasClass(nowClass, option.value)) {
			selectedMinWidth = option.value;
			break;
		}
	}

	// let selectedSpOption = '';
	// for (let i = 0; i < spOptions.length; i++) {
	// 	const option = spOptions[i];
	// 	if (hasClass(nowClass, option.value)) {
	// 		selectedSpOption = option.value;
	// 		break;
	// 	}
	// }
	useEffect(() => {
		const scrollableTable = document.querySelector(`[data-block="${clientId}"] table`);
		if (null === scrollableTable) return;
		if ('' === swlScrollable || 'sp' === swlScrollable) {
			scrollableTable.style.width = null;
			scrollableTable.style.maxWidth = null;
		} else {
			scrollableTable.style.width = `${swlMaxWidth}px`;
			scrollableTable.style.maxWidth = `${swlMaxWidth}px`;
		}
	}, [clientId, swlScrollable, swlMaxWidth]);

	return (
		<InspectorControls>
			<PanelBody title='テーブル設定' initialOpen={true} className='swl-panel'>
				<ToggleControl
					label={
						<>
							1列目の<code>td</code>を<code>th</code>風に
						</>
					}
					checked={hasClass(nowClass, 'td_to_th_')}
					onChange={(val) => {
						const addClass = val ? 'td_to_th_' : '';
						const newClass = setClass(nowClass, addClass, ['td_to_th_']);
						setAttributes({ className: newClass });
					}}
				/>
				<ToggleControl
					label='スマホで縦並びに表示する'
					checked={hasClass(nowClass, 'sp_block_')}
					onChange={(val) => {
						const addClass = val ? 'sp_block_' : '';
						const newClass = setClass(nowClass, addClass, ['sp_block_']);
						setAttributes({ className: newClass });
					}}
				/>
				<SelectControl
					label='各列で最低限維持する幅'
					// help='列数に応じて選択してください。（4列あるのに40%を選択するとはみ出します）'
					value={selectedMinWidth}
					options={minWidthOptions}
					onChange={(val) => {
						const newClass = setClass(nowClass, val, [
							'min_width10_',
							'min_width20_',
							'min_width30_',
						]);
						setAttributes({ className: newClass });
					}}
				/>
			</PanelBody>
			<PanelBody title='横スクロール設定' initialOpen={true} className='swl-panel'>
				<SelectControl
					value={swlScrollable}
					options={[
						{
							label: '横スクロールなし',
							value: '',
						},
						{
							label: '横スクロール可能 ( SPのみ )',
							value: 'sp',
						},
						{
							label: '横スクロール可能 ( PCのみ )',
							value: 'pc',
						},
						{
							label: '横スクロール可能 ( SP&PC )',
							value: 'both',
						},
					]}
					onChange={(val) => {
						const newAttrs = { swlScrollable: val };
						if (val) {
							newAttrs.swlFixedHead = '';
							if ('sp' === val) {
								newAttrs.swlMaxWidth = 800;
							} else if ('pc' === val || 'both' === val) {
								newAttrs.swlMaxWidth = 1200;
							}
						}

						setAttributes(newAttrs);
					}}
				/>
				<div data-swl-disable={!swlScrollable || null}>
					<div className='components-base-control__label'>テーブルの横幅</div>
					<Flex align='center' justify='flex-start' style={{ marginBottom: '16px' }}>
						<FlexItem>
							<input
								value={swlMaxWidth}
								type='number'
								step='1'
								onChange={(e) => {
									// typeがnumberなので、intに変換してから保存
									setAttributes({ swlMaxWidth: parseInt(e.target.value) });
								}}
							/>
						</FlexItem>
						<FlexItem style={{ marginLeft: '4px' }}>px</FlexItem>
					</Flex>
					<CheckboxControl
						label='1列目を左端に固定する'
						checked={swlIsFixedLeft}
						onChange={(checked) => {
							setAttributes({ swlIsFixedLeft: checked });
						}}
					/>
				</div>
			</PanelBody>
			<PanelBody title='テーブルヘッダーの固定' initialOpen={true} className='swl-panel'>
				<div data-swl-disable={!(head && head.length) || null}>
					<SelectControl
						value={swlFixedHead}
						options={[
							{
								label: '固定しない',
								value: '',
							},
							{
								label: 'ヘッダーを上部に固定 ( SPのみ )',
								value: 'sp',
							},
							{
								label: 'ヘッダーを上部に固定 ( SP&PC )',
								value: 'both',
							},
						]}
						onChange={(val) => {
							setAttributes({
								swlFixedHead: val,
								...(val ? { swlScrollable: '' } : {}),
							});
						}}
						help='横スクロール可能時にヘッダーを上部に固定することはできません。'
					/>
				</div>
			</PanelBody>
		</InspectorControls>
	);
};
