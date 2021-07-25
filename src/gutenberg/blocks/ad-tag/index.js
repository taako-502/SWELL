/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { memo } from '@wordpress/element';
import { registerBlockType } from '@wordpress/blocks';
import { SelectControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';

/**
 * @SWELL dependencies
 */
import { iconColor } from '@swell-guten/config';

/**
 * @Others dependencies
 */
import classnames from 'classnames';

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
	// console.log('memo', normalList);
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
registerBlockType('loos/ad-tag', {
	title: '広告タグ',
	description: __('登録済みの広告タグを呼び出すことができます。', 'swell'),
	icon: {
		foreground: iconColor,
		src: 'tickets-alt',
	},
	category: 'swell-blocks',
	keywords: ['swell', 'ad', 'tag'],
	// example,
	attributes: {
		adID: {
			type: 'string',
			default: '',
		},
	},

	edit: ({ attributes, className, setAttributes }) => {
		const { adID } = attributes;
		const blockClass = classnames(className, 'swellBlock--getContent');

		return (
			<div className={blockClass}>
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

	save: ({ attributes }) => {
		return <div>{'[ad_tag id="' + attributes.adID + '"]'}</div>;
	},
});
