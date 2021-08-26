/**
 * @WordPress dependencies
 */
import { useState } from '@wordpress/element';
import { registerBlockType } from '@wordpress/blocks';
import {
	InnerBlocks,
	InspectorControls,
	useBlockProps,
	__experimentalUseInnerBlocksProps as useInnerBlocksProps,
} from '@wordpress/block-editor';
import {
	BaseControl,
	PanelBody,
	ToggleControl,
	CheckboxControl,
	RadioControl,
	Button,
	DateTimePicker,
	Popover,
} from '@wordpress/components';

/**
 * @SWELL dependencies
 */
import metadata from './block.json';
import blockIcon from './_icon';
import getBlockIcon from '@swell-guten/utils/getBlockIcon';

/**
 * 設定
 */
const loggedInStatuses = [
	{
		label: '非ログインユーザー',
		value: 'noLoggedIn',
	},
	{
		label: 'ログインユーザー',
		value: 'loggedIn',
	},
];

const targetRoles = [
	{
		label: '管理者',
		value: 'administrator',
	},
	{
		label: '編集者',
		value: 'editor',
	},
	{
		label: '投稿者',
		value: 'author',
	},
	{
		label: '寄稿者',
		value: 'contributor',
	},
	{
		label: '購読者',
		value: 'subscriber',
	},
];

const TEMPLATE = [['core/paragraph']];

/**
 * 制限エリア
 */
const blockName = 'swell-block-restrictedArea';
registerBlockType(metadata.name, {
	icon: getBlockIcon(blockIcon),
	edit: ({ attributes, setAttributes }) => {
		const { roles, isRole, isLoggedIn, isDateTime, startDateTime, endDateTime } = attributes;

		// DateTimePickerのステート
		const [isOpenStartDateTime, setIsOpenStartDateTime] = useState(false);
		const [isOpenEndDateTime, setIsOpenEndDateTime] = useState(false);

		// ブロックprops
		const blockProps = useBlockProps({
			className: `${blockName} swl-inner-blocks swl-has-margin--s`,
		});

		const innerBlocksProps = useInnerBlocksProps(blockProps, {
			template: TEMPLATE,
			templateLock: false,
		});

		return (
			<>
				<InspectorControls>
					<PanelBody title='表示の制限' initialOpen={true}>
						<ToggleControl
							label='ログイン制限を有効にする'
							checked={isRole}
							onChange={(val) => setAttributes({ isRole: val })}
						/>
						{isRole && (
							<>
								<RadioControl
									label='コンテンツを見ることのできるユーザー'
									selected={isLoggedIn ? 'loggedIn' : 'noLoggedIn'}
									options={loggedInStatuses}
									onChange={(val) => {
										setAttributes({ isLoggedIn: 'loggedIn' === val });
									}}
								/>
								{isLoggedIn && (
									<BaseControl className='checkbox_group'>
										{targetRoles.map((targetRole) => {
											return (
												<CheckboxControl
													label={targetRole.label}
													key={`key_${targetRole.value}`}
													checked={roles[targetRole.value]}
													onChange={(val) => {
														setAttributes({
															roles: {
																...roles,
																[targetRole.value]: val,
															},
														});
													}}
												/>
											);
										})}
									</BaseControl>
								)}
							</>
						)}
						<ToggleControl
							label='日時範囲制限を有効にする'
							checked={isDateTime}
							onChange={(val) => setAttributes({ isDateTime: val })}
						/>
						{isDateTime && (
							<BaseControl>
								<Button
									isTertiary
									onClick={() => {
										setIsOpenStartDateTime(!isOpenStartDateTime);
									}}
								>
									{isOpenStartDateTime && (
										<Popover
											onFocusOutside={() => {
												setIsOpenStartDateTime(false);
											}}
										>
											<DateTimePicker
												currentDate={startDateTime}
												onChange={(val) => {
													setAttributes({ startDateTime: val });
													setIsOpenStartDateTime(false);
												}}
											/>
										</Popover>
									)}
									{startDateTime ? startDateTime : '開始日時'}
								</Button>
								<Button
									isTertiary
									onClick={() => {
										setIsOpenEndDateTime(!isOpenEndDateTime);
									}}
								>
									{isOpenEndDateTime && (
										<Popover
											onFocusOutside={() => {
												setIsOpenEndDateTime(false);
											}}
										>
											<DateTimePicker
												currentDate={endDateTime}
												onChange={(val) => {
													setAttributes({ endDateTime: val });
													setIsOpenEndDateTime(false);
												}}
											/>
										</Popover>
									)}
									{endDateTime ? endDateTime : '終了日時'}
								</Button>
							</BaseControl>
						)}
					</PanelBody>
				</InspectorControls>
				<div {...innerBlocksProps} />
			</>
		);
	},
	save: () => {
		return <InnerBlocks.Content />;
	},
});
