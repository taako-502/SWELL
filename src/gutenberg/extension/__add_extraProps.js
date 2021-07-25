/**
 * 使ってない
 */
const addFilter = wp.hooks.addFilter;
import classnames from 'classnames';

const saveFilterFunction = (props, blockType, attributes) => {
	const { className } = props;
	// groupブロックでの処理
	if ('core/group' === blockType.name) {
		if (attributes.align) {
			props.className = classnames(className, `has-text-align-${attributes.align}`);
		}
	}

	return props;
};
addFilter('blocks.getSaveContent.extraProps', 'loos-save-filter/group', saveFilterFunction);
