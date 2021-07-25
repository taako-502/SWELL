/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
// import { memo } from '@wordpress/element';
import { PanelColorSettings } from '@wordpress/block-editor';
import { PanelBody, ToggleControl, TextControl, RadioControl } from '@wordpress/components';
import { useDispatch } from '@wordpress/data';

/**
 * @Inner dependencies
 */
import BorderPanel from '@swell-guten/components/BorderPanel';

export default ({ attributes, setAttributes, clientId }) => {
	// useDispatch が使えなければ null
	if (useDispatch === undefined) return null;

	const {
		tabId,
		tabColor,
		activeTab,
		tabHeaders,
		tabWidthPC,
		tabWidthSP,
		isScrollPC,
		isScrollSP,
	} = attributes;

	const { getBlockOrder } = wp.data.select('core/block-editor');
	const { updateBlockAttributes } = useDispatch('core/block-editor');

	return (
		<>
			<PanelBody title='タブ設定' initialOpen={true}>
				<TextControl
					label='タブブロックのID'
					help='※ 同じページにある他のタブブロックと被らないIDにしてください'
					value={tabId}
					onChange={(val) => {
						setAttributes({ tabId: val });
						const tabBodyIDs = getBlockOrder(clientId);
						for (let i = 0; i < tabBodyIDs.length; i++) {
							updateBlockAttributes(tabBodyIDs[i], {
								tabId: val,
							});
						}
					}}
				/>
				<TextControl
					label='何番目のタブを最初に開いておくか'
					type='number'
					min='1'
					max={tabHeaders.length}
					style={{ maxWidth: '6em' }}
					// help='※ 1始まり'
					value={activeTab + 1}
					onChange={(val) => {
						const newActiveNum = parseInt(val) - 1;
						setAttributes({ activeTab: newActiveNum });

						const tabBodyIDs = getBlockOrder(clientId);
						for (let i = 0; i < tabBodyIDs.length; i++) {
							updateBlockAttributes(tabBodyIDs[i], {
								activeTab: newActiveNum,
							});
						}
					}}
				/>
			</PanelBody>
			<PanelBody title='タブサイズ設定（PC）' initialOpen={true}>
				<RadioControl
					// label='PCサイズ'
					selected={tabWidthPC}
					options={[
						{
							label: 'テキストに合わせる',
							value: 'auto',
						},
						{
							label: '固定幅（PC:25%）',
							value: '25',
						},

						{
							label: '端まで並べる',
							value: 'flex-auto',
						},
						{
							label: '端まで並べる（均等幅で）',
							value: 'flex-50',
						},
					]}
					onChange={(val) => {
						setAttributes({ tabWidthPC: val });
					}}
				/>
				<ToggleControl
					label='ナビをスクロール可能にする'
					checked={isScrollPC}
					onChange={(value) => {
						setAttributes({
							isScrollPC: value,
						});
					}}
				/>
			</PanelBody>
			<PanelBody title='タブサイズ設定（SP）' initialOpen={true}>
				<RadioControl
					// label='SPサイズ'
					selected={tabWidthSP}
					options={[
						{
							label: 'テキストに合わせる',
							value: 'auto',
						},
						{
							label: '固定幅（50%）',
							value: '50',
						},

						{
							label: '端まで並べる',
							value: 'flex-auto',
						},
						{
							label: '端まで並べる（均等幅で）',
							value: 'flex-50',
						},
					]}
					onChange={(val) => {
						setAttributes({ tabWidthSP: val });
					}}
				/>
				<ToggleControl
					label='ナビをスクロール可能にする'
					checked={isScrollSP}
					onChange={(value) => {
						setAttributes({
							isScrollSP: value,
						});
					}}
				/>
			</PanelBody>
			<PanelColorSettings
				title='カラー設定'
				initialOpen={false}
				colorSettings={[
					{
						value: tabColor,
						onChange: (value) => {
							setAttributes({ tabColor: value });
						},
						label: '背景色',
					},
				]}
			></PanelColorSettings>
			<BorderPanel className={attributes.className} setAttributes={setAttributes} />
		</>
	);
};
