/* eslint jsx-a11y/click-events-have-key-events: 0 */
/* eslint jsx-a11y/no-static-element-interactions: 0 */
/* eslint jsx-a11y/role-supports-aria-props: 0 */

/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import { InnerBlocks } from '@wordpress/block-editor';

/**
 * @SWELL dependencies
 */
import { iconColor } from '@swell-guten/config';
import blockIcon from './_icon';

/**
 * @others dependencies
 */
import classnames from 'classnames';

/**
 * ブロッククラス名
 */
const blockName = 'swell-block-tab';

/**
 * 子ブロック
 */
registerBlockType('loos/tab-body', {
	title: 'タブコンテンツ',
	icon: {
		foreground: iconColor,
		src: blockIcon.tabBody,
	},
	category: 'swell-blocks',
	parent: ['loos/tab'],
	supports: {
		className: false,
		customClassName: false,
		// multiple: false,
		reusable: false,
		html: false,
	},
	attributes: {
		id: {
			//並び順
			type: 'number',
			default: 0,
		},
		tabId: {
			//Tab自体のid
			type: 'string',
			default: '',
		},
		activeTab: {
			type: 'number',
			default: 0,
		},
	},

	edit: ({ className }) => {
		const tabBodyClass = classnames(`${blockName}__body c-tabBody__item`, className);
		return (
			<>
				<div className={tabBodyClass}>
					<InnerBlocks template={[['core/paragraph']]} templateLock={false} />
				</div>
			</>
		);
	},
	save: ({ attributes }) => {
		const { tabId, id, activeTab } = attributes;
		return (
			<div
				id={`tab-${tabId}-${id}`}
				className='c-tabBody__item'
				aria-hidden={activeTab === id ? 'false' : 'true'}
			>
				<InnerBlocks.Content />
			</div>
		);
	},
});
