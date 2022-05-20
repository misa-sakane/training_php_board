<?php
require_once('docker/web/php/Validation.php');
require_once('docker/db/usersTable.php');

session_start();
//ログインボタンが押された場合
if (isset($_POST["Login"])) {
    $loginuserid = htmlspecialchars($_POST['loginId']);
    $loginpassword = $_POST['loginPassword'];
    $validationcheck = new Validation();
    $loginerrormessage = $validationcheck->userLoginValidation($loginuserid, $loginpassword);
    if (!empty($loginerrormessage)) {
        $alert = "<script type='text/javascript'>alert('$loginerrormessage');</script>";
        echo $alert;
    } else {
        header('Location:docker/web/php/posts.php');
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
                <input type="submit" name="Login" value="ログインする">
            </div>
            <div class="newAccount-button">
                <a href="docker/web/php/creatAccount.php">新規追加はこちら</a>
            </div>
        </form>
    </div>

</body>

</html>