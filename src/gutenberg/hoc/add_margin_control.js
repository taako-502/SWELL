/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { addFilter } from '@wordpress/hooks';
import { useSelect } from '@wordpress/data';
import { createHigherOrderComponent } from '@wordpress/compose';
import { BlockControls, InspectorControls } from '@wordpress/block-editor';

/**
 * @Self dependencies
 */
import MarginToolbtn from './components/MarginToolbtn';
import MarginPanel from './components/MarginPanel';
import { swellStore } from '@swell-guten/config';

/**
 * マージンコントロール
 */
const addMarginControls = createHigherOrderComponent((BlockEdit) => {
	return (props) => {
		const { name, attributes, setAttributes } = props;

		// 除去するブロック
		const isRemoval =
			'loos/ab-test' === name ||
			'core/shortcode' === name ||
			'core/html' === name ||
			'core/block' === name ||
			'core/freeform' === name;
		if (!props.isSelected || isRemoval) {
			return <BlockEdit {...props} />;
		}

		const swellBlockSettings = useSelect((select) => {
			return select(swellStore).getSettings();
		}, []);
		const showMarginToolbtn = swellBlockSettings.show_margin_toolbtn;

		return (
			<>
				<BlockEdit {...props} />
				{showMarginToolbtn && (
					<BlockControls>
						<MarginToolbtn
							className={attributes.className}
							setAttributes={setAttributes}
						/>
					</BlockControls>
				)}

				<InspectorControls>
					<MarginPanel className={attributes.className} setAttributes={setAttributes} />
				</InspectorControls>
			</>
		);
	};
}, 'addMarginControls');
addFilter('editor.BlockEdit', 'swell-hook/add-margin-control', addMarginControls, 99);
