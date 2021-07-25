/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import { SelectControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { memo, useState } from '@wordpress/element';

/**
 * @SWELL dependencies
 */
import { iconColor } from '@swell-guten/config';

/**
 * @Others dependencies
 */
import classnames from 'classnames';

/**
 * ブログパーツ選択肢
 */
const partsTerms = [
	{
		value: 'normal',
		label: '通常のブログパーツ',
	},
	{
		value: 'for_cat',
		label: __('For category', 'swell'),
	},
	{
		value: 'for_tag',
		label: __('For tag', 'swell'),
	},
	{
		value: 'cta',
		label: 'CTA',
	},
	{
		value: 'pattern',
		label: __('Block Pattern', 'swell'),
	},
];

const defaultOption = {
	value: '',
	label: '-- ブログパーツを選択 --',
};

// 用途のセレクトボックス
const partsList = {
	normal: [defaultOption],
	for_cat: [defaultOption],
	for_tag: [defaultOption],
	cta: [defaultOption],
	pattern: [defaultOption],
};

// パーツリストのセレクトボックスを生成
const swellBlogParts = window.swellBlogParts || {};
Object.keys(swellBlogParts).forEach((id) => {
	const partsData = swellBlogParts[id];
	const partsTerm = partsData.term || 'normal';

	// 用途ごとに振り分ける
	partsList[partsTerm].push({
		value: id,
		label: partsData.title,
	});
});

/**
 * Selectコンポーネント
 */
const SelectControls = memo(({ partsID, setAttributes, selectedTerm, setSelectedTerm }) => {
	return (
		<div className='swellBlock__select'>
			<SelectControl
				value={partsID}
				className='-partlist'
				options={partsList[selectedTerm]}
				onChange={(val) => {
					setAttributes({ partsID: val });
				}}
			/>
			<SelectControl
				value={selectedTerm}
				options={partsTerms}
				onChange={(val) => {
					setSelectedTerm(val);
					setAttributes({ partsID: '' });
				}}
			/>
		</div>
	);
});

/**
 * ブログパーツ
 */
registerBlockType('loos/blog-parts', {
	title: 'ブログパーツ',
	description: __('登録済みのブログパーツを呼び出すことができます。', 'swell'),
	icon: {
		foreground: iconColor,
		src: 'welcome-widgets-menus',
	},
	category: 'swell-blocks',
	keywords: ['swell', 'blogparts', 'parts'],
	// example,
	attributes: {
		partsTitle: {
			type: 'string',
			default: '',
		},
		partsID: {
			type: 'string',
			default: '',
		},
	},

	edit: ({ attributes, className, setAttributes }) => {
		const { partsID } = attributes;

		const blockClass = classnames(className, 'swellBlock--getContent');

		let nowSelectedTerm = swellBlogParts[partsID] ? swellBlogParts[partsID].term : '';
		nowSelectedTerm = nowSelectedTerm || 'normal';

		const [selectedTerm, setSelectedTerm] = useState(nowSelectedTerm);

		return (
			<div className={blockClass}>
				<SelectControls {...{ partsID, setAttributes, selectedTerm, setSelectedTerm }} />
				<div className='swellBlock__preview'>
					{partsID ? (
						<ServerSideRender block='loos/blog-parts' attributes={attributes} />
					) : (
						<p>ブログパーツを選択してください。</p>
					)}
				</div>
			</div>
		);
	},

	save: ({ attributes }) => {
		return <div>{'[blog_parts id="' + attributes.partsID + '"]'}</div>;
	},
});
