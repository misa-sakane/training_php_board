<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../css/user.css">
    <script src="https://kit.fontawesome.com/e330008995.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="../js/user.js" type="text/javascript"></script>
    <title>ユーザー一覧|Bullentin board</title>
</head>

<body>
    <header>
        <div class="header-left">
            <div class="header-logo">Bullentin board</div>
        </div>
        <div id="nav-wrapper" class="nav-wrapper">
            <div class="hamburger" id="js-hamburger">
                <span class="inner-line" id="line1"></span>
                <span class="inner-line" id="line2"></span>
                <span class="inner-line" id="line3"></span>
                <span id="menu">MENU</span>
            </div>
            <nav class="sp-nav">
                <ul class="nav-menu">
                    <li id="post-list"><a href="post.php">投稿一覧</a></li>
                    <li id="logout" name="logout"><a href="../../db/logout.php">ログアウト</a></li>
                </ul>
            </nav>
            <div class="black-bg" id="js-black-bg">
            </div>
    </header>

    <div class="main-area">
        <h1>ユーザー管理</h1>
        <p>ユーザー一覧</p>
        <div class="delete-button">
            <input id="delete-btn" value="削除">
        </div>

        <div class="user-modal-wrapper" id="user-edit-modal">
            <div class="modal">
                <div id="close-modal">
                    <i class="fa fa-2x fa-times"></i>
                </div>
                <div id="user-form">
                    <h2>ユーザー情報編集</h2>
                    <p>ユーザーID</p>
                    <input id="userid-form" name="editUserId" type="text" value="" maxlength=20>
                    <input id="edit-seq_no" type="hidden" value="">
                    <p>パスワード</p>
                    <div class="form-content">
                        <input id="password-form" type="password" name="editPassword" maxlength=30>
                        <input id="passwordcheck-form" type="password" name="editPasswordCheck" maxlength=30>
                    </div>
                </div>
                <div class="update-button">
                    <input type="submit" id="update-user-button" value="変更する">
                </div>
            </div>
        </div>

        <div class="users-list">
            <table border="1">
                <tr class="title">
                    <th class="checkbox">選択</th>
                    <th class="number">No.</th>
                    <th class="users-id">ユーザーID</th>
                    <th class="edit">編集</th>
                    <th class="delete">削除</th>
                </tr>
                <tbody id="user-data">
                </tbody>
            </table>
        </div>
</body>

</html>