/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useMemo } from '@wordpress/element';
import { applyFormat, removeFormat } from '@wordpress/rich-text';
import { URLPopover } from '@wordpress/block-editor';
import { Button, ButtonGroup } from '@wordpress/components';

/**
 * @Self dependencies
 */
import getPopoverAnchorRect from '../../helper/getPopoverAnchorRect';

export default function (props) {
	const { value, name, isAdding, onChange, fontSizes, activeSize } = props;

	const anchorRect = useMemo(() => getPopoverAnchorRect(isAdding), []);
	if (!anchorRect) {
		return null;
	}

	return (
		<URLPopover anchorRect={anchorRect} {...props}>
			<div>
				<ButtonGroup>
					{fontSizes.map((size) => {
						const isSelected = size.val === activeSize;
						return (
							<Button
								className={`-${size.val}`}
								isSecondary={!isSelected}
								isPrimary={isSelected}
								onClick={() => {
									if ('' === size.val || isSelected) {
										onChange(removeFormat(value, name));
									} else {
										onChange(
											applyFormat(value, {
												type: name,
												attributes: {
													class: 'u-fz-' + size.val,
												},
											})
										);
									}
								}}
								key={`key_fz_btn_${size.val}`}
							>
								{size.label}
							</Button>
						);
					})}
				</ButtonGroup>
				<div className='swell-clearBtnWrap'>
					<Button
						isSmall
						className='-clear-btn'
						onClick={() => {
							onChange(removeFormat(value, name));
						}}
					>
						{__('Clear')}
					</Button>
				</div>
			</div>
		</URLPopover>
	);
}
