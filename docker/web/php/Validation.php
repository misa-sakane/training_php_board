<?php
require_once('ValidationUtil.php');

class Validation
{
    /**
     * 新規追加画面のバリデーションチェック
     * 
     * @param string $userId ユーザーID
     * @param string $password パスワード
     * @param string $passwordCheck パスワード確認
     * @return string $errors
     */
    public function userRegistValidation($userid, $password, $passwordcheck)
    {
        $errors = "";

        //必須項目のチェック
        if (empty($userid) || empty($password) || empty($passwordcheck)) {
            $errors = $errors . "項目が未入力です。" . '\n';
        }
        //ユーザーIDの半角英数・文字数制限チェック
        if (!ValidationUtil::isHanEisu($userid) || !ValidationUtil::isMaxLength($userid, 20)) {
            $errors = $errors . "ユーザーIDは半角英数入力20文字以下でしてください。" . '\n';
        }
        //パスワードの半角英数・文字数制限チェック
        if (!ValidationUtil::isHanEisu($password) || !ValidationUtil::isMaxLength($password, 30)) {
            $errors = $errors . "パスワードは半角英数入力30文字以下でしてください。" . '\n';
        }
        //パスワード確認の半角英数・文字数制限チェック
        if (!ValidationUtil::isHanEisu($passwordcheck) || !ValidationUtil::isMaxLength($passwordcheck, 30)) {
            $errors = $errors . "パスワード確認は半角英数入力30文字以下でしてください。" . '\n';
        }
        //パスワードとパスワード確認の一致チェック
        if ($password != $passwordcheck) {
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
     * @param string $loginuserId ユーザーID
     * @param string $loginpassword パスワード
     * @return string $errors
     */
    public function userLoginValidation($loginuserid, $loginpassword)
    {
        $errors = "";
        $dataselect = new usersTable();
        $selectuserinfo = $dataselect->userLogin($loginuserid);

        //必須項目のチェック
        if (empty($loginuserid) || empty($loginpassword)) {
            $errors = $errors . "項目が未入力です。" . '\n';
        }
        //ユーザーIDがあるかチェック
        if (!$selectuserinfo) {
            $errors = $errors . "ユーザーIDが存在しません。" . '\n';
        } else {
            //指定したハッシュがパスワードにマッチしているかチェック
            if (password_verify($loginpassword,  $selectuserinfo['password'])) {
                //DBのユーザー情報をセッションに保存
                $_SESSION['loginId'] =  $selectuserinfo['user_id'];
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