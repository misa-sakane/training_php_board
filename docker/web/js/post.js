$(function() {

    // ハンバーガーメニュー
    const NAV = document.getElementById('nav-wrapper');
    const HAMBURGER = document.getElementById('js-hamburger');
    const BLACK_BG = document.getElementById('js-black-bg');
    HAMBURGER.addEventListener('click', function() {
        NAV.classList.toggle('open');
    });
    BLACK_BG.addEventListener('click', function() {
        NAV.classList.remove('open');
    });

    /**
     * 投稿のバリデーションチェック
     * 
     * @return string | void
     */
    function postValidaton(INPUT_TITLE, INPUT_CONTENT) {
        let errors = [];
        //必須項目のチェック
        if (INPUT_TITLE === "" || INPUT_CONTENT === "") {
            errors.push("項目が未入力です。\n");
        }
        //投稿タイトルの文字数制限チェック
        if (INPUT_TITLE.length > 20) {
            errors.push("投稿タイトルを20文字以下で入力してください。\n");
        }
        //投稿内容の文字数制限チェック
        if (INPUT_CONTENT.length > 200) {
            errors.push("投稿タイトルを200文字以下で入力してください。\n");
        }
        //エラーが１つでもヒットしていたらエラー文表示
        if (errors.length > 0) {
            let errormessage = errors.join("");
            return errormessage;
        }
    }

    //投稿するボタンを押した後の処理
    const POSTBTN = document.getElementById('post-button');
    POSTBTN.addEventListener('click', function(event) {
        const INPUT_TITLE = document.getElementById('form-title').value;
        const INPUT_CONTENT = document.getElementById('form-content').value;
        const ERRORS = postValidaton(INPUT_TITLE, INPUT_CONTENT);
        if (ERRORS) {
            alert(ERRORS);
            return;
        }
        $.ajax({
                type: 'POST',
                url: '../php/ajax.php',
                datatype: 'json',
                data: {
                    'class': 'PostsTable',
                    'func': 'insertPost',
                    'postTitle': INPUT_TITLE,
                    'postContent': INPUT_CONTENT,
                },
            })
            .done(function(data) {
                NAV.classList.toggle('open');
                $('#post-modal').fadeOut();
                $('#js-black-bg').fadeOut();
                $('#post-data').empty();
                getPostDataBase();
                document.getElementById("form-title").value = '';
                document.getElementById("form-content").value = '';
            })
            .fail(function(data) {
                alert('通信失敗');
            })
    })

    /**
     *投稿一覧を表示する
     * 
     * @return void
     */
    function getPostDataBase() {
        $.ajax({
                type: 'POST',
                url: '../php/ajax.php',
                datatype: 'json',
                data: {
                    'class': 'PostsTable',
                    'func': 'getPostAscSeqNo',
                },
            })
            .done(function(data) {
                $.each(data, function(key, value) {
                    $('#post-data').append('<tr><td id="checks">' +
                        '<input type="checkbox" id="check" value=' +
                        value.seq_no + ' class="chk"></td><td id="seq-no">' +
                        value.seq_no + '</td><td>' +
                        value.user_id + '</td><td>' +
                        value.post_date + '</td><td id="edit-title-' +
                        value.seq_no + '">' +
                        value.post_title + '<br>' + value.post_contents +
                        '</td><td class="edit-botton" id=' + value.seq_no +
                        '><i class="fa-solid fa-pen-to-square"></i></td><td class="delete-btn" id=' +
                        value.seq_no + '>&times;</i></td></tr>')
                });
            })
            .fail(function(data) {
                alert('通信失敗');
            })
    }
    //getPostDataBase関数の呼び出す
    getPostDataBase();

    //投稿追加を押した時の処理
    $('#add-post').click(function() {
        $('#post-modal').fadeIn();
    });
    $('#close-modal').click(function() {
        $('#post-modal').fadeOut();
    });

    //編集アイコンを押した時の処理
    $(document).on('click', '.edit-botton', function() {
        //その列のタイトルと内容を取得
        const SEQ = $(this).attr('id');
        const TITLE = document.getElementById('edit-title-' + SEQ).innerHTML;
        const TITLE_SPLIT = TITLE.split("<br>");
        document.getElementById('edit-title').value = TITLE_SPLIT[0];
        document.getElementById('edit-content').value = TITLE_SPLIT[1];
        document.getElementById('edit-seq_no').value = SEQ;
        $('#post-edit-modal').fadeIn();
    });
    $(document).on('click', '#close-modal', function() {
        $('#post-edit-modal').fadeOut();
    });

    //編集モーダルの投稿ボタンを押した後の処理
    $(document).on('click', '#post-edit-button', function() {
        const SEQ_NO = document.getElementById('edit-seq_no').value;
        const INPUT_TITLE = document.getElementById('edit-title').value;
        const INPUT_CONTENT = document.getElementById('edit-content').value;
        const ERRORS = postValidaton(INPUT_TITLE, INPUT_CONTENT);
        if (ERRORS) {
            alert(ERRORS);
            return;
        }
        $.ajax({
                type: 'POST',
                url: '../php/ajax.php',
                datatype: 'json',
                data: {
                    'class': 'PostsTable',
                    'func': 'updatePost',
                    'editTitle': INPUT_TITLE,
                    'editContent': INPUT_CONTENT,
                    'number': SEQ_NO,
                },
            })
            .done(function(data) {
                $('#post-data').empty();
                getPostDataBase();
                $('#post-edit-modal').fadeOut();
            })
            .fail(function(data) {
                alert('通信失敗');
            })
    })

    //削除アイコンを押した後の処理
    $(document).on('click', '.delete-btn', function() {
        const NUMBER = $(this).attr('id');
        $result = confirm('No.' + NUMBER + 'の投稿を削除してよろしいですか？');
        if ($result === false) {
            return;
        }
        $.ajax({
                type: 'POST',
                url: '../php/ajax.php',
                datatype: 'json',
                data: {
                    'class': 'PostsTable',
                    'func': 'deletePost',
                    'delete': NUMBER,
                },
            })
            .done(function(data) {
                $('#post-data').empty();
                getPostDataBase();
            })
            .fail(function(data) {
                alert('通信失敗');
            })
    })

    //削除ボタンの活性化・非活性化の切り替え
    function changeActive() {
        $("#delete-btn").prop("disabled", true);
        $(document).on('change', '.chk', function() {
            if ($(".chk:checked").length > 0) {
                // ボタン有効
                $("#delete-btn").prop("disabled", false);
            } else {
                // ボタン無効
                $("#delete-btn").prop("disabled", true);
            }
        });
    }

    //changeActive関数の呼び出し
    changeActive();

    //削除ボタンを押した後の処理
    $("#delete-btn").click(function() {
        //チェックされた要素のバリューを取得する
        const ARR = [];
        const CHECK = document.getElementsByClassName('chk');
        for (let i = 0; i < CHECK.length; i++) {
            if (CHECK[i].checked) {
                ARR.push(CHECK[i].value);
            }
        }
        $result = confirm('No.' + ARR + 'の投稿を削除してよろしいですか？');
        if ($result === false) {
            return;
        }
        $.ajax({
                type: 'POST',
                url: '../php/ajax.php',
                datatype: 'json',
                data: {
                    'class': 'PostsTable',
                    'func': 'multiDeletePost',
                    'delete': ARR,
                },
            })
            .done(function(data) {
                $('#post-data').empty();
                getPostDataBase();
            })
            .fail(function(data) {
                alert('通信失敗');
            })
    })
});