/**
 * メディアアップローダー
 */
(function($) {
    let my_uploader;

    $('#media_btn').click(function(e) {
        e.preventDefault();

        if (my_uploader) {
            my_uploader.open();
            return;
        }

        //アップローダーの基本設定
        my_uploader = wp.media({
            title: '画像を選択',

            // ライブラリの一覧は画像のみにする
            library: {
                type: 'image',
            },

            //画像選択ボタンのテキスト
            button: {
                text: '画像を決定する',
            },

            // 選択できる画像は1つだけにする
            multiple: false,
        });

        //画像が選択された時の処理
        my_uploader.on('select', function() {
            let images = my_uploader.state().get('selection');

            images.each(function(img) {
                //img の中に選択された画像の各種情報が入ってくる

                //テキストフォームに画像の ID・URL を表示
                //$("#media_id").val(img.id);
                $('#media_url').val(img.attributes.sizes.full.url); //フルサイズのURL

                //プレビュー用に選択されたサムネイル画像を表示
                $('#media_preview')
                    .find('img')
                    .attr('src', img.attributes.sizes.medium.url);
                //$("#crear_btn").show();
            });
        });

        //アップローダー開く
        my_uploader.open();
    });

    // クリアボタンを押した時の処理
    $('#crear_btn').click(function() {
        //$("#media_id").val("");
        $('#media_url').val('');
        $('#media_preview')
            .find('img')
            .attr('src', '');
        //$(this).hide();
    });
})(jQuery);
