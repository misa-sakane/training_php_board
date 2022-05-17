<?php
require('../../db/usersTable.php');

class validationUtil
{
    public function validation($userId, $password, $passwordCheck)
    {
        $register = new usersTable();
        $errors = "";

        //必須項目のバリデーションチェック
        if (empty($userId && $password && $passwordCheck)) {
            $errors = $errors . "項目が未入力です。" . '\n';
        }
        //ユーザーIDのバリデーションチェック
        if (mb_strlen($userId > 20) && !preg_match("^[a-zA-Z0-9]+$", $userId)) {
            $errors = $errors . "ユーザーIDは半角英数字の20文字以下で入力して下さい。" . '\n';
        }
        //パスワードのバリデーションチェック
        if (mb_strlen($password) > 20 && !preg_match("^[a-zA-Z0-9]+$", $password)) {
            $errors = $errors . "パスワードは半角英数字の30文字以下で入力して下さい。" . '\n';
        }
        //パスワードチェックのバリデーションチェック
        if (mb_strlen($passwordCheck) > 30 && !preg_match("^[a-zA-Z0-9]+$", $passwordCheck)) {
            $errors = $errors . "パスワードチェックは半角英数字の30文字以下で入力して下さい。" . '\n';
        }
        //パスワードとパスワードチェックが一致しているかどうか
        if ($password != $passwordCheck) {
            $errors = $errors . "パスワードを一致させてください。";
        }
        //1つでもエラーがあるときエラー表示
        if (!empty($errors)) {
            return $errors;
        }
    }
}