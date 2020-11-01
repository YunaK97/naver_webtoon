<?php

//DB 정보
function pdoSqlConnect()
{
    try {
        $DB_HOST = "jenny-db.ccsu4h7i4yrh.ap-northeast-2.rds.amazonaws.com";
        $DB_NAME = "ProdDB";
        $DB_USER = "jenny";
        $DB_PW = "a2029612";
        $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PW);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}