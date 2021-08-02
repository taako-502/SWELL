/**
 * @WordPress dependencies
 */
import { useState } from '@wordpress/element';
import { registerBlockType } from '@wordpress/blocks';
import {
	InnerBlocks,
	useBlockProps,
	__experimentalUseInnerBlocksProps as useInnerBlocksProps,
} from '@wordpress/block-editor';

/**
 * @SWELL dependencies
 */
import metadata from './block.json';
import blockIcon from './_icon';
import getBlockIcon from '@swell-guten/utils/getBlockIcon';
import SwellTab from '@swell-guten/components/SwellTab';

/**
 * @Others dependencies
 */
import classnames from 'classnames';

const ALLOWED_BLOCKS = ['loos/ab-test-a', 'loos/ab-test-b'];
const TEMPLATE = [['loos/ab-test-a'], ['loos/ab-test-b']];

/**
 * ABテストブロック
 */
const blockName = 'swell-block-abTest';
registerBlockType(metadata.name, {
	icon: getBlockIcon(blockIcon),
	edit: () => {
		// タブ用のステート
		const defaultTab = 'tab-a';
		const [tabKey, setTabKey] = useState(defaultTab);

		// ブロックprops
		const blockProps = useBlockProps({
			className: classnames(blockName, tabKey, 'swl-inner-blocks'),
		});
		const innerBlocksProps = useInnerBlocksProps(
			{},
			{
				allowedBlocks: ALLOWED_BLOCKS,
				template: TEMPLATE,
				templateLock: true,
			}
		);

		return (
			<>
				<div {...blockProps}>
					<SwellTab
						className='-ab-test'
						tabs={[
							{ key: 'tab-a', label: 'Aブロック' },
							{ key: 'tab-b', label: 'Bブロック' },
						]}
						state={tabKey}
						setState={setTabKey}
					></SwellTab>
					{innerBlocksProps.children}
				</div>
			</>
		);
	},

	save: () => {
		return <InnerBlocks.Content />;
	},
});
