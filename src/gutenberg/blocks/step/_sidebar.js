/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { PanelBody, Button, ButtonGroup, BaseControl, TextControl } from '@wordpress/components';

export default function ({ attributes, setAttributes }) {
	const { stepLabel, startNum, stepClass, numLayout, numShape } = attributes;

	return (
		<PanelBody title='ステップ設定'>
			<TextControl
				label='「STEP」の文字'
				value={stepLabel}
				onChange={(val) => {
					setAttributes({ stepLabel: val });
				}}
			/>

			<TextControl
				label='始まりの番号'
				value={startNum}
				type='number'
				onChange={(val) => {
					// typeがnumberなので、intに変換してから保存！
					setAttributes({ startNum: parseInt(val) });
				}}
			/>
			{'big' === stepClass ? (
				<BaseControl>
					<BaseControl.VisualLabel>
						{__('ステップ番号の並び', 'swell')}
					</BaseControl.VisualLabel>
					<ButtonGroup className='swl-btns--padSmall'>
						<Button
							isLarge
							isPrimary={'vertical' === numLayout}
							onClick={() => {
								setAttributes({ numLayout: 'vertical' });
							}}
						>
							縦並び
						</Button>
						<Button
							isLarge
							isPrimary={'horizontal' === numLayout}
							onClick={() => {
								setAttributes({ numLayout: 'horizontal' });
							}}
						>
							横並び
						</Button>
					</ButtonGroup>
				</BaseControl>
			) : (
				<BaseControl>
					<BaseControl.VisualLabel>
						{__('ステップ番号の形', 'swell')}
					</BaseControl.VisualLabel>
					<ButtonGroup className='swl-btns--padSmall'>
						<Button
							isLarge
							isPrimary={'circle' === numShape}
							onClick={() => {
								setAttributes({ numShape: 'circle' });
							}}
						>
							円形
						</Button>
						<Button
							isLarge
							isPrimary={'square' === numShape}
							onClick={() => {
								setAttributes({ numShape: 'square' });
							}}
						>
							四角形
						</Button>
					</ButtonGroup>
				</BaseControl>
			)}
		</PanelBody>
	);
}
