/**
 * スマホヘッダーナビをセット
 */
export default function setSpHeader() {
    const spHeadNav = document.querySelector('.l-header__spNav');
    if (!spHeadNav) return;
    // スマホヘッダーナビのswiper処理

    //すでにset関数が実行されたかどうかで分岐
    // if (!spHeadNav.classList.contains('show_')) {
    //     setSpHeadNav(spHeadNav);
    // }

    const isMenuLoop = !spHeadNav.classList.contains('-loop-off');
    let swipeOption = {
        loop: isMenuLoop ? true : false,
        centeredSlides: isMenuLoop ? true : false,
        autoplay: false,
        speed: 600,
        runCallbacksOnInit: true,
        slidesPerView: 'auto',
        autoResize: false,
        spaceBetween: 0,
        // on: {
        //     init: function() {
        //         setTimeout(() => {
        //             spHeadNav.classList.add('show_');
        //         }, 10);
        //     },
        // },
    };
    let swiperIndex = 0;
    const spHeadNavList = spHeadNav.querySelectorAll('ul.swiper-wrapper > li');

    //現在のURLを取得 ? や # はをのぞいて
    const nowHref = window.location.origin + window.location.pathname;
    for (let i = 0; i < spHeadNavList.length; i++) {
        const element = spHeadNavList[i];
        // element.classList.add('swiper-slide');

        //-currentクラスを付与
        const elemLink = element.querySelector('a');
        const elemHref = elemLink.getAttribute('href');
        element.classList.remove('-current');
        if (nowHref === elemHref) {
            element.classList.add('-current');

            //スライダーのインデックス番号をセット（-currentは複数あるので初回だけ）
            if (0 === swiperIndex) {
                swiperIndex = i;
            }
        }
    }
    swipeOption.initialSlide = swiperIndex;
    new Swiper(spHeadNav, swipeOption);
}
