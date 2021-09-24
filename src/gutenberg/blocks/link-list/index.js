/**
 * @WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls, RichText, useBlockProps } from '@wordpress/block-editor';
import { useState, useEffect } from '@wordpress/element';

/**
 * @SWELL dependencies
 */
import metadata from './block.json';
import blockIcon from './_icon';
import LinkListSidebar from './_sidebar';
import getBlockIcon from '@swell-guten/utils/getBlockIcon';
import sliceIconData from '@swell-guten/utils/sliceIconData';
import List from './components/List';

/**
 * @Others dependencies
 */
import classnames from 'classnames';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

/**
 * リンクリスト
 */
const blockName = 'swell-block-linkList';
registerBlockType(metadata.name, {
	icon: getBlockIcon(blockIcon),
	edit: ({ attributes, setAttributes, isSelected }) => {
		// リスト項目のポップアップ
		const [isItemControlOpen, setItemControlOpen] = useState(false);

		// リスト項目の選択状態
		const [actItem, setActItem] = useState();

		// ボタンの選択状態
		const [isFocusBtn, setFocusBtn] = useState(false);

		// 選択中のリスト項目やブロックの選択状態が変わった場合は、状態を初期化する
		useEffect(() => {
			setItemControlOpen(false);
			setActItem(undefined);
			setFocusBtn(false);
		}, [isSelected]);

		useEffect(() => {
			setItemControlOpen(false);
		}, [actItem]);

		// ブロックprops
		const blockProps = useBlockProps({
			className: blockName,
		});

		return (
			<>
				<InspectorControls>
					<LinkListSidebar {...{ attributes, setAttributes }} />
				</InspectorControls>
				<div {...blockProps}>
					<List
						{...{
							blockName,
							isSelected,
							attributes,
							setAttributes,
							actItem,
							setActItem,
							setFocusBtn,
							isItemControlOpen,
							setItemControlOpen,
						}}
					/>
				</div>
			</>
		);
	},

	save: ({ attributes }) => {
		const { linkList, listType, listIconPos, listIconName } = attributes;

		// アイコン
		const IconTag = ({ iconName }) => {
			if (!iconName) return null;
			const iconData = sliceIconData(iconName);
			if (typeof iconData === 'string') {
				return <i className={iconName + ' __icon'}></i>;
			}
			return <FontAwesomeIcon icon={iconData} className='__icon' />;
		};

		// リスト用クラス
		const listClass = classnames(`${blockName}__list`, {
			[`-type-${listType}`]: !!listType,
			[`-icon-pos-${listIconPos}`]: !!listIconName && !!listIconPos,
		});

		// ブロックprops
		const blockProps = useBlockProps.save({
			className: blockName,
		});

		/* eslint react/jsx-no-target-blank: 0 */
		return (
			<div {...blockProps}>
				{linkList.length > 0 && (
					<ul className={listClass}>
						{linkList.map((item, idx) => {
							return (
								<li className={`${blockName}__item`} key={idx}>
									<a
										className={`${blockName}__link`}
										href={item.url}
										target={item.target}
										rel={item.rel || null}
									>
										{listIconPos === 'left' && (
											<IconTag iconName={listIconName} />
										)}
										<RichText.Content tagName='span' value={item.text} />
										{listIconPos === 'right' && (
											<IconTag iconName={listIconName} />
										)}
									</a>
								</li>
							);
						})}
					</ul>
				)}
			</div>
		);
	},
});
