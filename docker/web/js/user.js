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
     *ユーザー一覧を表示する
     * 
     * @return void
     */
    function getUserDataBase() {
        $.ajax({
                type: 'POST',
                url: '../php/ajax.php',
                datatype: 'json',
                data: {
                    'class': 'UsersTable',
                    'func': 'getUserAscSeqNo',
                },
            })
            .done(function(data) {
                $.each(data, function(key, value) {
                    $('#user-data').append('<tr><td>' +
                        '<input type="checkbox" id="check" value=' +
                        value.seq_no + ' class="chk"></td><td id="seq-no">' +
                        value.seq_no + '</td><td class="userid"id="edit-userid-' +
                        value.seq_no + '">' +
                        value.user_id + '</td><td class="edit-botton" id=' +
                        value.seq_no + ' ><i class="fa-solid fa-pen-to-square"></i></td><td class="delete-btn" id=' +
                        value.seq_no + '>&times;</td></tr>')
                });
            })
            .fail(function(data) {
                alert('通信失敗');
            })
    }
    //getUserDataBase関数の呼び出す
    getUserDataBase();

    //削除アイコンを押した後の処理
    $(document).on('click', '.delete-btn', function() {
        const NUMBER = $(this).attr('id');
        $result = confirm('No.' + NUMBER + 'のユーザーを削除してよろしいですか？');
        if ($result === false) {
            return;
        }
        $.ajax({
                type: 'POST',
                url: '../php/ajax.php',
                datatype: 'json',
                data: {
                    'class': 'UsersTable',
                    'func': 'deleteUser',
                    'delete': NUMBER,
                },
            })
            .done(function(data) {
                $('#user-data').empty();
                getUserDataBase();
            })
            .fail(function(data) {
                alert('通信失敗');
            })
    })

    /**
     * ユーザー情報編集のバリデーションチェック
     * 
     * @return string | void
     */
    function userValidaton(INPUT_USER_ID, INPUT_PASSWORD, INPUT_PASSWORD_CHECK) {
        let errors = [];
        //必須項目のチェック
        if (INPUT_USER_ID === "" || INPUT_PASSWORD === "" || INPUT_PASSWORD_CHECK === "") {
            errors.push("項目が未入力です。\n");
        }
        //ユーザーIDの半角英数・文字数制限チェック
        if (INPUT_USER_ID.length > 20 || !INPUT_USER_ID.match(/^[A-Za-z0-9]*$/)) {
            errors.push("ユーザーIDは半角英数入力20文字以下でしてください。\n");
        }
        //パスワードの半角英数・文字数制限チェック
        if (INPUT_PASSWORD.length > 30 || !INPUT_PASSWORD.match(/^[A-Za-z0-9]*$/)) {
            errors.push("パスワードは半角英数入力30文字以下でしてください。\n");
        }
        //パスワードチェックの半角英数・文字数制限チェック
        if (INPUT_PASSWORD_CHECK.length > 30 || !INPUT_PASSWORD_CHECK.match(/^[A-Za-z0-9]*$/)) {
            errors.push("パスワードチェックは半角英数入力30文字以下でしてください。\n");
        }
        //パスワードとパスワード確認の一致チェック
        if (INPUT_PASSWORD !== INPUT_PASSWORD_CHECK) {
            errors.push("パスワードを一致させてください。\n");
        }
        //エラーが１つでもヒットしていたらエラー文表示
        if (errors.length > 0) {
            let errormessage = errors.join("");
            return errormessage;
        }
    }

    //編集アイコンを押した後の処理
    $(document).on('click', '.edit-botton', function() {
        //その列のユーザーIDを取得
        const SEQ = $(this).attr('id');
        const USER_ID = document.getElementById('edit-userid-' + SEQ).innerHTML;
        document.getElementById('edit-seq_no').value = SEQ;
        document.getElementById('userid-form').value = USER_ID;
        $('#user-edit-modal').fadeIn();
    })

    //閉じるボタン押した後の処理
    $('#close-modal').click(function() {
        $('#user-edit-modal').fadeOut();
        document.getElementById("userid-form").value = '';
        document.getElementById("password-form").value = '';
        document.getElementById("passwordcheck-form").value = '';
    })

    //編集モーダルの変更ボタンを押した後の処理
    $(document).on('click', '#update-user-button', function() {
        const SEQ_NO = document.getElementById('edit-seq_no').value;
        const INPUT_USER_ID = document.getElementById('userid-form').value;
        const INPUT_PASSWORD = document.getElementById('password-form').value;
        const INPUT_PASSWORD_CHECK = document.getElementById('passwordcheck-form').value;
        const ERRORS = userValidaton(INPUT_USER_ID, INPUT_PASSWORD, INPUT_PASSWORD_CHECK);
        if (ERRORS) {
            alert(ERRORS);
            return;
        }
        $.ajax({
                type: 'POST',
                url: '../php/ajax.php',
                datatype: 'json',
                data: {
                    'class': 'UsersTable',
                    'func': 'updateUser',
                    'editUserId': INPUT_USER_ID,
                    'editpassword': INPUT_PASSWORD,
                    'number': SEQ_NO,
                },
            })
            .done(function(data) {
                $('#user-edit-modal').fadeOut();
                $('#js-black-bg').fadeOut();
                $('#user-data').empty();
                getUserDataBase()
                document.getElementById("userid-form").value = '';
                document.getElementById("password-form").value = '';
                document.getElementById("passwordcheck-form").value = '';
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
    //changeActive関数を呼び出す
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
        $result = confirm('No.' + ARR + 'のユーザーを削除してよろしいですか？');
        if ($result === false) {
            return;
        }
        $.ajax({
                type: 'POST',
                url: '../php/ajax.php',
                datatype: 'json',
                data: {
                    'class': 'UsersTable',
                    'func': 'multiDeleteUser',
                    'delete': ARR,
                },
            })
            .done(function(data) {
                $('#user-data').empty();
                getUserDataBase();
                changeActive();
            })
            .fail(function(data) {
                alert('通信失敗');
            })
    })
})