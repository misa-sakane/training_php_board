<?php
require_once('DataBaseConnect.php');

class UsersTable
{
    /**
     * ユーザー情報の新規登録処理
     * 
     * @param string $user_id ユーザーID
     * @param string $password パスワード
     * @return void
     */
    public function insertUser($user_id, $password)
    {
        try {
            $data_base_connect = new DataBaseConnect();
            $db_connect = $data_base_connect->connectDataBase();
            $sql = "SELECT * FROM users WHERE user_id=:userId;";
            $stmt = $db_connect->prepare($sql);
            $stmt->bindValue(':userId', $user_id);
            $stmt->execute();
            $users = $stmt->fetchAll();
            foreach ($users as $user) {
                if ($user['user_id']) {
                    $errors = '同じユーザーIDが存在します。';
                    return $errors;
                }
            }
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users(user_id, password) VALUES (:userId, :password)";
            $stmt = $db_connect->prepare($sql);
            $stmt->bindValue(':userId', $user_id);
            $stmt->bindValue(':password', $password_hash);
            $stmt->execute();
            header('Location:/');
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     *ログイン処理
     * 
     * @param string $login_userid ユーザーID
     * @return mixed $data_info
     */
    public function getUserWhereUserId($login_user_id)
    {
        try {
            $data_base_connect = new DataBaseConnect();
            $db_connect = $data_base_connect->connectDataBase();
            $sql = "SELECT * FROM users WHERE user_id=:userId;";
            $stmt = $db_connect->prepare($sql);
            $stmt->bindValue(':userId', $login_user_id);
            $stmt->execute();
            $data_info = $stmt->fetch();
            return $data_info;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     *ユーザー一覧データ取得
     * 
     * @return mixed $result
     */
    public function getUserAscSeqNo()
    {
        $data_base_connect = new DataBaseConnect();
        $db_connect = $data_base_connect->connectDataBase();
        try {
            $sql = 'select * from users order by seq_no asc;';
            $table_data = $db_connect->prepare($sql);
            $table_data->execute();
            $result = $table_data->fetchAll();
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
        $data_base_connect = new DataBaseConnect();
        $db_connect = $data_base_connect->connectDataBase();
        try {
            $delete = $_POST["delete"];
            $sql = 'delete from users where seq_no=:number;';
            $delete_data = $db_connect->prepare($sql);
            $delete_data->bindValue(':number', $delete);
            $delete_data->execute();
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
        $data_base_connect = new DataBaseConnect();
        $db_connect = $data_base_connect->connectDataBase();
        try {

            $edit_user_id = $_POST['editUserId'];
            $edit_password = $_POST['editPassword'];
            $edit_password_hash = password_hash($edit_password, PASSWORD_DEFAULT);
            $number = $_POST["number"];
            $sql = 'UPDATE users SET user_id =:edituserid, password=:editpassword  where seq_no =:number;';
            $edit_data = $db_connect->prepare($sql);
            $edit_data->bindValue(':edituserid', $edit_user_id);
            $edit_data->bindValue(':editpassword', $edit_password_hash);
            $edit_data->bindValue(':number', $number);
            $edit_data->execute();
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
        $data_base_connect = new DataBaseConnect();
        $db_connect = $data_base_connect->connectDataBase();
        try {
            $sql = 'delete from users where seq_no=:number;';
            $delete_data = $db_connect->prepare($sql);
            $params = $_POST["delete"];
            foreach ($params as $value) {
                $delete_data->execute(array(':number' => $value));
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}