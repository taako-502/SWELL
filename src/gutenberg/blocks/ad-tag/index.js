/**
 * @WordPress dependencies
 */
import { memo } from '@wordpress/element';
import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { SelectControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';

/**
 * @Internal dependencies
 */
import metadata from './block.json';
import deprecated from './deprecated';
import blockIcon from './_icon';
import getBlockIcon from '@swell-guten/utils/getBlockIcon';

// ブログパーツ選択肢
const normalList = [
	{
		value: '',
		label: '-- バナー型 --',
	},
];
const afiList = [
	{
		value: '',
		label: '-- アフィリエイト型 --',
	},
];
const amazonList = [
	{
		value: '',
		label: '-- Amazon型 --',
	},
];
const rankingList = [
	{
		value: '',
		label: '-- ランキング型 --',
	},
];

// 広告タグのデータを取得
const swellAdTags = window.swellAdTags || {};
Object.keys(swellAdTags).forEach(function (key) {
	const adTagData = swellAdTags[key];
	if ('normal' === adTagData.type) {
		normalList.push({
			value: key,
			label: adTagData.title,
		});
	} else if ('affiliate' === adTagData.type) {
		afiList.push({
			value: key,
			label: adTagData.title,
		});
	} else if ('amazon' === adTagData.type) {
		amazonList.push({
			value: key,
			label: adTagData.title,
		});
	} else if ('ranking' === adTagData.type) {
		rankingList.push({
			value: key,
			label: adTagData.title,
		});
	}
});

/**
 * Selectコンポーネント
 */
const SelectControls = memo(({ adID, setAttributes }) => {
	return (
		<div className='swellBlock__selectList'>
			<SelectControl
				value={adID}
				options={normalList}
				onChange={(val) => {
					setAttributes({ adID: val });
				}}
			/>
			<SelectControl
				value={adID}
				options={afiList}
				onChange={(val) => {
					setAttributes({ adID: val });
				}}
			/>
			<SelectControl
				value={adID}
				options={amazonList}
				onChange={(val) => {
					setAttributes({ adID: val });
				}}
			/>
			<SelectControl
				value={adID}
				options={rankingList}
				onChange={(val) => {
					setAttributes({ adID: val });
				}}
			/>
		</div>
	);
});

/**
 * 広告タグ
 */
const blockName = 'swell-block-ad-tag';
registerBlockType(metadata.name, {
	icon: getBlockIcon(blockIcon),
	edit: ({ attributes, setAttributes }) => {
		const { adID } = attributes;

		// ブロックProps
		const blockProps = useBlockProps({
			className: `${blockName} swellBlock--getContent`,
		});

		return (
			<div {...blockProps}>
				<SelectControls adID={adID} setAttributes={setAttributes} />
				<div className='swellBlock__preview'>
					{adID ? (
						<ServerSideRender block='loos/ad-tag' attributes={attributes} />
					) : (
						<p>広告タグを選択してください。</p>
					)}
				</div>
			</div>
		);
	},

	save: () => {
		return null;
	},
	deprecated,
});
