<?php
require_once('Validation.php');
require_once('../../db/usersTable.php');


//登録するボタンが押された場合
if (isset($_POST["signUp"])) {
    $userid = htmlspecialchars($_POST['userId']);
    $password = $_POST['password'];
    $passwordcheck = $_POST['passwordCheck'];
    $validationcheck = new Validation();
    $errormessage = $validationcheck->userRegistValidation($userid, $password, $passwordcheck);
    if (!empty($errormessage)) {
        $alert = "<script type='text/javascript'>alert('$errormessage');</script>";
        echo $alert;
    } else {
        $registration = new usersTable();
        $registration->userRegist($userid, $password);
    }
} ?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../css/creatAccount.css">
    <title>新規追加|Bullentin board</title>
</head>

<body>
    <div class="header-left">
        <div class="header-logo">Bullentin board</div>
    </div>

    <div class="return-botton">
        <input onclick="location.href='../../../index.php'" value="戻る">
    </div>

    <div class="main-area">
        <h1>Bullentin board</h1>
        <p>新規追加画面</p>
    </div>

    <div class="authorization">
        <form method="post" action="">
            <div class="authorization-form">
                <h2>新規追加</h2>
                <p>ユーザーIDとパスワードを入力してください。</p>
            </div>
            <div class="forms">
                <input type="text" name="userId" maxlength=20 autocomplete="off" placeholder="ユーザーID">
                <div class="passward-form">
                    <input type="password" name="password" maxlength=30 autocomplete="off" placeholder="パスワード">
                    <input type="password" name="passwordCheck" maxlength=30 autocomplete="off" placeholder="パスワード確認">
                </div>
            </div>
            <div class="login-button">
                <input type="submit" name="signUp" value="登録する">
            </div>
        </form>
    </div>
</body>

</html>