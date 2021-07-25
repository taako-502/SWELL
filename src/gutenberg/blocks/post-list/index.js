/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useSelect } from '@wordpress/data';
import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { TabPanel } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';

/**
 * @SWELL dependencies
 */
import metadata from './block.json';
import DisplayControl from './panel/_display';
import PickupControl from './panel/_pickup';
import getBlockIcon from '@swell-guten/utils/getBlockIcon';

/**
 * registerBlockType
 */
registerBlockType(metadata.name, {
	title: __('投稿リスト', 'swell'),
	description: __('投稿リストを好きな条件で呼び出すことができます。', 'swell'),
	icon: getBlockIcon('screenoptions'),
	edit: ({ attributes, setAttributes }) => {
		const authors = useSelect((select) => select('core').getAuthors());

		// ブロックProps
		const blockProps = useBlockProps({
			className: 'loos-block-post-list post_content',
		});

		return (
			<>
				<InspectorControls>
					<TabPanel
						className='swell-tab-panel -postList'
						activeClass='is-active'
						tabs={[
							{
								name: 'display',
								title: (
									<>
										<i className='dashicons-before dashicons-admin-settings'></i>
										Settings
									</>
								),
								className: '__display',
							},
							{
								name: 'pickup',
								title: (
									<>
										<i className='dashicons-before dashicons-post-status'></i>
										Pickup
									</>
								),
								className: '__pickup',
							},
						]}
						initialTabName='display'
					>
						{(tab) => {
							if ('pickup' === tab.name) {
								return (
									<PickupControl {...{ attributes, setAttributes, authors }} />
								);
							} else if ('display' === tab.name) {
								return <DisplayControl {...{ attributes, setAttributes }} />;
							}
						}}
					</TabPanel>
				</InspectorControls>
				<div {...blockProps}>
					<ServerSideRender block='loos/post-list' attributes={attributes} />
				</div>
			</>
		);
	},
	save: () => {
		return null;
	},
});
