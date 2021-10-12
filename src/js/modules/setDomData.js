/**
 * DOMデータ
 */
// import DOM from '@swell-js/modules/data/domData';

export default function setDomData(DOM) {
	DOM.header = document.getElementById('header');
	DOM.bodyWrap = document.getElementById('body_wrap');
	DOM.searchModal = document.getElementById('search_modal');
	DOM.indexModal = document.getElementById('index_modal');
	DOM.pageTopBtn = document.getElementById('pagetop');
	DOM.fixHeader = document.getElementById('fix_header');
	DOM.gnav = document.getElementById('gnav');
	DOM.spMenu = document.getElementById('sp_menu');
	DOM.wpadminbar = document.getElementById('wpadminbar');
	DOM.content = document.getElementById('content') || document.getElementById('lp-content');
	DOM.mainContent = document.getElementById('main_content');
	DOM.sidebar = document.getElementById('sidebar');
	DOM.fixBottomMenu = document.getElementById('fix_bottom_menu');
	DOM.fixSidebar = document.getElementById('fix_sidebar');
}
