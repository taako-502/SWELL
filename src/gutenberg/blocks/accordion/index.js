/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useEffect, useCallback } from '@wordpress/element';
import { useDispatch, useSelect } from '@wordpress/data';
import { registerBlockType } from '@wordpress/blocks';
import { InnerBlocks, InspectorControls } from '@wordpress/block-editor';

/**
 * @SWELL dependencies
 */
import blockIcon from './_icon';
import example from './_example';
import AcordionSidebar from './_sidebar';
import { iconColor } from '@swell-guten/config';

/**
 * @Others dependencies
 */
import classnames from 'classnames';

/**
 * アコーディオンブロック
 */
const blockName = 'swell-block-accordion';
registerBlockType('loos/accordion', {
	title: 'アコーディオン',
	description: __('クリックで展開できるアコーディオンコンテンツを作成できます。', 'swell'),
	icon: {
		foreground: iconColor,
		src: blockIcon.accordion,
	},
	category: 'swell-blocks',
	keywords: ['swell', 'accordion'],
	supports: {
		anchor: true,
		className: false, //ブロック要素を作成した際に付く .wp-block-[ブロック名]で自動生成されるクラス名の設定。
	},
	styles: [
		{ name: 'default', label: 'デフォルト', isDefault: true },
		{ name: 'simple', label: 'シンプル' },
		{ name: 'border', label: '囲い枠' },
		{ name: 'main', label: 'メインカラー' },
	],
	attributes: {
		iconOpened: {
			type: 'string',
			default: 'icon-arrow_drop_up',
		},
		iconClosed: {
			type: 'string',
			default: 'icon-arrow_drop_down',
		},
	},
	example,
	edit: ({ attributes, setAttributes, className, clientId }) => {
		const blockClass = classnames(className, blockName);

		// 子ブロックを取得
		const childBlocks = useSelect(
			(select) => select('core/block-editor').getBlocks(clientId),
			[clientId]
		);
		// console.log(childBlocks);

		// 子ブロックの更新に使う
		const { updateBlockAttributes } = useDispatch('core/block-editor');

		const updateIcons = useCallback(
			(newIconObj) => {
				// 自分を更新
				setAttributes(newIconObj);

				// 子ブロックにもアイコン設定を伝達
				childBlocks.forEach((child) => {
					updateBlockAttributes(child.clientId, newIconObj);
				});
			},
			[childBlocks]
		);

		// 子ブロックが追加された時もアイコン同期できるように。
		useEffect(() => {
			updateIcons({
				iconOpened: attributes.iconOpened,
				iconClosed: attributes.iconClosed,
			});
		}, [childBlocks]);

		return (
			<>
				<InspectorControls>
					<AcordionSidebar iconOpened={attributes.iconOpened} updateIcons={updateIcons} />
				</InspectorControls>
				<div className={blockClass}>
					<div className='swell-block-parentSelector'>親ブロックを選択</div>
					<InnerBlocks
						templateLock={false}
						allowedBlocks={['loos/accordion-item']}
						template={[['loos/accordion-item']]}
						renderAppender={InnerBlocks.ButtonBlockAppender}
					/>
				</div>
			</>
		);
	},

	save: () => {
		return (
			<div className={blockName}>
				<InnerBlocks.Content />
			</div>
		);
	},
});
