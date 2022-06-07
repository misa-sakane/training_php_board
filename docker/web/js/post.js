$(function() {

    // ハンバーガーメニュー
    const nav = document.getElementById('nav-wrapper');
    const hamburger = document.getElementById('js-hamburger');
    const blackBg = document.getElementById('js-black-bg');
    hamburger.addEventListener('click', function() {
        nav.classList.toggle('open');
    });
    blackBg.addEventListener('click', function() {
        nav.classList.remove('open');
    });

    /**
     * 投稿のバリデーションチェック
     * 
     * @return string | void
     */
    function postValidaton(inputTitle, inputContent) {
        let errors = [];
        //必須項目のチェック
        if (inputTitle === "" || inputContent === "") {
            errors.push("項目が未入力です。\n");
        }
        //投稿タイトルの文字数制限チェック
        if (inputTitle.length > 20) {
            errors.push("投稿タイトルを20文字以下で入力してください。\n");
        }
        //投稿内容の文字数制限チェック
        if (inputContent.length > 200) {
            errors.push("投稿タイトルを200文字以下で入力してください。\n");
        }
        //エラーが１つでもヒットしていたらエラー文表示
        if (errors.length > 0) {
            let errormessage = errors.join("");
            return errormessage;
        }
    }

    //投稿するボタンを押した後の処理
    const postBtn = document.getElementById('post-button');
    postBtn.addEventListener('click', function(event) {
        const inputTitle = document.getElementById('form-title').value;
        const inputContent = document.getElementById('form-content').value;
        const errors = postValidaton(inputTitle, inputContent);
        if (errors) {
            alert(errors);
            return;
        }
        $.ajax({
                type: 'POST',
                url: '../php/ajax.php',
                datatype: 'json',
                data: {
                    'class': 'postsTable',
                    'func': 'insertPost',
                    'postTitle': inputTitle,
                    'postContent': inputContent,
                },
            })
            .done(function(data) {
                nav.classList.toggle('open');
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
                    'class': 'postsTable',
                    'func': 'getPostAscSeqNo',
                },
            })
            .done(function(data) {
                $.each(data, function(key, value) {
                    $('#post-data').append('<tr><td id="checks">' +
                        '<input type="checkbox" id="check" value=' +
                        value.seq_no + ' class="chk" name="chk-dox"></td><td id="seq-no">' +
                        value.seq_no + '</td><td>' +
                        value.user_id + '</td><td>' +
                        value.post_date + '</td><td id="edit-title-' +
                        value.seq_no + '">' +
                        value.post_title + '<br>' + value.post_contents +
                        '</td><td class="edit-botton" id=' + value.seq_no +
                        '><i class="fa-solid fa-pen-to-square"></i></td><td class="deletebtn" id=' +
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
        const seq = $(this).attr('id');
        const title = document.getElementById('edit-title-' + seq).innerHTML;
        const titlesplit = title.split("<br>");
        document.getElementById('edit-title').value = titlesplit[0];
        document.getElementById('edit-content').value = titlesplit[1];
        document.getElementById('edit-seq_no').value = seq;
        $('#post-edit-modal').fadeIn();
    });
    $(document).on('click', '#close-modal', function() {
        $('#post-edit-modal').fadeOut();
    });

    //編集モーダルの投稿ボタンを押した後の処理
    $(document).on('click', '#post-edit-button', function() {
        const seq_no = document.getElementById('edit-seq_no').value;
        const inputTitle = document.getElementById('edit-title').value;
        const inputContent = document.getElementById('edit-content').value;
        const errors = postValidaton(inputTitle, inputContent);
        if (errors) {
            alert(errors);
            return;
        }
        $.ajax({
                type: 'POST',
                url: '../php/ajax.php',
                datatype: 'json',
                data: {
                    'class': 'postsTable',
                    'func': 'updatePost',
                    'editTitle': inputTitle,
                    'editContent': inputContent,
                    'number': seq_no,
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
    $(document).on('click', '.deletebtn', function() {
        const number = $(this).attr('id');
        $result = confirm('No.' + number + 'の投稿を削除してよろしいですか？');
        if ($result == false) {
            return;
        }
        $.ajax({
                type: 'POST',
                url: '../php/ajax.php',
                datatype: 'json',
                data: {
                    'class': 'postsTable',
                    'func': 'deletePost',
                    'delete': number,
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
        const arr = [];
        const check = document.getElementsByClassName('chk');
        for (let i = 0; i < check.length; i++) {
            if (check[i].checked) {
                arr.push(check[i].value);
            }
        }
        $result = confirm('No.' + arr + 'の投稿を削除してよろしいですか？');
        if ($result == false) {
            return;
        }
        $.ajax({
                type: 'POST',
                url: '../php/ajax.php',
                datatype: 'json',
                data: {
                    'class': 'postsTable',
                    'func': 'multideletePost',
                    'delete': arr,
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