/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useMemo } from '@wordpress/element';
import {
	PanelBody,
	TextControl,
	BaseControl,
	CheckboxControl,
	RadioControl,
	ButtonGroup,
	Button,
	ToggleControl,
	RangeControl,
} from '@wordpress/components';

/**
 * 設定
 */
// 並び順
const orderOptions = [
	{
		label: '新着順',
		value: 'date',
	},
	{
		label: '更新日順',
		value: 'modified',
	},
	{
		label: '人気順',
		value: 'pv',
	},
	{
		label: 'ランダム',
		value: 'rand',
	},
];

const orderBtns = [
	{
		label: '降順',
		val: 'DESC',
	},
	{
		label: '昇順',
		val: 'ASC',
	},
];

// リストタイプ
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
		label: __('List type', 'swell') + __('(Alternate left and right)', 'swell'),
		value: 'list2',
	},
	{
		label: __('Thumbnail type', 'swell'),
		value: 'thumb',
	},
	{
		label: __('Text type', 'swell'),
		value: 'simple',
	},
];

// カテゴリー位置
const catPosOptions = [
	{
		label: '表示しない',
		value: 'none',
	},
	{
		label: 'サムネイル画像の上',
		value: 'on_thumb',
	},
	{
		label: '日付の横',
		value: 'beside_date',
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

const excerptLengthButtons = [
	{
		label: '0',
		val: 0,
	},
	{
		label: '40',
		val: 40,
	},
	{
		label: '80',
		val: 80,
	},
	{
		label: '120',
		val: 120,
	},
	{
		label: '160',
		val: 160,
	},
];

/**
 * 投稿リストコントロール
 */
export default function ({ attributes, setAttributes }) {
	const {
		listType,
		listCount,
		pcCol,
		spCol,
		showDate,
		showModified,
		showAuthor,
		showPV,
		showTitle,
		addSticky,
		order,
		orderby,
		catPos,
		hTag,
		moreText,
		moreUrl,
		pcExcerptLength,
		spExcerptLength,
		pcHideLast,
		spHideLast,
	} = attributes;

	// トグルコントロール
	const toggleData = useMemo(
		() => [
			{
				name: 'showDate',
				label: '公開日を表示する',
				description: '',
				attrVal: showDate,
			},
			{
				name: 'showModified',
				label: '更新日を表示する',
				description: '',
				attrVal: showModified,
			},
			{
				name: 'showAuthor',
				label: '著者を表示する',
				attrVal: showAuthor,
			},
			{
				name: 'showPV',
				label: 'PV数を表示する',
				attrVal: showPV,
			},
			{
				name: 'showTitle',
				label: 'タイトルを表示する',
				description: '※ サムネイル型でのみ有効です',
				attrVal: showTitle,
			},
		],
		[showDate, showModified, showAuthor, showPV, showTitle]
	);

	// パネル生成
	return (
		<PanelBody title='表示設定' initialOpen={true} className='post_list_controles'>
			<RangeControl
				className='u-mb-10'
				label='表示する投稿数'
				value={listCount}
				onChange={(val) => {
					setAttributes({ listCount: val });
				}}
				min={1}
				max={24}
			/>
			<ToggleControl
				label={'先頭固定記事を追加する'}
				// help='「表示する投稿数」に'
				checked={addSticky}
				onChange={(val) => {
					setAttributes({ addSticky: val });
				}}
			/>
			<RadioControl
				label='レイアウトを選択'
				// help=''
				selected={listType}
				options={listTypeOptions}
				onChange={(val) => {
					setAttributes({ listType: val });
				}}
			/>
			<RadioControl
				className='u-mb-10'
				label='投稿の表示順序'
				// help=''
				selected={orderby}
				options={orderOptions}
				onChange={(val) => {
					setAttributes({ orderby: val });
				}}
			/>
			<div className='components-base-control' data-swl-disable={'rand' === orderby || null}>
				<ButtonGroup className='swl-btns--minWidth'>
					{orderBtns.map((btn) => {
						return (
							<Button
								isPrimary={btn.val === order}
								onClick={() => {
									setAttributes({ order: btn.val });
								}}
								key={`order_${order.val}`}
							>
								{btn.label}
							</Button>
						);
					})}
				</ButtonGroup>
			</div>
			<BaseControl className='toggle_group'>
				<BaseControl.VisualLabel>{__('各種表示設定', 'swell')}</BaseControl.VisualLabel>
				{toggleData.map((toggle) => {
					const isChecked = '1' === toggle.attrVal;
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
							// help={(<small>{toggle.description}</small>)}
							checked={isChecked}
							onChange={(val) => {
								if (val) {
									setAttributes({ [toggle.name]: '1' });
								} else {
									setAttributes({ [toggle.name]: '' });
								}
							}}
							key={`toggle_${toggle.name}`}
						/>
					);
				})}
			</BaseControl>
			<RadioControl
				label={__('Category display position', 'swell')}
				selected={catPos}
				options={catPosOptions}
				onChange={(val) => {
					setAttributes({ catPos: val });
				}}
			/>
			<BaseControl>
				<BaseControl.VisualLabel>
					{__('HTML tag for title', 'swell')}
				</BaseControl.VisualLabel>
				<ButtonGroup className='swl-btns--minWidth'>
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
			<BaseControl>
				<BaseControl.VisualLabel>
					{__('最大カラム数（PC）', 'swell')}
				</BaseControl.VisualLabel>
				<small className='button_group_help'>※ カード型またはサムネイル型でのみ有効</small>
				<ButtonGroup className='swl-btns--minWidth'>
					{pcColbuttonData.map((btn) => {
						const isSlected = btn.val === pcCol;
						return (
							<Button
								isSecondary={!isSlected}
								isPrimary={isSlected}
								onClick={() => {
									setAttributes({ pcCol: btn.val });
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
				<small className='button_group_help'>※ カード型またはサムネイル型でのみ有効</small>
				<BaseControl.VisualLabel>
					{__('最大カラム数（SP）', 'swell')}
				</BaseControl.VisualLabel>
				<ButtonGroup className='swl-btns--minWidth'>
					{spColbuttonData.map((btn) => {
						const isSlected = btn.val === spCol;
						return (
							<Button
								isSecondary={!isSlected}
								isPrimary={isSlected}
								onClick={() => {
									setAttributes({ spCol: btn.val });
								}}
								key={`spCol_${btn.val}`}
							>
								{btn.label}
							</Button>
						);
					})}
				</ButtonGroup>
			</BaseControl>
			<BaseControl>
				<BaseControl.VisualLabel>
					{__('Number of characters in the excerpt', 'swell') + '(PC)'}
				</BaseControl.VisualLabel>
				<ButtonGroup className='swl-btns--minWidth'>
					{excerptLengthButtons.map((btn) => {
						const isSlected = btn.val === pcExcerptLength;
						return (
							<Button
								isSecondary={!isSlected}
								isPrimary={isSlected}
								onClick={() => {
									setAttributes({
										pcExcerptLength: btn.val,
									});
								}}
								key={`pcExcerptLength_${btn.val}`}
							>
								{btn.label}
							</Button>
						);
					})}
				</ButtonGroup>
			</BaseControl>
			<BaseControl>
				<BaseControl.VisualLabel>
					{__('Number of characters in the excerpt', 'swell') + '(SP)'}
				</BaseControl.VisualLabel>
				<ButtonGroup className='swl-btns--minWidth'>
					{excerptLengthButtons.map((btn) => {
						const isSlected = btn.val === spExcerptLength;
						return (
							<Button
								isSecondary={!isSlected}
								isPrimary={isSlected}
								onClick={() => {
									setAttributes({
										spExcerptLength: btn.val,
									});
								}}
								key={`spExcerptLength_${btn.val}`}
							>
								{btn.label}
							</Button>
						);
					})}
				</ButtonGroup>
			</BaseControl>
			<BaseControl>
				<BaseControl.VisualLabel>
					{__('MOREリンクの表示テキスト', 'swell')}
				</BaseControl.VisualLabel>
				<TextControl
					value={moreText}
					placeholder='もっと見る'
					onChange={(val) => setAttributes({ moreText: val })}
					// help='空の場合、ボタンは表示されません。'
				/>
			</BaseControl>
			<BaseControl
				help={
					<small>
						※
						カテゴリーを指定した場合などは自動でURLを取得しますので、入力する必要はありません。
					</small>
				}
			>
				<BaseControl.VisualLabel>{__('MOREリンクのURL', 'swell')}</BaseControl.VisualLabel>
				<TextControl
					value={moreUrl}
					// placeholder='MORE'
					onChange={(val) => setAttributes({ moreUrl: val })}
				/>
			</BaseControl>
			<BaseControl className='checkbox_group'>
				<BaseControl.VisualLabel>
					{__('最後の投稿を非表示にするかどうか', 'swell')}
				</BaseControl.VisualLabel>
				<CheckboxControl
					label='PC表示で非表示にする'
					checked={pcHideLast}
					onChange={(val) => setAttributes({ pcHideLast: val })}
				/>
				<CheckboxControl
					label='SP表示で非表示にする'
					checked={spHideLast}
					onChange={(val) => setAttributes({ spHideLast: val })}
				/>
			</BaseControl>
		</PanelBody>
	);
}
