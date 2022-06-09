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
                    'class': 'usersTable',
                    'func': 'getUsserAscSeqNo',
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
                        value.seq_no + ' ><i class="fa-solid fa-pen-to-square"></i></td><td class="deletebtn" id=' +
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
    $(document).on('click', '.deletebtn', function() {
        const number = $(this).attr('id');
        $result = confirm('No.' + number + 'のユーザーを削除してよろしいですか？');
        if ($result === false) {
            return;
        }
        $.ajax({
                type: 'POST',
                url: '../php/ajax.php',
                datatype: 'json',
                data: {
                    'class': 'usersTable',
                    'func': 'deleteUser',
                    'delete': number,
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
    function userValidaton(inputUserId, inputPassword, inputPasswordCheck) {
        let errors = [];
        //必須項目のチェック
        if (inputUserId === "" || inputPassword === "" || inputPasswordCheck === "") {
            errors.push("項目が未入力です。\n");
        }
        //ユーザーIDの半角英数・文字数制限チェック
        if (inputUserId.length > 20 || !inputUserId.match(/^[A-Za-z0-9]*$/)) {
            errors.push("ユーザーIDは半角英数入力20文字以下でしてください。\n");
        }
        //パスワードの半角英数・文字数制限チェック
        if (inputPassword.length > 30 || !inputPassword.match(/^[A-Za-z0-9]*$/)) {
            errors.push("パスワードは半角英数入力30文字以下でしてください。\n");
        }
        //パスワードチェックの半角英数・文字数制限チェック
        if (inputPasswordCheck.length > 30 || !inputPasswordCheck.match(/^[A-Za-z0-9]*$/)) {
            errors.push("パスワードチェックは半角英数入力30文字以下でしてください。\n");
        }
        //パスワードとパスワード確認の一致チェック
        if (inputPassword !== inputPasswordCheck) {
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
        const seq = $(this).attr('id');
        const userid = document.getElementById('edit-userid-' + seq).innerHTML;
        document.getElementById('edit-seq_no').value = seq;
        document.getElementById('userid-form').value = userid;
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
        const seq_no = document.getElementById('edit-seq_no').value;
        const inputUserId = document.getElementById('userid-form').value;
        const inputPassword = document.getElementById('password-form').value;
        const inputPasswordCheck = document.getElementById('passwordcheck-form').value;
        const errors = userValidaton(inputUserId, inputPassword, inputPasswordCheck);
        if (errors) {
            alert(errors);
            return;
        }
        $.ajax({
                type: 'POST',
                url: '../php/ajax.php',
                datatype: 'json',
                data: {
                    'class': 'usersTable',
                    'func': 'updateUser',
                    'editUserId': inputUserId,
                    'editpassword': inputPassword,
                    'number': seq_no,
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
        const arr = [];
        const check = document.getElementsByClassName('chk');
        for (let i = 0; i < check.length; i++) {
            if (check[i].checked) {
                arr.push(check[i].value);
            }
        }
        $result = confirm('No.' + arr + 'のユーザーを削除してよろしいですか？');
        if ($result === false) {
            return;
        }
        $.ajax({
                type: 'POST',
                url: '../php/ajax.php',
                datatype: 'json',
                data: {
                    'class': 'usersTable',
                    'func': 'multiDeleteUser',
                    'delete': arr,
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