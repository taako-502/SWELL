module.exports = {
    /**
     * lazyloadでPCとSPのバックグラウンド画像を変えたい時に使う
     */
    // LazyHook: (isPC) => {
    //     document.addEventListener('lazybeforeunveil', function (e) {
    //         const pcsrc = e.target.getAttribute('data-pcsrc');
    //         const pcbg = e.target.getAttribute('data-pcbg');
    //         if (pcsrc) {
    //             const spsrc = e.target.getAttribute('data-spsrc');
    //             const src   = (isPC) ? pcsrc : spsrc;
    //             console.log(src);
    //             e.target.setAttribute('src', src);
    //         }
    //         if (pcbg) {
    //             const spbg = e.target.getAttribute('data-spbg');
    //             const bg   = (isPC)? pcbg : spbg;
    //             e.target.style.backgroundImage = 'url(' + bg + ')';
    //         }
    //     });
    // },
};
