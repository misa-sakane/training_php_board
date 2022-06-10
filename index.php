<?php
require_once('docker/web/php/Validation.php');
require_once('docker/db/UsersTable.php');

//ログインボタンが押された場合
if (isset($_POST["login"])) {
    $login_user_id = htmlspecialchars($_POST['loginId']);
    $login_password = $_POST['loginPassword'];
    $validation_check = new Validation();
    $login_error_message = $validation_check->userLoginValidation($login_user_id, $login_password);
    if (!empty($login_error_message)) {
        $alert = "<script type='text/javascript'>alert('$login_error_message');</script>";
        echo $alert;
    } else {
        header('Location:docker/web/php/post.php');
    }
} ?>

<html>

<head>
    <link rel="stylesheet" href="docker/web/css/login.css">
    <title>ログイン|Bullentin board</title>
</head>

<body>
    <div class="header-left">
        <div class="header-logo">Bullentin board</div>
    </div>
    <div class="main-area">
        <h1>Bullentin board</h1>
        <p>ログイン画面</p>
    </div>
    <div class="authorization">
        <form method="post" action="">
            <div class="authorization-form">
                <h2>ログイン</h2>
                <p>ユーザーIDとパスワードを入力してください。</p>
            </div>
            <div class="forms">
                <input type="text" name="loginId" maxlength=20 placeholder="ユーザーID">
                <input type="password" name="loginPassword" maxlength=30 placeholder="パスワード">
            </div>
            <div class="login-button">
                <input type="submit" name="login" value="ログインする">
            </div>
            <div class="newAccount-button">
                <a href="docker/web/php/creataccount.php">新規追加はこちら</a>
            </div>
        </form>
    </div>

</body>

</html>