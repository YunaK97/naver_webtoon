<?php

//READ
function mainscreen($keyword)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT idx,title,author,thumbnail,ifnull(starscore,0) as starscore
                FROM WEBTOON
                join DATE ON DATE.webtoon_idx=WEBTOON.idx
                left JOIN (SELECT webtoon_idx,AVG(score) AS starscore FROM `STARRATING`) AS TEMP ON TEMP.webtoon_idx=WEBTOON.idx
                WHERE days=? and is_completed='N'
                order by starscore desc";
    $st = $pdo->prepare($query);
   $st->execute([$keyword]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//READ
function mainscreenNew()
{
    $pdo = pdoSqlConnect();
    $query = "SELECT idx,title,author,thumbnail,ifnull(starscore,0) as starscore
        FROM WEBTOON
        left JOIN (SELECT webtoon_idx,AVG(score) AS starscore FROM `STARRATING`) AS TEMP ON TEMP.webtoon_idx=WEBTOON.idx
        WHERE TIMESTAMPDIFF(MONTH,updated_at,NOW())<1 AND is_completed='N'
        order by starscore desc;";
    $st = $pdo->prepare($query);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//READ
function mainscreenComplete()
{
    $pdo = pdoSqlConnect();
    $query = "SELECT idx,title,author,thumbnail,ifnull(starscore,0) as starscore
                FROM WEBTOON
                join DATE ON DATE.webtoon_idx=WEBTOON.idx
                left JOIN (SELECT webtoon_idx,AVG(score) AS starscore FROM `STARRATING`) AS TEMP ON TEMP.webtoon_idx=WEBTOON.idx
                WHERE is_completed='Y'
                order by starscore desc;";
    $st = $pdo->prepare($query);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//READ
function getWebtoonDetail($webtoonIdx)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT idx,title,author,details,profile,CASE WHEN daycount=1 THEN CONCAT(days,'요웹툰')
    WHEN daycount>=2 then CONCAT(group_concat(days separator ','),' 연재')
    else 0
    END days,ifnull(favorite_count,0) as favorite_count,color
FROM WEBTOON
left JOIN (SELECT webtoon_idx,COUNT(*) AS daycount FROM `DATE` group by webtoon_idx) as TEMP ON TEMP.webtoon_idx=WEBTOON.idx
left join DATE ON DATE.webtoon_idx=WEBTOON.idx
left join(SELECT webtoon_idx,COUNT(*) AS favorite_count FROM `FAVORITES` group by webtoon_idx) AS TEMP2 ON TEMP2.webtoon_idx=WEBTOON.idx
WHERE idx=?;";
    $st = $pdo->prepare($query);
    $st->execute([$webtoonIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}
//READ
function getEpisodeList($webtoonIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select idx,thumbnail,title,CASE WHEN TIMESTAMPDIFF(MONTH,created_at,now())<1
                 THEN CONCAT(30-TIMESTAMPDIFF(DAY,created_at,now()),'일 후 무료')
                    ELSE date_format(created_at,'%y.%m.%d')
        END AS date,form,ifnull(starscore,0) as starscore,case when TIMESTAMPDIFF(MONTH,created_at,now())<1
THEN 'N'
ELSE 'Y' END AS type
from EPISODE
left JOIN (SELECT episode_idx,AVG(score) AS starscore FROM `STARRATING`) AS TEMP ON TEMP.episode_idx=EPISODE.idx
WHERE EPISODE.webtoon_idx=?
order by created_at desc;";
    $st = $pdo->prepare($query);
    $st->execute([$webtoonIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//READ
function getEpisode($episodeIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select idx,contents from CARTOONS WHERE episode_idx=? order by idx;";
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
function isValidWebtoonIdx($webtoonIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from WEBTOON where idx = ?) exist;";

    $st = $pdo->prepare($query);
    $st->execute([$webtoonIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}
//READ
function isValidEpisodeIdx($episodeIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from EPISODE where idx = ?) exist;";

    $st = $pdo->prepare($query);
    $st->execute([$episodeIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}
//READ
function ischeckWebtoon($webtoonIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from EPISODE where webtoon_idx = ?) exist;";

    $st = $pdo->prepare($query);
    $st->execute([$webtoonIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}
//READ
function ischeckEpisode($episodeIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from CARTOONS where episode_idx = ?) exist;";

    $st = $pdo->prepare($query);
    $st->execute([$episodeIdx]);
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
