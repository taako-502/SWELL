/**
 * @WordPress dependencies
 */
import { createBlock } from '@wordpress/blocks';

/**
 * deprecated
 */
export default {
	from: [
		//どのブロックタイプから変更できるようにするか
		{
			type: 'block',
			blocks: ['core/paragraph'],
			transform: (attributes) => {
				return createBlock('loos/balloon', {
					content: attributes.content,
				});
			},
		},
	],
	to: [
		//どのブロックタイプへ変更できるようにするか
		{
			type: 'block',
			blocks: ['core/paragraph'],
			transform: (attributes) => {
				return createBlock('core/paragraph', {
					content: attributes.content,
				});
			},
		},
	],
};
