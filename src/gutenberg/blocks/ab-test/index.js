/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useState } from '@wordpress/element';
import { registerBlockType } from '@wordpress/blocks';
import { InnerBlocks } from '@wordpress/block-editor';

/**
 * @SWELL dependencies
 */
import example from './_example';
import blockIcon from './_icon';
import { iconColor } from '@swell-guten/config';
import SwellTab from '@swell-guten/components/SwellTab';

/**
 * @Others dependencies
 */
import classnames from 'classnames';

/**
 * ABテスト
 */
const blockName = 'swell-block-abTest';
registerBlockType('loos/ab-test', {
	title: 'ABテスト',
	description: __('2つのブロックをランダムに表示します。', 'swell'),
	icon: {
		foreground: iconColor,
		src: blockIcon.abTest,
	},
	category: 'swell-blocks',
	keywords: ['swell', 'ab', 'test'],
	supports: {
		className: false,
	},
	attributes: {},
	example,
	edit: ({ className }) => {
		// 許可されるブロックを登録
		const allowedBlocks = ['loos/ab-test-a', 'loos/ab-test-b'];

		// タブ用のステート
		const defaultTab = 'tab-a';
		const [tabKey, setTabKey] = useState(defaultTab);

		// ブロッククラス
		const blockClass = classnames(className, blockName, tabKey);

		return (
			<>
				<div className={blockClass}>
					<SwellTab
						className='-ab-test'
						tabs={[
							{ key: 'tab-a', label: 'Aブロック' },
							{ key: 'tab-b', label: 'Bブロック' },
						]}
						state={tabKey}
						setState={setTabKey}
					></SwellTab>
					<InnerBlocks
						allowedBlocks={allowedBlocks}
						templateLock={true}
						template={[['loos/ab-test-a'], ['loos/ab-test-b']]}
					/>
				</div>
			</>
		);
	},

	save: () => {
		return <InnerBlocks.Content />;
	},
});

/**
 * A
 */
registerBlockType('loos/ab-test-a', {
	title: 'Aブロック',
	icon: {
		foreground: iconColor,
		src: blockIcon.a,
	},
	category: 'swell-blocks',
	parent: ['loos/ab-test'],
	supports: {
		className: false,
		customClassName: false,
	},
	attributes: {},
	edit: ({ className }) => {
		const blockClass = classnames(className, 'swell-block-abTest__a', 'u-block-guide');
		return (
			<div className={blockClass}>
				<InnerBlocks templateLock={false} template={[['core/paragraph']]} />
			</div>
		);
	},
	save: () => {
		return <InnerBlocks.Content />;
	},
});

/**
 * B
 */
registerBlockType('loos/ab-test-b', {
	title: 'Bブロック',
	icon: {
		foreground: iconColor,
		src: blockIcon.b,
	},
	category: 'swell-blocks',
	parent: ['loos/ab-test'],
	supports: {
		className: false,
		customClassName: false,
	},
	attributes: {},
	edit: ({ className }) => {
		const blockClass = classnames(className, 'swell-block-abTest__b', 'u-block-guide');
		return (
			<div className={blockClass}>
				<InnerBlocks templateLock={false} template={[['core/paragraph']]} />
			</div>
		);
	},
	save: () => {
		return <InnerBlocks.Content />;
	},
});
