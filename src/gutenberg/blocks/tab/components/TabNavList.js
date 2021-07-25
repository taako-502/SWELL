/* eslint jsx-a11y/click-events-have-key-events: 0 */
/* eslint jsx-a11y/no-static-element-interactions: 0 */
/* eslint jsx-a11y/role-supports-aria-props: 0 */

/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { RichText } from '@wordpress/block-editor';
import { Tooltip } from '@wordpress/components';
import { Icon, plus, chevronLeft, chevronRight, closeSmall } from '@wordpress/icons';
import { memo } from '@wordpress/element';

/**
 * @Inner dependencies
 */

/**
 * TabNavList
 */
export default memo((props) => {
	const {
		blockName,
		isSelected,
		attributes,
		// setAttributes,
		actTab,
		setActTab,
		updateTabsHeader,
		moveUpTab,
		moveDownTab,
		addTab,
		removeTab,
	} = props;

	const {
		// isExample,
		// tabId,
		tabHeaders,
		// activeTab,
		// tabWidthPC,
		// tabWidthSP,
		// isScrollPC,
		// isScrollSP,
		// tabColor,
	} = attributes;

	return (
		<ul className={`${blockName}__nav c-tabList`}>
			{tabHeaders.map((item, index) => (
				<li
					key={index}
					className={`swell-block-tab__navItem c-tabList__item ${
						actTab === index ? 'is-active' : ''
					}`}
				>
					<div
						// style={{ color: headerTextColor }}
						className={`${blockName}__navButton c-tabList__button`}
						aria-selected={actTab === index ? 'true' : 'false'}
						onClick={() => {
							setActTab(index);
						}}
					>
						<RichText
							tagName='p'
							placeholder={__('Title', 'swell') + '...'}
							value={item}
							onChange={(value) => updateTabsHeader(value, index)}
							// unstableOnSplit={() => null} // ?
						/>
					</div>
					<div className='swell-block-tab__tooltips'>
						<Tooltip text={__('タブを前に移動する', 'swell')}>
							<span
								className='swl-tabBtn--moveUp'
								data-active={0 === index ? 'false' : 'true'}
								onClick={() => {
									moveUpTab(index);
								}}
							>
								<Icon icon={chevronLeft} />
							</span>
						</Tooltip>
						{/* 一個以上の時、削除ボタンも追加 */}
						{1 < tabHeaders.length && (
							<Tooltip text={__('タブを削除する', 'swell')}>
								<span
									className='swl-tabBtn--remove'
									onClick={() => {
										removeTab(index);
									}}
								>
									<Icon icon={closeSmall} />
								</span>
							</Tooltip>
						)}
						<Tooltip text={__('タブを後ろに移動する', 'swell')}>
							<span
								className='swl-tabBtn--moveDown'
								data-active={tabHeaders.length - 1 === index ? 'false' : 'true'}
								onClick={() => {
									moveDownTab(index);
								}}
							>
								<Icon icon={chevronRight} />
							</span>
						</Tooltip>
					</div>
				</li>
			))}

			{/* ナビ追加ボタン */}
			{isSelected && (
				<li className='swell-block-tab__addBtn'>
					<Tooltip text={__('タブを追加', 'swell')}>
						<span
							className='swl-tabBtn--add'
							onClick={() => {
								addTab();
							}}
						>
							<Icon icon={plus} />
						</span>
					</Tooltip>
				</li>
			)}
		</ul>
	);
});
