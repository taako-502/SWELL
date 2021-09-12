/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';

import { useEffect } from '@wordpress/element';

/**
 * @SWELL dependencies
 */
import HocParagraph from './hoc_paragraph';
import HocGroup from './hoc_group';
import HocImage from './hoc_image';
import HocGallery from './hoc_gallery';
import HocTable from './hoc_table';
import HocColumns from './hoc_columns';
import HocList from './hoc_list';

// OLリストを取得する関数
const getOlList = (clientId) => {
	let ol = document.querySelector('ol[data-block="' + clientId + '"]'); // wp5.5 ~
	if (null === ol) {
		ol = document.querySelector('[data-block="' + clientId + '"] > ol'); // ~ wp5.4
	}
	return ol;
};

const setOlList = (clientId, attributes) => {
	setTimeout(() => {
		const ol = getOlList(clientId);

		if (null !== ol) {
			const startVal = attributes.start;
			const isReversed = attributes.reversed;

			if (true === isReversed) {
				ol.style.counterReset = `li ${parseInt(startVal) + 1}`;
			} else {
				ol.style.counterReset = `li ${parseInt(startVal) - 1}`;
			}
		}
	}, 5);
};

/**
 * HOC : コアブロックの機能拡張 (全ブロックで発火する)
 */
const addSwellHoc = createHigherOrderComponent((BlockEdit) => {
	return (props) => {
		const { clientId, name, isSelected, attributes, setAttributes } = props;

		/**
		 * ページロード時に一度だけ発火したい処理
		 */
		useEffect(() => {
			// console.log('useEffect');

			// olのstart属性に対応する : 初回表示時にも対応できるように、 isSelected のバリデーションを通す前に一度実行
			if ('core/list' === name) {
				// startがセットされている時のみ処理する
				if ('undefined' !== typeof attributes.start) {
					// console.log('ol set');
					setOlList(clientId, attributes);
				}
			}
		}, [clientId]);

		// ここから先は全部 isSelected の時のみ追加
		if (!isSelected) {
			return <BlockEdit {...props} />;
		}

		let SwellHoc = null;

		switch (name) {
			case 'core/paragraph':
				SwellHoc = HocParagraph;
				break;
			case 'core/group':
				SwellHoc = HocGroup;
				break;
			case 'core/image':
				if (attributes.url) SwellHoc = HocImage;
				break;
			case 'core/gallery':
				if (attributes.ids) SwellHoc = HocGallery;
				break;

			case 'core/table':
				SwellHoc = HocTable;
				break;
			case 'core/columns':
				SwellHoc = HocColumns;
				break;
			case 'core/list':
				if ('undefined' !== typeof attributes.start) {
					// startがセットされている時のみ処理する
					setOlList(clientId, attributes);
				} else if ('undefined' === typeof attributes.start) {
					// あとからstartを消した時用の処理
					setTimeout(() => {
						const ol = getOlList(clientId);
						if (null !== ol) {
							ol.style.counterReset = `li`;
						}
					}, 5);
				}
				SwellHoc = HocList;
				break;
			default:
				return <BlockEdit {...props} />;
		}

		if (null !== SwellHoc) {
			return (
				<>
					<BlockEdit {...props} />
					<SwellHoc {...{ attributes, setAttributes, clientId }} />
				</>
			);
		}

		return <BlockEdit {...props} />;
	};
}, 'addSwellHoc');
addFilter('editor.BlockEdit', 'swell-theme/hoc', addSwellHoc);

const exTable = (settings) => {
	if ('core/table' !== settings.name) {
		return settings;
	}

	const newSettings = {
		...settings,
		attributes: {
			...settings.attributes,
			swlMaxWidth: {
				type: 'number',
				default: 800,
			},
			swlScrollable: {
				type: 'string',
				default: '',
			},
			swlFixedHead: {
				type: 'string',
				default: '',
			},
			swlIsFixedLeft: {
				type: 'boolean',
				default: false,
			},
		},
	};
	return newSettings;
};
addFilter('blocks.registerBlockType', 'swell-theme/ex-table', exTable);
