/**
 * deprecated
 */
export default [
	{
		attributes: {
			adID: {
				type: 'string',
				default: '',
			},
		},
		save: ({ attributes }) => {
			return <div>{'[ad_tag id="' + attributes.adID + '"]'}</div>;
		},
	},
];
