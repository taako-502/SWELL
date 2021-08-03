/**
 * @WordPress dependencies
 */
import { RichText } from '@wordpress/block-editor';

/**
 * @SWELL dependencies
 */
import metadata from './block.json';

/**
 * deprecated
 */
export default [
	{
		attributes: metadata.blockAttributes,
		supports: { className: false },
		save: ({ attributes }) => {
			const {
				balloonTitle,
				balloonIcon,
				balloonName,
				balloonCol,
				balloonType,
				balloonAlign,
				balloonBorder,
				balloonShape,
				spVertical,
				content,
			} = attributes;

			let props = '';
			if (balloonTitle) props += ' set="' + balloonTitle + '"';
			if (balloonIcon) props += ' icon="' + balloonIcon + '"';
			if (balloonAlign) props += ' align="' + balloonAlign + '"';
			if (balloonName) props += ' name="' + balloonName + '"';
			if (balloonCol) props += ' col="' + balloonCol + '"';
			if (balloonType) props += ' type="' + balloonType + '"';
			if (balloonBorder) props += ' border="' + balloonBorder + '"';
			if (balloonShape) props += ' icon_shape="' + balloonShape + '"';

			if ('' !== spVertical) props += ' sp_vertical="1"';

			return (
				<div className='swell-block-balloon'>
					{'[ふきだし' + props + ']'}
					<RichText.Content tagName='p' value={content} />
					{'[/ふきだし]'}
				</div>
			);
		},
	},
	{
		attributes: metadata.blockAttributes,
		save: ({ attributes }) => {
			const {
				balloonTitle,
				balloonIcon,
				balloonName,
				balloonCol,
				balloonType,
				balloonAlign,
				balloonBorder,
				balloonShape,
				spVertical,
				content,
			} = attributes;

			let props = '';
			if (balloonTitle) props += ' set="' + balloonTitle + '"';
			if (balloonIcon) props += ' icon="' + balloonIcon + '"';
			if (balloonAlign) props += ' align="' + balloonAlign + '"';
			if (balloonName) props += ' name="' + balloonName + '"';
			if (balloonCol) props += ' col="' + balloonCol + '"';
			if (balloonType) props += ' type="' + balloonType + '"';
			if (balloonBorder) props += ' border="' + balloonBorder + '"';
			if (balloonShape) props += ' icon_shape="' + balloonShape + '"';

			if ('' !== spVertical) props += ' sp_vertical="1"';

			return (
				<div>
					{'[ふきだし' + props + ']'}
					<RichText.Content tagName='p' value={content} />
					{'[/ふきだし]'}
				</div>
			);
		},
	},
];
