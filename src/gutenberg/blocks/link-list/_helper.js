/**
 * @SWELL dependencies
 */
import sliceIconData from '@swell-guten/utils/sliceIconData';

/**
 * @others dependencies
 */
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

/**
 * アイコン
 */
export const IconTag = ({ iconName }) => {
	if (!iconName) return null;
	const iconData = sliceIconData(iconName);
	if (typeof iconData === 'string') {
		return <i className={iconName + ' __icon'}></i>;
	}
	return <FontAwesomeIcon icon={iconData} className='__icon' />;
};
