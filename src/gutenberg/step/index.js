/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useEffect } from '@wordpress/element';
import { useDispatch, useSelect } from '@wordpress/data';
import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls, InnerBlocks } from '@wordpress/block-editor';

/**
 * @SWELL dependencies
 */
import { iconColor } from '@swell-guten/config';
import StepSidebar from './_sidebar';
import blockIcon from './_icon';
import example from './_example';

/**
 * @Others dependencies
 */
import classnames from 'classnames';

/**
 * STEPブロック
 */
const blockName = 'swell-block-step';
registerBlockType('loos/step', {
	apiVersion: 1,
	title: 'ステップ',
	description: __('ステップ形式で流れを説明できます。', 'swell'),
	icon: {
		foreground: iconColor,
		src: blockIcon.step,
	},
	category: 'swell-blocks',
	keywords: ['swell', 'step'],
	supports: {
		anchor: true,
		className: false, //ブロック要素を作成した際に付く .wp-block-[ブロック名]で自動生成されるクラス名の設定。
	},
	example,
	styles: [
		{ name: 'default', label: 'デフォルト', isDefault: true },
		{ name: 'big', label: 'ビッグ' },
		{ name: 'small', label: 'スモール' },
	],
	attributes: {
		startNum: {
			type: 'number',
			default: 1,
		},
		numShape: {
			type: 'string',
			default: 'circle',
		},
		numLayout: {
			type: 'string',
			default: 'vertical',
		},
		stepLabel: {
			type: 'string',
			default: 'STEP',
		},
		stepClass: {
			type: 'string',
			default: 'default',
		},
	},

	edit: (props) => {
		const { attributes, setAttributes, className, clientId } = props;
		const { stepLabel, stepClass, numShape, numLayout, startNum } = attributes;
		const blockClass = classnames(className, blockName);

		const nowClass = attributes.className || '';

		// ブロックスタイルに応じた attribute
		useEffect(() => {
			if (-1 !== nowClass.indexOf('is-style-big')) {
				setAttributes({ stepClass: 'big' });
			} else if (-1 !== nowClass.indexOf('is-style-small')) {
				setAttributes({ stepClass: 'small' });
			} else {
				setAttributes({ stepClass: 'default' });
			}
		}, [nowClass]);

		// 子ブロックを取得
		const childBlocks = useSelect(
			(select) => select('core/block-editor').getBlocks(clientId),
			[clientId]
		);

		// 子ブロックの更新
		const { updateBlockAttributes } = useDispatch('core/block-editor');
		useEffect(() => {
			childBlocks.forEach((child) => {
				updateBlockAttributes(child.clientId, {
					stepLabel,
					stepClass,
				});
			});
		}, [childBlocks, stepClass, stepLabel]);

		return (
			<>
				<InspectorControls>
					<StepSidebar {...{ attributes, setAttributes }} />
				</InspectorControls>
				<div
					className={blockClass}
					data-num-style={'big' === stepClass ? numLayout : numShape}
					style={1 < startNum ? { counterReset: `step ${startNum - 1}` } : null}
				>
					<div className='swell-block-parentSelector'>親ブロックを選択</div>
					<InnerBlocks
						templateLock={false}
						allowedBlocks={['loos/step-item']}
						template={[['loos/step-item'], ['loos/step-item']]}
						renderAppender={InnerBlocks.ButtonBlockAppender}
					/>
				</div>
			</>
		);
	},

	save: ({ attributes }) => {
		const { startNum, stepClass, numLayout, numShape } = attributes;
		return (
			<div
				className={blockName}
				data-num-style={'big' === stepClass ? numLayout : numShape}
				style={1 < startNum ? { counterReset: `step ${startNum - 1}` } : null}
			>
				<InnerBlocks.Content />
			</div>
		);
	},
});
