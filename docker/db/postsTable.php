<?php
require_once('usersTable.php');

class postsTable
{
    /**
     *投稿一覧データ取得
     * 
     * @return mixed $result
     */
    public function getPostData()
    {
        $datainfo = new usersTable();
        $dataconnect = $datainfo->connectDatabase();
        try {
            $sql = 'select * from posts order by seq_no asc;';
            $tabledata = $dataconnect->prepare($sql);
            $tabledata->execute();
            $result = $tabledata->fetchAll();
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     *新しい投稿データ取得
     * 
     * @return mixed $result
     */
    public function getnewPostData()
    {
        $datainfo = new usersTable();
        $dataconnect = $datainfo->connectDatabase();
        try {
            $sql = 'select * from posts where seq_no=(select max(seq_no) from posts);';
            $tabledata = $dataconnect->prepare($sql);
            $tabledata->execute();
            $result = $tabledata->fetchAll();
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     *投稿データ登録
     * 
     * @return void
     */
    public function addPostData()
    {
        $datainfo = new usersTable();
        $dataconnect = $datainfo->connectDatabase();
        try {
            $title = $_POST['postTitle'];
            $content = $_POST['postContent'];
            $date = new DateTime();
            $currentdate = $date->format('Y/m/d');
            $sql = 'INSERT INTO posts (user_id,post_date,post_title,post_contents) values(:loginId,:post_date,:title,:content)';
            $adddata = $dataconnect->prepare($sql);
            $adddata->bindValue(':loginId', $_SESSION['loginId']);
            $adddata->bindValue(':post_date', $currentdate);
            $adddata->bindValue(':title', $title);
            $adddata->bindValue(':content', $content);
            $adddata->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}