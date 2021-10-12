/**
 * リンクボックス
 *
 * @param linkClass    リンクボックス化したいクラスセレクタ
 */
export default function (linkClass) {
	const linkBoxes = document.querySelectorAll(linkClass);
	for (let i = 0; i < linkBoxes.length; i++) {
		linkBoxes[i].addEventListener('click', function (e) {
			e.preventDefault();
			const aTag = linkBoxes[i].querySelector('a');
			const href = aTag.getAttribute('href');
			const target = aTag.getAttribute('target');
			if (target) {
				//target属性があれば別タブで開く
				window.open(href);
			} else {
				//target属性がなければ
				window.location = href;
			}
		});
	}
}
