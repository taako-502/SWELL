import hexToRgba from 'hex-to-rgba';

/**
 * 背景色
 */
const getBgColor = (attributes) => {
	const { bgColor, bgOpacity } = attributes;
	if (0 === bgOpacity) {
		// backgroundColorなし
		return '';
	} else if (100 === bgOpacity) {
		return bgColor;
	}
	return hexToRgba(bgColor, bgOpacity / 100);
};

/**
 * スタイルをセットする関数
 */
export const getBannerStyle = (attributes) => {
	const { textColor, imgRadius } = attributes;

	const style = {};

	// textColorがセットされているか
	if (textColor) {
		style.color = textColor;
	}

	// 背景色
	const bgColor = getBgColor(attributes);
	if (bgColor) {
		style.backgroundColor = bgColor;
	}

	if (0 !== imgRadius) {
		style.borderRadius = imgRadius + 'px';
	}

	return style;
};
