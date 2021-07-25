const addFilter = wp.hooks.addFilter;

const borderAttributes = {
	borderStyle: {
		type: 'string',
		default: '',
	},
	borderColor: {
		type: 'string',
		default: '',
	},
	borderWidth: {
		type: 'number',
		default: 1,
	},
};

/**
 * 段落ブロックに独自の attributes 追加
 */
const addAttributesBorder = (settings, name) => {
	if ('core/paragraph' !== name) {
		return settings;
	}
	settings.attributes = {
		...settings.attributes,
		...borderAttributes,
	};
	const coreGetEditWrapperProps = settings.getEditWrapperProps;
	settings.getEditWrapperProps = (attributes) => {
		let dataObj = coreGetEditWrapperProps(attributes);
		const borderWidth = attributes.borderWidth;
		if (borderWidth) {
			dataObj = {
				...dataObj,
				...{
					'data-border-width': borderWidth,
				},
			};
		}
		return dataObj;
	};
	return settings;
};
addFilter('blocks.registerBlockType', 'swell-theme/addAttributes/border', addAttributesBorder);

/**
 * style属性を付与するフック
 */
function addBackgroundColorStyle(props, blockType, attributes) {
	if ('core/paragraph' === blockType.name) {
		const borderWidth = attributes.borderWidth;
		if (borderWidth && 1 < borderWidth) {
			const borderStyle = {
				borderWidth: borderWidth + 'px',
			};
			props.style = {
				...props.style,
				...borderStyle,
			};
		}
	}

	return props;
}

addFilter(
	'blocks.getSaveContent.extraProps',
	'my-plugin/add-background-color-style',
	addBackgroundColorStyle
);
