/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useMemo } from '@wordpress/element';
import {
	PanelBody,
	TextControl,
	BaseControl,
	// CheckboxControl,
	RadioControl,
	ButtonGroup,
	Button,
	ToggleControl,
	RangeControl,
} from '@wordpress/components';

/**
 * 設定
 */
const listTypeOptions = [
	{
		label: __('Card type', 'swell'),
		value: 'card',
	},
	{
		label: __('List type', 'swell'),
		value: 'list',
	},
	{
		label: __('Text type', 'swell'),
		value: 'simple',
	},
];

const hTags = [
	{
		label: 'h2',
		val: 'h2',
	},
	{
		label: 'h3',
		val: 'h3',
	},
	{
		label: 'div',
		val: 'div',
	},
];

const pcColbuttonData = [
	{
		label: '1列',
		val: '1',
	},
	{
		label: '2列',
		val: '2',
	},
	{
		label: '3列',
		val: '3',
	},
];

const spColbuttonData = [
	{
		label: '1列',
		val: '1',
	},
	{
		label: '2列',
		val: '2',
	},
];

export default function ({ attributes, setAttributes }) {
	const {
		rssUrl,
		pageName,
		useCache,
		listType,
		listCountPC,
		listCountSP,
		showDate,
		showAuthor,
		showSite,
		showThumb,
		hTag,
		pcCol,
		spCol,
		// excerptLength,
	} = attributes;

	// トグルコントロール
	const toggleData = useMemo(() => {
		const _toggleData = [
			{
				name: 'showSite',
				label: __('Show page name of site', 'swell'),
				value: showSite,
			},
			{
				name: 'showDate',
				label: __('Show release date', 'swell'),
				description: '',
				value: showDate,
			},
			{
				name: 'showAuthor',
				label: __('Show author', 'swell'),
				value: showAuthor,
			},
		];

		if ('simple' !== listType) {
			_toggleData.push({
				name: 'showThumb',
				label: __('Show thumbnail', 'swell'),
				value: showThumb,
			});
		}
		return _toggleData;
	}, [listType, showSite, showDate, showAuthor, showThumb]);

	return (
		<>
			<PanelBody title={__('RSS settings', 'swell')} initialOpen={true}>
				<TextControl
					label={__('RSS feed URL', 'swell')}
					value={rssUrl}
					onChange={(val) => {
						setAttributes({ rssUrl: val });
					}}
				/>
				<TextControl
					label={__('RSS feed page name', 'swell')}
					value={pageName}
					onChange={(val) => {
						setAttributes({ pageName: val });
					}}
				/>
				<ToggleControl
					label={__('Use the cache', 'swell')}
					help={__('If you want to clear the cache, turn it off only once.', 'swell')}
					checked={useCache}
					onChange={(val) => {
						setAttributes({ useCache: val });
					}}
				/>
			</PanelBody>
			<PanelBody title={__('Display settings', 'swell')} initialOpen={true}>
				<RangeControl
					label={__('Number of posts to display', 'swell') + '(PC)'}
					value={listCountPC}
					onChange={(val) => {
						setAttributes({ listCountPC: val });
					}}
					min={1}
					max={10}
				/>
				<RangeControl
					label={__('Number of posts to display', 'swell') + '(SP)'}
					value={listCountSP}
					onChange={(val) => {
						setAttributes({ listCountSP: val });
					}}
					min={1}
					max={10}
				/>
				<RadioControl
					label={__('List layout', 'swell')}
					selected={listType}
					options={listTypeOptions}
					onChange={(val) => {
						setAttributes({ listType: val });
					}}
				/>
				<BaseControl className='arkb-toggles'>
					<BaseControl.VisualLabel>
						{__('What to display', 'swell')}
					</BaseControl.VisualLabel>
					{toggleData.map((toggle) => {
						const label =
							'' === toggle.description ? (
								toggle.label
							) : (
								<span>
									{toggle.label}
									<br />
									<small>{toggle.description}</small>
								</span>
							);
						return (
							<ToggleControl
								label={label}
								checked={toggle.value}
								onChange={(val) => {
									setAttributes({ [toggle.name]: val });
								}}
								key={`toggle_${toggle.name}`}
							/>
						);
					})}
				</BaseControl>
				<BaseControl>
					<BaseControl.VisualLabel>
						{__('HTML tag for title', 'swell')}
					</BaseControl.VisualLabel>
					<ButtonGroup className='ark-btns--minWidth'>
						{hTags.map((btn) => {
							const isSlected = btn.val === hTag;
							return (
								<Button
									// isSecondary={ ! isSlected }
									isPrimary={isSlected}
									onClick={() => {
										setAttributes({ hTag: btn.val });
									}}
									key={`hTag_${btn.val}`}
								>
									{btn.label}
								</Button>
							);
						})}
					</ButtonGroup>
				</BaseControl>
				{'card' === listType && (
					<>
						<BaseControl>
							<BaseControl.VisualLabel>
								{__('最大カラム数', 'swell') + '（PC）'}
							</BaseControl.VisualLabel>
							<small className='button_group_help'>
								※ カード型またはサムネイル型でのみ有効
							</small>
							<ButtonGroup className='swl-btns--minWidth'>
								{pcColbuttonData.map((btn) => {
									const isSlected = btn.val === pcCol;
									return (
										<Button
											isSecondary={!isSlected}
											isPrimary={isSlected}
											onClick={() => {
												setAttributes({
													pcCol: btn.val,
												});
											}}
											key={`pcCol_${btn.val}`}
										>
											{btn.label}
										</Button>
									);
								})}
							</ButtonGroup>
						</BaseControl>
						<BaseControl>
							<small className='button_group_help'>※ カード型でのみ有効</small>
							<BaseControl.VisualLabel>
								{__('最大カラム数', 'swell') + '（SP）'}
							</BaseControl.VisualLabel>
							<ButtonGroup className='swl-btns--minWidth'>
								{spColbuttonData.map((btn) => {
									const isSlected = btn.val === spCol;
									return (
										<Button
											isSecondary={!isSlected}
											isPrimary={isSlected}
											onClick={() => {
												setAttributes({
													spCol: btn.val,
												});
											}}
											key={`spCol_${btn.val}`}
										>
											{btn.label}
										</Button>
									);
								})}
							</ButtonGroup>
						</BaseControl>
					</>
				)}
			</PanelBody>
		</>
	);
}
