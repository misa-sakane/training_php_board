<?php
require_once('../../db/postsTable.php');

$posttable = new postsTable();
$result = $posttable->getPostData();
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../css/posts.css">
    <script src="https://kit.fontawesome.com/e330008995.js" crossorigin="anonymous"></script>
    <title>投稿一覧|Bullentin board</title>
</head>

<body>
    <header>
        <div class="header-left">
            <div class="header-logo">Bullentin board</div>
        </div>
        <div id="hamburger">
            <span class="inner_line" id="line1"></span>
            <span class="inner_line" id="line2"></span>
            <span class="inner_line" id="line3"></span>
            <span id="menu">MENU</span>
        </div>
        </div>
    </header>

    <div class="main-area">
        <h1>投稿一覧</h1>
        <div class="delete-button">
            <input value="削除">
        </div>
    </div>

    <div class="posts-list">
        <table border="1">
            <tr class="title">
                <th class="checkbox">選択</th>
                <th class="number">No.</th>
                <th class="usersId">ユーザーID</th>
                <th class="data">投稿日時</th>
                <th class="contents">項目（内容）</th>
                <th class="edit">編集</th>
                <th class="delete">削除</th>
            </tr>
            <?php foreach ($result as $value) : ?>
            <tr>
                <td><input type="checkbox"></td>
                <td><?php echo $value["seq_no"] ?></td>
                <td><?php echo $value["user_id"] ?></td>
                <td><?php echo $value["post_date"] ?></td>
                <td class='td-contents'><?php echo $value["post_title"] . "<br>" . $value["post_contents"] ?></td>
                <td><i class="fa-solid fa-pen-to-square"></i>
                </td>
                <td>&times;</td>
            </tr>
            <?php endforeach; ?>

        </table>
    </div>
</body>

</html>