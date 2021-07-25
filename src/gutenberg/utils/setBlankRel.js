/**
 * ターゲット属性に合わせて rel を調節
 */
const setBlankRel = ( value, nowRel ) => {
	let newRel = nowRel || '';
	if ( value ) {
		// noopener / noreferrerがなければつける
		if ( -1 === newRel.indexOf( 'noopener' ) ) {
			newRel += ' noopener';
		}
		if ( -1 === newRel.indexOf( 'noreferrer' ) ) {
			newRel += ' noreferrer';
		}
	} else {
		// noopener / noreferrerを消す
		newRel = newRel.replace( 'noopener', '' );
		newRel = newRel.replace( 'noreferrer', '' );
	}

	return newRel.trim();
};
export default setBlankRel;
