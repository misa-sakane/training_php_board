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

    <div class="mainArea">
        <h1>Bullentin board</h1>
        <p>新規追加画面</p>
    </div>

    <div class="authorization">
        <form method="post" action="login">
            <div class="authorization-form">
                <h2>新規追加</h2>
                <p>ユーザーIDとパスワードを入力してください。</p>
            </div>

            <div class="forms">
                <input type="text" autocomplete="off" placeholder="ユーザーID">
                <div class="passward-form">
                    <input type="text" autocomplete="off" placeholder="パスワード">
                    <input type="text" autocomplete="off" placeholder="パスワード確認">
                </div>
            </div>

            <div class="login-button">
                <input onclick="location.href='login'" value="登録する">
            </div>

            </from>
    </div>

</body>

</html>