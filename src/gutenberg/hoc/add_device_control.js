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
import DeviceToolbtn from './components/DeviceToolbtn';
import DevicePanel from './components/DevicePanel';
import { swellStore } from '@swell-guten/config';

const setBlocks = [
	'core/paragraph',
	'core/group',
	'core/image',
	'core/list',
	'core/gallery',
	'core/video',
	'core/media-text',
	'core/cover',
	'core/columns',
	'core/table',

	'loos/step',
	'loos/faq',
	'loos/button',
	'loos/full-wide',
	'loos/accordion',
	'loos/tab',
	'loos/ad-tag',
	'loos/balloon',
	'loos/banner-link',
	'loos/cap-block',
	'loos/dl',
	'loos/post-link',
	'loos/blog-parts',
	'loos/post-list',
	'loos/ab-test',
];

/**
 * デバイスコントロール
 */
const addDeviceControls = createHigherOrderComponent((BlockEdit) => {
	return (props) => {
		const { name, attributes, setAttributes } = props;

		// const isSwellBlock = -1 !== name.indexOf('loos/');
		const isNotSet = -1 === setBlocks.indexOf(name);

		if (!props.isSelected || isNotSet) {
			return <BlockEdit {...props} />;
		}

		const swellBlockSettings = useSelect((select) => {
			return select(swellStore).getSettings();
		}, []);
		const showDeviceToolbtn = swellBlockSettings.show_device_toolbtn;

		return (
			<>
				<BlockEdit {...props} />
				{showDeviceToolbtn && (
					<BlockControls>
						<DeviceToolbtn
							className={attributes.className}
							setAttributes={setAttributes}
						/>
					</BlockControls>
				)}
				<InspectorControls>
					<DevicePanel className={attributes.className} setAttributes={setAttributes} />
				</InspectorControls>
			</>
		);
	};
}, 'addDeviceControls');
addFilter('editor.BlockEdit', 'swell-hook/add-device-control', addDeviceControls, 99);
