function setOlStart() {
	// console.log('SWELL: ol_start');
	const olWithStart = document.querySelectorAll('ol[start]');
	for (let i = 0; i < olWithStart.length; i++) {
		const ol = olWithStart[i];
		const start = ol.getAttribute('start');
		const isR = ol.getAttribute('reversed');
		if (null === isR) {
			ol.style.counterReset = 'li ' + (parseInt(start) - 1);
		} else {
			ol.style.counterReset = 'li ' + (parseInt(start) + 1);
		}
	}
}
setOlStart();
if (window.SWELLHOOK) window.SWELLHOOK.barbaAfter.add(setOlStart);
