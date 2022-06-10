<?php

session_start();
//ログインをせずに投稿一覧画面を開けないようにするための対処
if (!isset($_SESSION["loginId"])) {
    header('Location:/');
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="../css/posts.css">
    <script src="https://kit.fontawesome.com/e330008995.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="../js/post.js" type="text/javascript"></script>
    <title>投稿一覧|Bullentin board</title>
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
                    <li id="add-post">投稿追加</li>
                    <li id="user-manage"><a href="user.php">ユーザー管理</a></li>
                    <li id="logout" name="logout"><a href="../../db/logout.php">ログアウト</a></li>
                </ul>
            </nav>
            <div class="black-bg" id="js-black-bg">
            </div>
    </header>

    <div class="main-area">
        <h1>投稿一覧</h1>
        <div class="delete-button">
            <input id="delete-btn" value="削除">
        </div>

        <div class="post-modal-wrapper" id="post-modal">
            <div class="modal">
                <div id="close-modal">
                    <i class="fa fa-2x fa-times"></i>
                </div>
                <div id="post-form">
                    <h2>投稿追加</h2>
                    <p>投稿タイトル</p>
                    <input id="form-title" name="postTitle" type="text" placeholder="20文字以内で入力してください">
                    <p>投稿内容</p>
                    <div class="form-content">
                        <input id="form-content" type="text" name="postContent" maxlength=200>
                    </div>
                </div>
                <div class="post-button">
                    <input type="submit" id="post-button" name="postButton" value="投稿する">
                </div>
            </div>
        </div>

        <div class="post-modal-wrapper" id="post-edit-modal">
            <div class="modal">
                <div id="close-modal">
                    <i class="fa fa-2x fa-times"></i>
                </div>
                <div id="post-form">
                    <h2>投稿編集</h2>
                    <p>投稿タイトル</p>
                    <input id="edit-title" name="postTitle" type="text" value="">
                    <input id="edit-seq_no" type="hidden" value="">
                    <p>投稿内容</p>
                    <div class="form-content">
                        <input id="edit-content" type="text" name="postContent" maxlength=200>
                    </div>
                </div>
                <div class="post-button">
                    <input type="submit" id="post-edit-button" name="postButton" value="投稿する">
                </div>
            </div>
        </div>
    </div>

    <div class="posts-list">
        <table border="1">
            <tr class="title">
                <th class="checkbox">選択</th>
                <th class="number">No.</th>
                <th class="users-id">ユーザーID</th>
                <th class="date">投稿日時
                    <button id="asc-button">▲</button>
                    <button id="desc-button">▼</button>
                </th>
                <th class="contents">項目（内容）</th>
                <th class="edit">編集</th>
                <th class="delete">削除</th>
            </tr>
            <tbody id="post-data">
            </tbody>
        </table>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.1/js/jquery.tablesorter.min.js">
    </script>
</body>

</html>