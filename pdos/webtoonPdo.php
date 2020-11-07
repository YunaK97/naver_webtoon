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
    END days,ifnull(favorite_count,0) as favorite_count
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
