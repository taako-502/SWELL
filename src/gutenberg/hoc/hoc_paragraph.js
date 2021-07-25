/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { PanelBody } from '@wordpress/components';
import { InspectorControls } from '@wordpress/block-editor';
import { memo } from '@wordpress/element';

/**
 * @SWELL dependencies
 */
import BoxStyleControls from '@swell-guten/components/BoxStyleControls';
import BorderPanel from '@swell-guten/components/BorderPanel';

const styleObj = {
	bg_stripe: 'ストライプ',
	bg_grid: '方眼',
	crease: '角に折り目',
	stitch: 'スティッチ',
	kakko_box: 'かぎ括弧',
	big_kakko_box: 'かぎ括弧（大）',
	dent_box: '窪み',
	emboss_box: '浮き出し',
	border_left: '左に縦線',
	sticky_box: '付箋',
	balloon_box: '吹き出し',
	balloon_box2: '吹き出し２',
	icon_good: 'グッド',
	icon_bad: 'バッド',
	icon_info: 'インフォ',
	icon_announce: 'アナウンス',
	icon_pen: 'ペン',
	icon_book: '本',
	big_icon_point: 'ポイント',
	big_icon_check: 'チェック',
	big_icon_batsu: 'バツ印',
	big_icon_hatena: 'はてな',
	big_icon_caution: 'アラート',
	big_icon_memo: 'メモ',
};

/**
 * 段落ブロック用 HOC
 */
export default memo(({ attributes, setAttributes }) => {
	// console.log('hoc paragraph');

	return (
		<InspectorControls>
			<BorderPanel className={attributes.className} setAttributes={setAttributes} />
			<PanelBody title='スタイル' initialOpen={true} className='swl-panel'>
				<BoxStyleControls
					attributes={attributes}
					setAttributes={setAttributes}
					styles={styleObj}
				></BoxStyleControls>
			</PanelBody>
		</InspectorControls>
	);
});
