<?php
require_once('ValidationUtil.php');

class Validation
{
    /**
     * 新規追加画面のバリデーションチェック
     * 
     * @param string $user_Id ユーザーID
     * @param string $password パスワード
     * @param string $password_check パスワード確認
     * @return string $errors
     */
    public function userRegistValidation($user_id, $password, $password_check)
    {
        $errors = "";

        //必須項目のチェック
        if (empty($user_id) || empty($password) || empty($password_check)) {
            $errors = $errors . "項目が未入力です。" . '\n';
        }
        //ユーザーIDの半角英数・文字数制限チェック
        if (!ValidationUtil::isHanEisu($user_id) || !ValidationUtil::isMaxLength($user_id, 20)) {
            $errors = $errors . "ユーザーIDは半角英数入力20文字以下でしてください。" . '\n';
        }
        //パスワードの半角英数・文字数制限チェック
        if (!ValidationUtil::isHanEisu($password) || !ValidationUtil::isMaxLength($password, 30)) {
            $errors = $errors . "パスワードは半角英数入力30文字以下でしてください。" . '\n';
        }
        //パスワード確認の半角英数・文字数制限チェック
        if (!ValidationUtil::isHanEisu($password_check) || !ValidationUtil::isMaxLength($password_check, 30)) {
            $errors = $errors . "パスワード確認は半角英数入力30文字以下でしてください。" . '\n';
        }
        //パスワードとパスワード確認の一致チェック
        if ($password != $password_check) {
            $errors = $errors . "パスワードを一致させてください。";
        }
        //エラーが１つでもヒットしていたらエラー文表示
        if (!empty($errors)) {
            return $errors;
        }
    }

    /**
     * ログイン画面のバリデーションチェック
     * 
     * @param string $login_userId ユーザーID
     * @param string $login_password パスワード
     * @return string $errors
     */
    public function userLoginValidation($login_user_id, $login_password)
    {
        $errors = "";
        $data_select = new UsersTable();
        $select_user_info = $data_select->getUserWhereUserId($login_user_id);

        //必須項目のチェック
        if (empty($login_user_id) || empty($login_password)) {
            $errors = $errors . "項目が未入力です。" . '\n';
        }
        //ユーザーIDがあるかチェック
        if (!$select_user_info) {
            $errors = $errors . "ユーザーIDが存在しません。" . '\n';
        } else {
            //指定したハッシュがパスワードにマッチしているかチェック
            if (password_verify($login_password,  $select_user_info['password'])) {
                //DBのユーザー情報をセッションに保存
                $_SESSION['loginId'] =  $select_user_info['user_id'];
            } else {
                $errors = $errors . "ユーザーIDもしくはパスワードが間違っています。" . '\n';
            }
        }
        //エラーが１つでもヒットしていたらエラー文表示
        if (!empty($errors)) {
            return $errors;
        }
    }
}