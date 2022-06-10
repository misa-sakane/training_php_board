<?php
require_once('DataBaseConnect.php');

class PostsTable
{
    /**
     *投稿一覧データ取得
     * 
     * @return mixed $result
     */
    public function getPostAscSeqNo()
    {
        $data_base_connect = new DataBaseConnect();
        $db_connect = $data_base_connect->connectDataBase();
        try {
            $sql = 'select * from posts order by seq_no asc;';
            $table_data = $db_connect->prepare($sql);
            $table_data->execute();
            $result = $table_data->fetchAll();
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
        $data_base_connect = new DataBaseConnect();
        $db_connect = $data_base_connect->connectDataBase();
        try {
            $title = $_POST['postTitle'];
            $content = $_POST['postContent'];
            $date = new DateTime();
            $current_date = $date->format('Y/m/d');
            $sql = 'INSERT INTO posts (user_id,post_date,post_title,post_contents) values(:loginId,:post_date,:title,:content)';
            $add_data = $db_connect->prepare($sql);
            $add_data->bindValue(':loginId', $_SESSION['loginId']);
            $add_data->bindValue(':post_date', $current_date);
            $add_data->bindValue(':title', $title);
            $add_data->bindValue(':content', $content);
            $add_data->execute();
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
        $data_base_connect = new DataBaseConnect();
        $db_connect = $data_base_connect->connectDataBase();
        try {
            $delete = $_POST["delete"];
            $sql = 'delete from posts where seq_no=:number;';
            $delete_data = $db_connect->prepare($sql);
            $delete_data->bindValue(':number', $delete);
            $delete_data->execute();
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
        $data_base_connect = new DataBaseConnect();
        $db_connect = $data_base_connect->connectDataBase();
        try {
            $edit_title = $_POST['editTitle'];
            $edit_content = $_POST['editContent'];
            $date = new DateTime();
            $current_date = $date->format('Y/m/d');
            $number = $_POST["number"];
            $sql = 'UPDATE posts SET post_title =:edittitle, post_contents=:editcontent ,post_date =:postdate where seq_no =:number;';
            $edit_data = $db_connect->prepare($sql);
            $edit_data->bindValue(':edittitle', $edit_title);
            $edit_data->bindValue(':editcontent', $edit_content);
            $edit_data->bindValue(':postdate', $current_date);
            $edit_data->bindValue(':number', $number);
            $edit_data->execute();
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
        $data_base_connect = new DataBaseConnect();
        $db_connect = $data_base_connect->connectDataBase();
        try {
            $sql = 'delete from posts where seq_no=:number;';
            $delete_data = $db_connect->prepare($sql);
            $params = $_POST["delete"];
            foreach ($params as $value) {
                $delete_data->execute(array(':number' => $value));
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     *投稿日時昇順データ取得
     * 
     * @return mixed $result
     */
    public function getPostAscPostDate()
    {
        $data_base_connect = new DataBaseConnect();
        $db_connect = $data_base_connect->connectDataBase();
        try {
            $sql = 'select * from posts order by post_date asc;';
            $table_data = $db_connect->prepare($sql);
            $table_data->execute();
            $result = $table_data->fetchAll();
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     *投稿日時昇順データ取得
     * 
     * @return mixed $result
     */
    public function getPostDescPostDate()
    {
        $data_base_connect = new DataBaseConnect();
        $db_connect = $data_base_connect->connectDataBase();
        try {
            $sql = 'select * from posts order by post_date desc;';
            $table_data = $db_connect->prepare($sql);
            $table_data->execute();
            $result = $table_data->fetchAll();
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}