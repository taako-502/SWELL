/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { useEffect } from '@wordpress/element';
import { useDispatch, useSelect } from '@wordpress/data';
import { registerBlockType } from '@wordpress/blocks';
import { InnerBlocks, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, RangeControl } from '@wordpress/components';

/**
 * @SWELL dependencies
 */
import example from './_example';
import blockIcon from './_icon';
import { iconColor } from '@swell-guten/config';

/**
 * @Others dependencies
 */
import classnames from 'classnames';

/**
 * registerBlockType
 */
const blockName = 'swell-block-dl';
registerBlockType('loos/dl', {
	title: '説明リスト(DL)',
	description: '説明リストを簡単に使用できます。',
	icon: {
		foreground: iconColor,
		src: blockIcon.dl,
	},
	category: 'swell-blocks',
	keywords: ['swell', 'dl'],
	supports: {
		anchor: true,
		className: false,
	},
	example,
	styles: [
		{ name: 'default', label: 'デフォルト', isDefault: true },
		{ name: 'border', label: '左に線' },
		{ name: 'float', label: '横並び' },
		{ name: 'vtabel', label: '縦並び表' },
	],
	attributes: {
		dtWidth: {
			type: 'number',
		},
		styleName: {
			type: 'string',
			selector: '',
		},
	},

	edit: ({ className, attributes, setAttributes, clientId }) => {
		const { dtWidth } = attributes;
		const blockClass = classnames(className, blockName);

		const nowClass = attributes.className || '';
		const isFloat = -1 !== nowClass.indexOf('is-style-float');
		// console.log(isFloat, nowClass);

		// 横並びスタイル以外の時はdtWidthの指定を消す
		useEffect(() => {
			if (!isFloat) {
				setAttributes({ dtWidth: undefined });
			}
		}, [isFloat]);

		// 子ブロックを取得
		const childBlocks = useSelect(
			(select) => select('core/block-editor').getBlocks(clientId),
			[clientId]
		);

		// 子ブロックにdtWidthを伝達
		const { updateBlockAttributes } = useDispatch('core/block-editor');
		useEffect(() => {
			childBlocks.forEach((child) => {
				updateBlockAttributes(child.clientId, {
					dtWidth,
				});
			});
		}, [dtWidth, childBlocks]);

		return (
			<>
				{isFloat && (
					<InspectorControls>
						<PanelBody title='DTの横幅'>
							<RangeControl
								label='※ em単位'
								value={dtWidth}
								onChange={(val) => {
									setAttributes({ dtWidth: val });
								}}
								min={1}
								max={12}
								step={1}
							/>
						</PanelBody>
					</InspectorControls>
				)}
				<div className={blockClass}>
					<div className='swell-block-parentSelector'>親ブロックを選択</div>
					<InnerBlocks
						allowedBlocks={['loos/dt', 'loos/dd']}
						templateLock={false}
						template={[['loos/dt'], ['loos/dd'], ['loos/dt'], ['loos/dd']]}
						renderAppender={InnerBlocks.ButtonBlockAppender}
					/>
				</div>
			</>
		);
	},

	save: () => {
		return (
			<dl className={blockName}>
				<InnerBlocks.Content />
			</dl>
		);
	},
});
