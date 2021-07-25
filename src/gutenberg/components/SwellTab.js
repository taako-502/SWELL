/**
 * @WordPress dependencies
 */
import { memo } from '@wordpress/element';
import { Button } from '@wordpress/components';

/**
 * @Others dependencies
 */
import classnames from 'classnames';

/**
 * SwellTab
 */
export default memo((props) => {
	const { tabs, state, setState, className } = props;
	return (
		<div className={classnames('swell-components-tab', className)}>
			{tabs.map((tab) => {
				return (
					<Button
						isSecondary={state !== tab.key}
						isPrimary={state === tab.key}
						onClick={() => {
							setState(tab.key);
						}}
						key={`tabkey_${tab.key}`}
					>
						{tab.label}
					</Button>
				);
			})}
		</div>
	);
});
