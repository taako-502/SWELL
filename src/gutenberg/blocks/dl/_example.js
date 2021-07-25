const dt = {
	name: 'loos/dt',
	attributes: {
		content: 'Item title',
	},
};
const dd = {
	name: 'loos/dd',
	attributes: {
		// content: 'Accordion Title.',
	},
	innerBlocks: [
		{
			name: 'core/paragraph',
			attributes: {
				content:
					'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do incididunt ut labore et dolore magna aliqua.',
			},
		},
	],
};
export default {
	innerBlocks: [dt, dd, dt, dd],
};
