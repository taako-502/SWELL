/* eslint jsx-a11y/click-events-have-key-events: 0 */
/* eslint jsx-a11y/no-static-element-interactions: 0 */
/* eslint jsx-a11y/role-supports-aria-props: 0 */

/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { registerBlockType, createBlock } from '@wordpress/blocks';
import {
	InnerBlocks,
	InspectorControls,
	useBlockProps,
	__experimentalUseInnerBlocksProps as useInnerBlocksProps,
} from '@wordpress/block-editor';

import { RawHTML, useEffect, useState, useCallback, useMemo } from '@wordpress/element';
import { useDispatch, useSelect } from '@wordpress/data';

/**
 * @SWELL dependencies
 */
import metadata from './block.json';
import blockIcon from './_icon';
import example from './_example';
import TabSidebar from './_sidebar';
import TabNavList from './components/TabNavList';
import getBlockIcon from '@swell-guten/utils/getBlockIcon';

/**
 * @others dependencies
 */
import classnames from 'classnames';

/**
 * ブロッククラス名
 */
const blockName = 'swell-block-tab';

// 配列の要素を移動させる
function moveAt(array, index, at) {
	// 移動下と移動先が同じ場合や、どちらかが配列の長さを超える場合は return
	if (index === at || index > array.length - 1 || at > array.length - 1) {
		return array;
	}

	const value = array[index];
	const tail = array.slice(index + 1);

	array.splice(index);

	Array.prototype.push.apply(array, tail);

	array.splice(at, 0, value);

	return array;
}

// clientIdからタブIDを生成する （split使うよりsubstring+indexOfの方が速いっぽい）
function generateTabId(clientId) {
	const theId = clientId.substring(0, clientId.indexOf('-'));
	console.log(theId);
	return theId;

	// const newID = clientId.split('-');
	// return newID[0];
}

/**
 * registerBlockType
 */
