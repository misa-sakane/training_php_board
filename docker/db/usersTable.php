<?php
session_start();

class usersTable
{
    /**
     * DB接続
     * 
     * @return mixed $dbinfo 
     */
    public function connectDatabase()
    {
        $dbname = 'pgsql:dbname=board_database; host=db; port=5555;';
        $user = 'root';
        $bdpassword = 'password';
        $dbinfo = new PDO($dbname, $user, $bdpassword);
        return $dbinfo;
    }

    /**
     * ユーザー情報の新規登録処理
     * 
     * @param string $userid ユーザーID
     * @param string $password パスワード
     * @return void
     */
    public function userRegist($userid, $password)
    {
        try {
            $dbconnect = $this->connectDatabase();
            $sql = "SELECT * FROM users WHERE user_id=:userId;";
            $stmt = $dbconnect->prepare($sql);
            $stmt->bindValue(':userId', $userid);
            $stmt->execute();
            $users = $stmt->fetchAll();
            foreach ($users as $user) {
                if ($user['user_id']) {
                    $errors = '同じユーザーIDが存在します。';
                    return $errors;
                }
            }
            $passwordhash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users(user_id, password) VALUES (:userId, :password)";
            $stmt = $dbconnect->prepare($sql);
            $stmt->bindValue(':userId', $userid);
            $stmt->bindValue(':password', $passwordhash);
            $stmt->execute();
            header('Location:/');
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     *ログイン処理
     * 
     * @param string $loginuserid ユーザーID
     * @param string $loginpassword パスワード
     * @return mixed $datainfo
     */
    public function userLogin($loginuserId)
    {
        try {
            $dbconnect = $this->connectDatabase();
            $sql = "SELECT * FROM users WHERE user_id=:userId;";
            $stmt = $dbconnect->prepare($sql);
            $stmt->bindValue(':userId', $loginuserId);
            $stmt->execute();
            $datainfo = $stmt->fetch();
            return $datainfo;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     *ユーザー一覧データ取得
     * 
     * @return mixed $result
     */
    public function getUsserAscSeqNo()
    {
        $dataconnect = $this->connectDatabase();
        try {
            $sql = 'select * from users order by seq_no asc;';
            $tabledata = $dataconnect->prepare($sql);
            $tabledata->execute();
            $result = $tabledata->fetchAll();
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     *ユーザーデータ削除
     * 
     * @return void
     */
    public function deleteUser()
    {
        $dataconnect = $this->connectDatabase();
        try {
            $delete = $_POST["delete"];
            $sql = 'delete from users where seq_no=:number;';
            $deletedata = $dataconnect->prepare($sql);
            $deletedata->bindValue(':number', $delete);
            $deletedata->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     *ユーザーデータ編集
     * 
     * @return void
     */
    public function updateUser()
    {
        $dataconnect = $this->connectDatabase();
        try {

            $edituserid = $_POST['editUserId'];
            $editpassword = $_POST['editPassword'];
            $editpasswordhash = password_hash($editpassword, PASSWORD_DEFAULT);
            $number = $_POST["number"];
            $sql = 'UPDATE users SET user_id =:edituserid, password=:editpassword  where seq_no =:number;';
            $editdata = $dataconnect->prepare($sql);
            $editdata->bindValue(':edituserid', $edituserid);
            $editdata->bindValue(':editpassword', $editpasswordhash);
            $editdata->bindValue(':number', $number);
            $editdata->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     *ユーザーデータの一括削除
     * 
     * @return void
     */
    public function multiDeleteUser()
    {
        $dataconnect = $this->connectDatabase();
        try {
            $sql = 'delete from users where seq_no=:number;';
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