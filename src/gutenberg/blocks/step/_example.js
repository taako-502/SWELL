const exampleItem = {
    name: 'loos/step-item',
    attributes: {
        title: 'Step Title.',
        stepLabel: 'STEP',
        stepClass: '',
        isShapeFill: false,
        isPreview: true,
    },
    innerBlocks: [
        {
            name: 'core/paragraph',
            attributes: {
                content:
                    'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.',
            },
        },
    ],
};
export default {
    innerBlocks: [exampleItem, exampleItem, exampleItem],
};
