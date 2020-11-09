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
function getWebtoonIdx($episodeIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select webtoon_idx FROM `EPISODE` where idx=?;";

    $st = $pdo->prepare($query);
    $st->execute([$episodeIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['webtoon_idx'];
}
//READ
function getNick($userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "select nick FROM `USER` where idx=?;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['nick'];
}
//READ
function alreadExistHeart($episodeIdx,$userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from HEART where episode_idx = ? AND user_idx=?) exist;";

    $st = $pdo->prepare($query);
    $st->execute([$episodeIdx,$userIdxInToken]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}
//READ
function alreadydeleteHeart($episodeIdx,$userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from HEART where episode_idx = ? AND user_idx=? and is_deleted='Y') exist;";

    $st = $pdo->prepare($query);
    $st->execute([$episodeIdx,$userIdxInToken]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}
//READ
function checkStar($userIdxInToken,$episodeIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from STARRATING where episode_idx = ? AND user_idx=?) exist;";

    $st = $pdo->prepare($query);
    $st->execute([$episodeIdx,$userIdxInToken]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}
function createHeart($episodeIdx,$userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO `HEART` (user_idx,episode_idx) VALUES (?,?);";
    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken,$episodeIdx]);
    $st = null;
    $pdo = null;
}
function createStar($userIdxInToken,$episodeIdx,$score,$webtoonIdx)
{
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO `STARRATING` (user_idx,episode_idx,score,webtoon_idx) VALUES (?,?,?,?);";
    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken,$episodeIdx,$score,$webtoonIdx]);
    $st = null;
    $pdo = null;
}
function deleteHeart($episodeIdx,$userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "UPDATE `HEART` SET is_deleted='Y' where user_idx=? and episode_idx=?;";
    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken,$episodeIdx]);
    $st = null;
    $pdo = null;
}
function updateHeart($episodeIdx,$userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "UPDATE `HEART` SET is_deleted='N' where user_idx=? and episode_idx=?;";
    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken,$episodeIdx]);
    $st = null;
    $pdo = null;
}
function deleteLike($commentIdx,$userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "UPDATE `LIKECOMMENTS` SET status='N' where user_idx=? and comment_idx=?;";
    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken,$commentIdx]);
    $st = null;
    $pdo = null;
}
function deleteDislike($commentIdx,$userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "UPDATE `LIKECOMMENTS` SET status='N' where user_idx=? and comment_idx=?;";
    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken,$commentIdx]);
    $st = null;
    $pdo = null;
}
function updateCommentsLike($commentIdx,$userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "UPDATE `LIKECOMMENTS` SET status='L' where user_idx=? and comment_idx=?;";
    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken,$commentIdx]);
    $st = null;
    $pdo = null;
}
function updateCommentsDislike($commentIdx,$userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "UPDATE `LIKECOMMENTS` SET status='D' where user_idx=? and comment_idx=?;";
    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken,$commentIdx]);
    $st = null;
    $pdo = null;
}
function createCommentsLike($commentIdx,$userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO `LIKECOMMENTS` (user_idx,comment_idx,status) VALUES (?,?,'L');";
    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken,$commentIdx]);
    $st = null;
    $pdo = null;
}
function createCommentsDislike($commentIdx,$userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO `LIKECOMMENTS` (user_idx,comment_idx,status) VALUES (?,?,'D');";
    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken,$commentIdx]);
    $st = null;
    $pdo = null;
}
function createComments($nick,$userIdxInToken,$episodeIdx,$contents)
{
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO `COMMENTS` (nick,user_idx,episode_idx,contents) VALUES (?,?,?,?);";
    $st = $pdo->prepare($query);
    $st->execute([$nick,$userIdxInToken,$episodeIdx,$contents]);
    $st = null;
    $pdo = null;
}
//READ
function alreadyCommentsDislike($commentIdx,$userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from LIKECOMMENTS where comment_idx = ? AND user_idx=? and status='D') exist;";

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
function alreadyCommentsNo($commentIdx,$userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from LIKECOMMENTS where comment_idx = ? AND user_idx=? and status='N') exist;";

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
function isValidCommentIdx($commentIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from COMMENTS where idx = ?) exist;";

    $st = $pdo->prepare($query);
    $st->execute([$commentIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}
// CREATE
//    function addMaintenance($message){
//        $pdo = pdoSqlConnect();
//        $query = "INSERT INTO MAINTENANCE (MESSAGE) VALUES (?);";
//
//        $st = $pdo->prepare($query);
//        $st->execute([$message]);
//
//        $st = null;
//        $pdo = null;
//
//    }


// UPDATE
//    function updateMaintenanceStatus($message, $status, $no){
//        $pdo = pdoSqlConnect();
//        $query = "UPDATE MAINTENANCE
//                        SET MESSAGE = ?,
//                            STATUS  = ?
//                        WHERE NO = ?";
//
//        $st = $pdo->prepare($query);
//        $st->execute([$message, $status, $no]);
//        $st = null;
//        $pdo = null;
//    }

// RETURN BOOLEAN
//    function isRedundantEmail($email){
//        $pdo = pdoSqlConnect();
//        $query = "SELECT EXISTS(SELECT * FROM USER_TB WHERE EMAIL= ?) AS exist;";
//
//
//        $st = $pdo->prepare($query);
//        //    $st->execute([$param,$param]);
//        $st->execute([$email]);
//        $st->setFetchMode(PDO::FETCH_ASSOC);
//        $res = $st->fetchAll();
//
//        $st=null;$pdo = null;
//
//        return intval($res[0]["exist"]);
//
//    }
