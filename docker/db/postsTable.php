<?php
require_once('usersTable.php');

class postsTable
{
    /**
     *投稿一覧データ取得
     * 
     * @return mixed $result
     */
    public function getPostAscSeqNo()
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
     *投稿データ登録
     * 
     * @return void
     */
    public function insertPost()
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

    /**
     *投稿データ削除
     * 
     * @return void
     */
    public function deletePost()
    {
        $datainfo = new usersTable();
        $dataconnect = $datainfo->connectDatabase();
        try {
            $delete = $_POST["delete"];
            $sql = 'delete from posts where seq_no=:number;';
            $deletedata = $dataconnect->prepare($sql);
            $deletedata->bindValue(':number', $delete);
            $deletedata->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     *投稿データ編集
     * 
     * @return void
     */
    public function updatePost()
    {
        $datainfo = new usersTable();
        $dataconnect = $datainfo->connectDatabase();
        try {
            $edittitle = $_POST['editTitle'];
            $editcontent = $_POST['editContent'];
            $date = new DateTime();
            $currentdate = $date->format('Y/m/d');
            $number = $_POST["number"];
            $sql = 'UPDATE posts SET post_title =:edittitle, post_contents=:editcontent ,post_date =:postdate where seq_no =:number;';
            $editdata = $dataconnect->prepare($sql);
            $editdata->bindValue(':edittitle', $edittitle);
            $editdata->bindValue(':editcontent', $editcontent);
            $editdata->bindValue(':postdate', $currentdate);
            $editdata->bindValue(':number', $number);
            $editdata->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     *投稿データの一括削除
     * 
     * @return void
     */
    public function multiDeletePost()
    {
        $datainfo = new usersTable();
        $dataconnect = $datainfo->connectDatabase();
        try {
            $sql = 'delete from posts where seq_no=:number;';
            $deletedata = $dataconnect->prepare($sql);
            $params = $_POST["delete"];
            foreach ($params as $value) {
                $deletedata->execute(array(':number' => $value));
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}