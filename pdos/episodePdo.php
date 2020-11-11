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
function getCookie($userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "select cookie FROM `USER` where idx=?;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['cookie'];
}
//READ
function getPreviewDetails($episodeIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select CONCAT(WEBTOON.title,' ',EPISODE.title) as name, CASE WHEN EPISODE.form='S' THEN 2
WHEN EPISODE.form='C' THEN 1
ELSE 0 END AS cookie
FROM EPISODE
left join WEBTOON ON WEBTOON.idx=EPISODE.webtoon_idx
WHERE EPISODE.idx=?;";

    $st = $pdo->prepare($query);
    $st->execute([$episodeIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}
//READ
function getMore($userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "select nick,cookie_count
from USER
left join COOKIE ON COOKIE.user_idx=USER.idx
WHERE idx=?;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}
//READ
function getLookEpisode($userIdxInToken)
{
        $pdo = pdoSqlConnect();
        $query = "select episode_idx,CONCAT(EPISODE.title,' 이어보기') AS episode ,WEBTOON.title as title ,WEBTOON.thumbnail as thumbnail,
       CASE WHEN DATEDIFF(now(),LOOK.created_at)<1
                 THEN '오늘'
                    ELSE CONCAT(DATEDIFF(now(),LOOK.created_at),'일전')
        END AS time
from LOOK
left join EPISODE ON EPISODE.idx=LOOK.episode_idx
left join WEBTOON ON WEBTOON.idx=EPISODE.webtoon_idx
where user_idx=? and LOOK.is_deleted='N';";

        $st = $pdo->prepare($query);
        $st->execute([$userIdxInToken]);
        //    $st->execute();
        $st->setFetchMode(PDO::FETCH_ASSOC);
        $res = $st->fetchAll();

        $st = null;
        $pdo = null;

        return $res;
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
function getFavorites($userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "select webtoon_idx,thumbnail,title,alarm,date_format(updated_at,'%y.%m.%d') as date
from FAVORITES
join WEBTOON ON WEBTOON.idx=FAVORITES.webtoon_idx
WHERE user_idx=? and FAVORITES.is_deleted='N';";

    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
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
function checkAlarm($webtoonIdx,$userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from FAVORITES where webtoon_idx = ? AND user_idx=? and alarm='Y')  exist;";

    $st = $pdo->prepare($query);
    $st->execute([$webtoonIdx,$userIdxInToken]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}
//READ
function alreadExistFavorites($webtoonIdx,$userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from FAVORITES where webtoon_idx = ? AND user_idx=?) exist;";

    $st = $pdo->prepare($query);
    $st->execute([$webtoonIdx,$userIdxInToken]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}
//READ
function alreadydeleteFavorites($webtoonIdx,$userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from FAVORITES where webtoon_idx = ? AND user_idx=? and is_deleted='Y') exist;";

    $st = $pdo->prepare($query);
    $st->execute([$webtoonIdx,$userIdxInToken]);
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
function checkLookDeleted($userIdxInToken,$episodeIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from LOOK where episode_idx = ? AND user_idx=? and is_deleted='Y') exist;";

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
function checkLook($userIdxInToken,$episodeIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from LOOK where episode_idx = ? AND user_idx=?) exist;";

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
function createLook($userIdxInToken,$episodeIdx)
{
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO `LOOK` (user_idx,episode_idx) VALUES (?,?);";
    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken,$episodeIdx]);
    $st = null;
    $pdo = null;
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
function createFavorites($webtoonIdx,$userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO `FAVORITES` (user_idx,webtoon_idx) VALUES (?,?);";
    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken,$webtoonIdx]);
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
function deleteFavorites($webtoonIdx,$userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "UPDATE `FAVORITES` SET is_deleted='Y' where user_idx=? and webtoon_idx=?;";
    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken,$webtoonIdx]);
    $st = null;
    $pdo = null;
}
function deleteAlarm($webtoonIdx,$userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "UPDATE `FAVORITES` SET alarm='N' where user_idx=? and webtoon_idx=?;";
    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken,$webtoonIdx]);
    $st = null;
    $pdo = null;
}
function updateLookdeletd($userIdxInToken,$episodeIdx)
{
    $pdo = pdoSqlConnect();
    $query = "UPDATE `LOOK` SET is_deleted='N',created_at=CURRENT_TIMESTAMP where user_idx=? and episode_idx=?;";
    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken,$episodeIdx]);
    $st = null;
    $pdo = null;
}
function updateLookTime($userIdxInToken,$episodeIdx)
{
    $pdo = pdoSqlConnect();
    $query = "UPDATE `LOOK` SET created_at=CURRENT_TIMESTAMP where user_idx=? and episode_idx=?;";
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
function updateFavorites($webtoonIdx,$userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "UPDATE `FAVORITES` SET is_deleted='N' where user_idx=? and webtoon_idx=?;";
    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken,$webtoonIdx]);
    $st = null;
    $pdo = null;
}
function updateAlarm($webtoonIdx,$userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "UPDATE `FAVORITES` SET alarm='Y' where user_idx=? and webtoon_idx=?;";
    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken,$webtoonIdx]);
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
