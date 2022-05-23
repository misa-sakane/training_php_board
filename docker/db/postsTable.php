<?php
require_once('usersTable.php');

class postsTable
{
    /**
     *投稿一覧データ取得
     * 
     * @return mixed $result
     */
    public function post()
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
}