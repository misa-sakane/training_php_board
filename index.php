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
        <form method="post" action="login">
            <div class="authorization-form">
                <h2>ログイン</h2>
                <p>ユーザーIDとパスワードを入力してください。</p>
            </div>

            <div class="forms">
                <input type="text" autocomplete="off" placeholder="ユーザーID">
                <input type="text" autocomplete="off" placeholder="パスワード">
            </div>

            <div class="login-button">
                <input onclick="location.href='docker/web/php/posts.php'" value="ログインする">
            </div>

            <div class="newAccount-button">
                <a href="docker/web/php/creatAccount.php">新規追加はこちら</a>
            </div>
            </from>
    </div>

</body>

</html>