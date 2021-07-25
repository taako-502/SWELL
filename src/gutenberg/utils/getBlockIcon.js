import { iconColor } from '@swell-guten/config';

const getBlockIcon = (src) => {
	return {
		foreground: iconColor,
		src,
	};
};
export default getBlockIcon;