registerBlockType(metadata.name, {
	icon: getBlockIcon(blockIcon),
	styles: [
		{
			name: 'default',
			label: 'ノーマル',
			isDefault: true,
		},
		{
			name: 'balloon',
			label: 'ふきだし',
		},
		{
			name: 'bb',
			label: '下線',
		},
	],
	example,
	attributes: {
		...metadata.attributes,
		tabHeaders: {
			type: 'array',
			default: [__('Tab 1', 'swell'), __('Tab 2', 'swell')],
		},
	},
	edit: ({ attributes, setAttributes, clientId, isSelected }) => {
		// useSelectが使えなければ null
		if (useSelect === undefined) return null;

		const {
			isExample,
			tabId,
			tabHeaders,
			activeTab,
			tabWidthPC,
			tabWidthSP,
			isScrollPC,
			isScrollSP,
			tabColor,
		} = attributes;

		// デフォルトクラスを強制セット
		useEffect(() => {
			if (isExample) return;
			if (!attributes.className) {
				setAttributes({ className: 'is-style-default' });
			}
		}, [attributes.className]);

		const theTabId = tabId || generateTabId(clientId);

		// タブIDを初回にセット
		useEffect(() => {
			if (!tabId) {
				setAttributes({ tabId: theTabId });
			}
		}, [tabId, theTabId]);

		// エディタ上での開閉状態を管理
		const [actTab, setActTab] = useState(activeTab);

		// getBlockOrderメソッドの準備 memo: useSelectで取得すると更新のタイミングが遅くなる
		const { getBlockOrder } = wp.data.select('core/block-editor');

		//useDispatch系メソッド
		const { removeBlocks, insertBlocks, updateBlockAttributes, moveBlocksUp, moveBlocksDown } =
			useDispatch('core/block-editor');

		// IDの二重登録チェック
		useEffect(() => {
			if (isExample) return;
			const sameIdBlocks = document.querySelectorAll(
				`.swell-block-tab[data-tab-id="${tabId}"]`
			);
			if (sameIdBlocks.length > 1) {
				const newId = generateTabId(clientId);
				setAttributes({ tabId: newId });

				// タブボディ側
				const tabBodyIDs = getBlockOrder(clientId);
				tabBodyIDs.forEach((_tabBodyID) => {
					updateBlockAttributes(_tabBodyID, { tabId: newId });
				});
			}
		}, [clientId]);

		// 順序( id )を再セット
		const resetOrder = useCallback(() => {
			// useSelectで取得すると、どうしても更新のタイミングが遅くなる
			const tabBodyIDs = getBlockOrder(clientId);
			for (let i = 0; i < tabBodyIDs.length; i++) {
				updateBlockAttributes(tabBodyIDs[i], { id: i });
			}
		}, [clientId]);

		// ナビテキスト更新
		const updateTabsHeader = useCallback(
			(header, index) => {
				const newHeaders = tabHeaders.map((item, idx) => {
					if (index === idx) {
						item = header;
					}
					return item;
				});
				setAttributes({ tabHeaders: newHeaders });
			},
			[tabHeaders]
		);

		// タブを前に移動
		const moveUpTab = useCallback(
			(index) => {
				if (0 === index) return; //先頭の場合は動かさない

				const tabBodyIDs = getBlockOrder(clientId);
				const moveBlockID = tabBodyIDs[index];

				// ナビを移動
				const newTabHeaders = tabHeaders;
				moveAt(newTabHeaders, index, index - 1);
				setAttributes({ tabHeaders: newTabHeaders });

				//コンテンツを移動
				moveBlocksUp(moveBlockID, clientId);

				//一つ前の番号をセット。
				setActTab(actTab - 1);

				// id振り直し
				resetOrder();
			},
			[clientId, tabHeaders, actTab, resetOrder]
		);

		// タブを後ろに移動
		const moveDownTab = useCallback(
			(index) => {
				const tabBodyIDs = getBlockOrder(clientId);
				const moveBlockID = tabBodyIDs[index];

				if (tabBodyIDs.length - 1 === index) return; //最後の場合は動かさない

				// ナビを移動
				const newTabHeaders = tabHeaders;
				moveAt(newTabHeaders, index, index + 1);
				setAttributes({ tabHeaders: newTabHeaders });

				//コンテンツを移動
				moveBlocksDown(moveBlockID, clientId);

				//一つ前の番号をセット。
				setActTab(actTab + 1);

				// id振り直し
				resetOrder();
			},
			[clientId, tabHeaders, actTab, resetOrder]
		);

		// タブ追加
		const addTab = useCallback(() => {
			const tabContentBlock = createBlock('loos/tab-body', {
				tabId,
				activeTab,
			});

			insertBlocks(tabContentBlock, tabHeaders.length, clientId);
			setAttributes({
				tabHeaders: [...tabHeaders, __('タブテキスト', 'swell')],
			});
			resetOrder();

			// 新しく追加されたタブにフォーカス
			setActTab(tabHeaders.length);
		}, [clientId, tabId, activeTab, tabHeaders, resetOrder]);

		// タブ削除
		const removeTab = useCallback(
			(index) => {
				// indexと一致する番号のタブを 削除
				const newHeaders = tabHeaders.filter((el, idx) => idx !== index);
				setAttributes({ tabHeaders: newHeaders });

				// コンテンツブロックも削除
				const tabBodyIDs = getBlockOrder(clientId);
				removeBlocks(tabBodyIDs[index], false);

				//選択中のタブが削除されるので、一つ前の番号をセット。(最初のタブが削除される時はそのまま)
				const newFocusTab = 0 !== index ? actTab - 1 : 0;

				setActTab(newFocusTab);

				// id振り直し
				resetOrder();
			},
			[clientId, tabId, tabHeaders, resetOrder]
		);

		// ブロックprops
		const blockProps = useBlockProps({
			className: classnames(blockName, {
				'has-background': !!tabColor,
			}),
			'data-width-pc': tabWidthPC,
			'data-width-sp': tabWidthSP,
			'data-scroll-pc': isScrollPC ? 'true' : null,
			'data-scroll-sp': isScrollSP ? 'true' : null,
			'data-tab-id': tabId,
			style: tabColor ? { backgroundColor: tabColor } : null,
		});
		const innerBlocksProps = useInnerBlocksProps(
			{},
			{
				allowedBlocks: ['loos/tab-body'],
				template: [
					['loos/tab-body', { id: 0, tabId: theTabId }],
					['loos/tab-body', { id: 1, tabId: theTabId }],
				],
				templateLock: false,
			}
		);

		return (
			<>
				<InspectorControls>
					<TabSidebar {...{ attributes, setAttributes, clientId }} />
				</InspectorControls>
				<div {...blockProps}>
					<TabNavList
						{...{
							blockName,
							isSelected,
							attributes,
							actTab,
							setActTab,
							updateTabsHeader,
							moveUpTab,
							moveDownTab,
							addTab,
							removeTab,
						}}
					/>
					<div className='c-tabBody'>{innerBlocksProps.children}</div>
				</div>
				{!isExample && (
					<style>
						{`[data-block="${clientId}"] [data-type="loos/tab-body"]:nth-child(${
							actTab + 1
						}){ display:block; }`}
					</style>
				)}
			</>
		);
	},

	save: ({ attributes }) => {
		const {
			tabId,
			tabHeaders,
			activeTab,
			tabWidthPC,
			tabWidthSP,
			isScrollPC,
			isScrollSP,
			tabColor,
		} = attributes;

		const blockProps = useBlockProps.save({
			className: classnames(blockName, {
				'has-background': !!tabColor,
			}),
			'data-width-pc': tabWidthPC,
			'data-width-sp': tabWidthSP,
			'data-scroll-pc': isScrollPC ? 'true' : null,
			'data-scroll-sp': isScrollSP ? 'true' : null,
			style: tabColor ? { backgroundColor: tabColor } : null,
		});

		return (
			<div {...blockProps}>
				<ul className='c-tabList' role='tablist'>
					{tabHeaders.map((header, index) => (
						<li key={index} className='c-tabList__item' role='presentation'>
							<button
								className={`c-tabList__button`}
								aria-selected={activeTab === index ? 'true' : 'false'}
								aria-controls={`tab-${tabId}-${index}`}
								data-onclick='tabControl'
							>
								<RawHTML>{header}</RawHTML>
							</button>
						</li>
					))}
				</ul>
				<div className='c-tabBody'>
					<InnerBlocks.Content />
				</div>
			</div>
		);
	},
});
