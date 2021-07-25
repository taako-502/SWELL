/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { memo } from '@wordpress/element';
import { PanelBody } from '@wordpress/components';
import { InspectorControls } from '@wordpress/block-editor';

/**
 * @SWELL dependencies
 */
import BoxStyleControls from '@swell-guten/components/BoxStyleControls';
import BorderPanel from '@swell-guten/components/BorderPanel';

/**
 * @Other dependencies
 */
// import classnames from 'classnames';

/**
 * 設定項目
 */
const styleObj = {
	bg_stripe: 'ストライプ',
	bg_grid: '方眼',
	crease: '角に折り目',
	stitch: 'スティッチ',
	kakko_box: 'かぎ括弧',
	big_kakko_box: 'かぎ括弧（大）',
	dent_box: '窪み',
	emboss_box: '浮き出し',
	big_icon_point: 'ポイント',
	big_icon_good: 'チェック',
	big_icon_bad: 'バツ印',
	big_icon_hatena: 'はてな',
	big_icon_caution: 'アラート',
	big_icon_memo: 'メモ',
	browser_mac: 'ブラウザ風',
	desktop: 'デスクトップ風',
};

/**
 * グループブロック用HOC
 */
export default memo(({ attributes, setAttributes }) => {
	return (
		<InspectorControls>
			<BorderPanel className={attributes.className} setAttributes={setAttributes} />
			<PanelBody title='スタイル' className='swl-panel' initialOpen={true}>
				<BoxStyleControls
					attributes={attributes}
					setAttributes={setAttributes}
					styles={styleObj}
				></BoxStyleControls>
			</PanelBody>
		</InspectorControls>
	);
});
