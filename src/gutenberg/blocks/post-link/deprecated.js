/**
 * deprecated
 */
export default [
	{
		attributes: {
			postTitle: {
				type: 'string',
				default: '',
			},
			postId: {
				type: 'string',
				default: '',
			},
			cardCaption: {
				type: 'string',
				default: '',
			},
			isNewTab: {
				type: 'boolean',
				default: false,
			},
			externalUrl: {
				type: 'string',
				default: '',
			},
			isPreview: {
				type: 'boolean',
				default: false,
			},
			rel: {
				type: 'string',
				default: '',
			},
		},
		// supports,
		save: ({ attributes }) => {
			const { postId, cardCaption, isNewTab, externalUrl } = attributes;

			let shortcode = 'post_link';
			if (postId) {
				shortcode += ' id="' + postId + '"';
			}
			if (cardCaption) {
				shortcode += ' cap="' + cardCaption + '"';
			}
			if (isNewTab) {
				shortcode += ' target="_blank"';
			}
			if (externalUrl) {
				shortcode += ' url="' + externalUrl + '"';
			}

			return <div>{'[' + shortcode + ']'}</div>;
		},
	},
];
