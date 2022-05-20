<?php
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
}