/**
 * 追従サイドバーウィジェットの位置・サイズのセット
 */
export default function setFixWidget(fixSidebar) {
	// 追従サイドバーに複数のウィジェットがあればクラスを付与
	const fixWidgetItems = fixSidebar.querySelectorAll('.c-widget');
	if (1 < fixWidgetItems.length) {
		fixSidebar.classList.add('-multiple');
	}
}
