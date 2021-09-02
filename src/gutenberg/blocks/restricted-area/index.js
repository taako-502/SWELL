/**
 * @WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import {
	InnerBlocks,
	InspectorControls,
	useBlockProps,
	__experimentalUseInnerBlocksProps as useInnerBlocksProps,
} from '@wordpress/block-editor';
import { Icon } from '@wordpress/components';

/**
 * @SWELL dependencies
 */
import RestrictedAreaSidebar from './_sidebar';
import metadata from './block.json';
import blockIcon from './_icon';
import getBlockIcon from '@swell-guten/utils/getBlockIcon';

const TEMPLATE = [['core/paragraph']];

/**
 * 制限エリア
 */
const blockName = 'swell-block-restrictedArea';
registerBlockType(metadata.name, {
	icon: getBlockIcon(blockIcon),
	edit: ({ attributes, setAttributes }) => {
		const blockProps = useBlockProps({
			className: `${blockName} swl-inner-blocks`,
		});
		const innerBlocksProps = useInnerBlocksProps(
			{},
			{
				template: TEMPLATE,
				templateLock: false,
			}
		);

		return (
			<>
				<InspectorControls>
					<RestrictedAreaSidebar {...{ attributes, setAttributes }} />
				</InspectorControls>
				<div {...blockProps}>
					<div className='swell-block-parentSelector'>
						<Icon icon={blockIcon} />
					</div>
					{innerBlocksProps.children}
				</div>
			</>
		);
	},
	save: () => {
		return <InnerBlocks.Content />;
	},
});
