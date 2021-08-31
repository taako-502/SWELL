/**
 * WordPress dependencies
 */
import {
	Popover,
	SlotFillProvider,
	DropZoneProvider,
	FocusReturnProvider,
} from '@wordpress/components';

import { InterfaceSkeleton, FullscreenMode } from '@wordpress/interface';

/**
 * Internal dependencies
 */
import Notices from './components/notices';
import Sidebar from './components/sidebar';
import BlockEditor from './components/block-editor';

export default function () {
	return (
		<>
			<FullscreenMode isActive={false} />
			<SlotFillProvider>
				<DropZoneProvider>
					<FocusReturnProvider>
						<InterfaceSkeleton
							sidebar={<Sidebar />}
							content={
								<>
									<Notices />
									<BlockEditor />
								</>
							}
						/>
						<Popover.Slot />
					</FocusReturnProvider>
				</DropZoneProvider>
			</SlotFillProvider>
		</>
	);
}
