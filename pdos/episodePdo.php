<?php
//READ
function getEpisode($episodeIdx,$first,$size)
{
    $pdo = pdoSqlConnect();
    $query = "select idx,contents from CARTOONS WHERE episode_idx='$episodeIdx' order by idx limit $first,$size;";
    $st = $pdo->prepare($query);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//READ
function getEpiosdeDetails($episodeIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select title,comment_count,heart_count
from EPISODE
left JOIN (SELECT episode_idx,COUNT(*) AS comment_count FROM `COMMENTS` group by episode_idx) AS TEMP ON TEMP.episode_idx=EPISODE.idx
left join (SELECT episode_idx,count(*) as heart_count FROM `HEART` group by episode_idx) AS TEMP1 ON TEMP1.episode_idx=EPISODE.idx
WHERE idx=?;";
    $st = $pdo->prepare($query);
    $st->execute([$episodeIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
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
    $query = "select cookie_count FROM COOKIE WHERE user_idx=?;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['cookie_count'];
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
function getComments($episodeIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select nick,date_format(created_at,'%Y-%m-%d %H:%i') as date,contents,ifnull(like_count,0) as like_count,ifnull(dislike_count,0) as dislike_count
from COMMENTS
left JOIN (SELECT comment_idx,COUNT(*) AS like_count FROM `LIKECOMMENTS` where status='L' group by comment_idx) AS TEMP ON TEMP.comment_idx=COMMENTS.idx
left JOIN (SELECT comment_idx,COUNT(*) AS dislike_count FROM `LIKECOMMENTS` where status='D' group by comment_idx) AS TEMP2 ON TEMP2.comment_idx=COMMENTS.idx
WHERE episode_idx=? and is_deleted='N'
order by created_at;";
    $st = $pdo->prepare($query);
    $st->execute([$episodeIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//READ
function getCommentsB($episodeIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select nick,date_format(created_at,'%Y-%m-%d %H:%i') as date,contents,ifnull(like_count,0) as like_count,ifnull(dislike_count,0) as dislike_count
from COMMENTS
left JOIN (SELECT comment_idx,COUNT(*) AS like_count FROM `LIKECOMMENTS` where status='L' group by comment_idx) AS TEMP ON TEMP.comment_idx=COMMENTS.idx
left JOIN (SELECT comment_idx,COUNT(*) AS dislike_count FROM `LIKECOMMENTS` where status='D' group by comment_idx) AS TEMP2 ON TEMP2.comment_idx=COMMENTS.idx
WHERE episode_idx=? and is_deleted='N'
order by like_count desc limit 15;";
    $st = $pdo->prepare($query);
    $st->execute([$episodeIdx]);
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
function checkkeepDeleted($userIdxInToken,$episodeIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from KEEP where episode_idx = ? AND user_idx=? and is_deleted='Y') exist;";

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
function checkKeep($userIdxInToken,$episodeIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from KEEP where episode_idx = ? AND user_idx=?) exist;";

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
//READ
function checkCommentsTime($userIdxInToken,$episodeIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from COMMENTS where episode_idx = ? AND user_idx=? AND TIMESTAMPDIFF(MINUTE,created_at,NOW())<3) exist;";

    $st = $pdo->prepare($query);
    $st->execute([$episodeIdx,$userIdxInToken]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}
function createKeep($userIdxInToken,$episodeIdx)
{
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO `KEEP` (user_idx,episode_idx) VALUES (?,?);";
    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken,$episodeIdx]);
    $st = null;
    $pdo = null;
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

function updatekeepdeletd($userIdxInToken,$episodeIdx)
{
    $pdo = pdoSqlConnect();
    $query = "UPDATE `KEEP` SET is_deleted='N',created_at=CURRENT_TIMESTAMP where user_idx=? and episode_idx=?;";
    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken,$episodeIdx]);
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

function updateAlarm($webtoonIdx,$userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "UPDATE `FAVORITES` SET alarm='Y' where user_idx=? and webtoon_idx=?;";
    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken,$webtoonIdx]);
    $st = null;
    $pdo = null;
}
function updateTransaction($userIdxInToken, $episodeIdx)
{
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO TRANSACTION (user_idx, episode_idx, finish_at) VALUES (?,?,DATE_ADD(now(),interval 3 DAY));";
    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken, $episodeIdx]);
    $st = null;
    $pdo = null;
}
function updateCookie($cookie,$userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "update COOKIE set cookie_count=cookie_count-? where user_idx=?;";
    $st = $pdo->prepare($query);
    $st->execute([$cookie,$userIdxInToken]);
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
