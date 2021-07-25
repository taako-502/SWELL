/**
 * closest() の IE用ポリフィル
 */
if (!Element.prototype.matches) {
	Element.prototype.matches =
		Element.prototype.msMatchesSelector ||
		Element.prototype.webkitMatchesSelector;
}

if (!Element.prototype.closest) {
	Element.prototype.closest = function (s) {
		let el = this;

		do {
			if (el.matches(s)) return el;
			el = el.parentElement || el.parentNode;
		} while (null !== el && 1 === el.nodeType);
		return null;
	};
}
