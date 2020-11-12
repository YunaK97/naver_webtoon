<?php
//READ
function getCommentsUserIdx($commentIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select user_idx FROM `COMMENTS` where idx=?;";

    $st = $pdo->prepare($query);
    $st->execute([$commentIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['user_idx'];
}
//READ
function alreadyExistCommentsState($commentIdx,$userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from LIKECOMMENTS where comment_idx = ? AND user_idx=?) exist;";

    $st = $pdo->prepare($query);
    $st->execute([$commentIdx,$userIdxInToken]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}
//READ
function getCommentsState($commentIdx,$userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "select status from LIKECOMMENTS where comment_idx = ? AND user_idx=?";

    $st = $pdo->prepare($query);
    $st->execute([$commentIdx,$userIdxInToken]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['status'];
}

function createCommentsState($commentIdx,$userIdxInToken,$state)
{
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO `LIKECOMMENTS` (user_idx,comment_idx,status) VALUES (?,?,?);";
    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken,$commentIdx,$state]);
    $st = null;
    $pdo = null;
}
function updateCommentsState($commentIdx, $userIdxInToken,$state)
{
    $pdo = pdoSqlConnect();
    $query = "UPDATE `LIKECOMMENTS` SET status=? where user_idx=? and comment_idx=?;";
    $st = $pdo->prepare($query);
    $st->execute([$state,$userIdxInToken,$commentIdx]);
    $st = null;
    $pdo = null;
}
function deleteState($commentIdx,$userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "UPDATE `LIKECOMMENTS` SET status='N' where user_idx=? and comment_idx=?;";
    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken,$commentIdx]);
    $st = null;
    $pdo = null;
}

