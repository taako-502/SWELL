/**
 * @WordPress dependencies
 */
import { RichText } from '@wordpress/block-editor';

const oldAttributes = {
	className: {
		type: 'string',
		default: '',
	},
	content: {
		type: 'string',
		source: 'html',
		selector: 'p',
		default: 'ここにテキストを入力',
	},
	balloonID: {
		type: 'string',
		default: '0',
	},
	balloonTitle: {
		type: 'string',
		default: '',
	},
	balloonIcon: {
		type: 'string',
		default: '',
	},
	balloonName: {
		type: 'string',
		default: '',
	},
	balloonCol: {
		type: 'string',
		default: '',
	},
	balloonType: {
		type: 'string',
		default: '',
	},
	balloonAlign: {
		type: 'string',
		default: '',
	},
	balloonBorder: {
		type: 'string',
		default: '',
	},
	balloonShape: {
		type: 'string',
		default: '',
	},
	spVertical: {
		type: 'string',
		default: '',
	},
};

/**
 * deprecated
 */
export default [
	{
		// api v1時代
		attributes: oldAttributes,
		supports: { className: false },
		save: ({ attributes }) => {
			// 単純に p タグとして内容は保存しておく
			return <RichText.Content tagName='p' value={attributes.content} />;
		},
	},
	{
		attributes: oldAttributes,
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
		attributes: oldAttributes,
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
