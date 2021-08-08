/**
 * deprecated
 */
export default [
	{
		attributes: {
			partsTitle: {
				type: 'string',
				default: '',
			},
			partsID: {
				type: 'string',
				default: '',
			},
		},
		save: ({ attributes }) => {
			return <div>{'[blog_parts id="' + attributes.partsID + '"]'}</div>;
		},
	},
];
