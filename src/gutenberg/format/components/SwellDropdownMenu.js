/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { memo } from '@wordpress/element';
import { Slot, DropdownMenu, ToolbarItem } from '@wordpress/components';

// 配置
const POPOVER_PROPS = {
	position: 'bottom center',
};

/**
 * SwellDropdownMenu
 */
export default memo(({ fillName, icon, label }) => {
	return (
		<Slot name={`RichText.ToolbarControls.${fillName}`}>
			{(fills) => {
				if (0 === fills.length) return;

				if (undefined === ToolbarItem) {
					return (
						<DropdownMenu
							icon={
								<>
									{icon}
									<span className='components-dropdown-menu__indicator'></span>
								</>
							}
							label={label}
							controls={fills.map(([{ props }]) => props)}
							popoverProps={POPOVER_PROPS}
						/>
					);
				}
				return (
					<ToolbarItem>
						{(toggleProps) => (
							<DropdownMenu
								icon={
									<>
										{icon}
										<span className='components-dropdown-menu__indicator'></span>
									</>
								}
								label={label}
								toggleProps={toggleProps}
								controls={fills.map(([{ props }]) => props)}
								popoverProps={POPOVER_PROPS}
							/>
						)}
					</ToolbarItem>
				);
			}}
		</Slot>
	);
});
