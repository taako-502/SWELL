/**
 * @WordPress dependencies
 */
import { RichText, URLInput } from '@wordpress/block-editor';
import { closeSmall, chevronUp, chevronDown, plus, cog } from '@wordpress/icons';
import { Button, Popover, TextControl, ToggleControl } from '@wordpress/components';

/**
 * @SWELL dependencies
 */
import setBlankRel from '@swell-guten/utils/setBlankRel';
import moveAt from '@swell-guten/utils/moveAt';
import { IconTag } from '../_helper';

/**
 * @Others dependencies
 */
import classnames from 'classnames';

/**
 * リスト項目
 */
export default (props) => {
	const {
		blockName,
		isSelected,
		attributes,
		setAttributes,
		actItem,
		setActItem,
		setFocusBtn,
		isItemControlOpen,
		setItemControlOpen,
	} = props;

	const { linkList, listType, listIconPos, listIconName } = attributes;

	// 項目の情報を更新
	const updateItemAttributes = (newAttributes, index) => {
		const newLinkList = linkList.map((item, idx) => {
			if (index === idx) {
				item = {
					...item,
					...newAttributes,
				};
			}
			return item;
		});

		setAttributes({ linkList: newLinkList });
	};

	// 項目を前に移動
	const moveUpItem = (index) => {
		//先頭の場合は動かさない
		if (0 === index) return;

		// ナビを移動
		setAttributes({ linkList: moveAt(linkList, index, index - 1) });

		//一つ前の番号をセット。
		setActItem(actItem - 1);
	};

	// 項目を後ろに移動
	const moveDownItem = (index) => {
		// 最後の場合は動かさない
		if (linkList.length - 1 === index) return;

		// ナビを移動
		setAttributes({ linkList: moveAt(linkList, index, index + 1) });

		//一つ前の番号をセット。
		setActItem(actItem + 1);
	};

	// 項目追加
	const addItem = () => {
		setAttributes({
			linkList: [...linkList, { text: '', url: '' }],
		});
		// 新しく追加された項目にフォーカス
		setActItem(linkList.length);
	};

	//項目削除
	const removeItem = (index) => {
		setAttributes({
			...linkList.splice(index, 1),
		});

		setActItem(undefined);
	};

	// リスト用クラス
	const listClass = classnames(`${blockName}__list`, {
		[`-type-${listType}`]: !!listType,
		[`-icon-pos-${listIconPos}`]: !!listIconPos && !!listIconName,
	});

	return (
		<>
			<ul className={listClass}>
				{linkList.map((item, index) => (
					<li className={`${blockName}__item`} key={index}>
						<a
							className={`${blockName}__link`}
							href='###'
							onClick={() => {
								setActItem(index);
								setFocusBtn(false);
							}}
						>
							{listIconPos === 'left' && <IconTag iconName={listIconName} />}
							<RichText
								placeholder='テキスト...'
								tagName='span'
								href='###'
								value={item.text}
								onChange={(value) => {
									updateItemAttributes({ text: value }, index);
								}}
							/>
							{listIconPos === 'right' && <IconTag iconName={listIconName} />}
						</a>
						{actItem === index && (
							<>
								<div className='swl-input--url'>
									<span className='__label'>href = </span>
									<URLInput
										className='__input'
										value={item.url}
										onChange={(value) => {
											updateItemAttributes({ url: value }, index);
										}}
										disableSuggestions={!isSelected}
										isFullWidth
									/>
								</div>
								<div className='swell-block-linkList__itemControl'>
									<Button
										showTooltip
										label='項目を上に移動する'
										className={`${blockName}__itemBtn ${blockName}__itemBtn--moveUp`}
										iconSize='20'
										icon={chevronUp}
										onClick={() => {
											moveUpItem(index);
										}}
									/>
									<Button
										showTooltip
										label='項目を削除する'
										className={`${blockName}__itemBtn ${blockName}__itemBtn--remove`}
										icon={closeSmall}
										iconSize='20'
										onClick={() => {
											removeItem(index);
										}}
									/>
									<Button
										showTooltip
										label='項目を下に移動する'
										className={`${blockName}__itemBtn ${blockName}__itemBtn--moveDown`}
										iconSize='20'
										icon={chevronDown}
										onClick={() => {
											moveDownItem(index);
										}}
									/>
									<div>
										<Button
											showTooltip
											label='項目の設定'
											className={`${blockName}__itemBtn ${blockName}__itemBtn--setting`}
											iconSize='20'
											icon={cog}
											onClick={() => {
												setItemControlOpen(!isItemControlOpen);
											}}
										/>
										{isSelected && isItemControlOpen && actItem === index && (
											<Popover
												className={`${blockName}__popover`}
												position='top left'
											>
												<ToggleControl
													label='新しいタブで開く'
													checked={item.target === '_blank'}
													onChange={(value) => {
														const target = value ? '_blank' : undefined;
														const newRel = setBlankRel(value, item.rel);
														updateItemAttributes(
															{
																target,
																rel: newRel,
															},
															index
														);
													}}
												/>
												<TextControl
													label='リンク rel 属性'
													value={item.rel || ''}
													onChange={(value) => {
														updateItemAttributes({ rel: value }, index);
													}}
												/>
											</Popover>
										)}
									</div>
								</div>
							</>
						)}
					</li>
				))}
			</ul>
			{isSelected && (
				<Button className={`${blockName}__addBtn`} icon={plus} onClick={addItem} />
			)}
		</>
	);
};
