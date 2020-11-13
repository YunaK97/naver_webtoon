<?php
//READ
function banner($size)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT banner_photo,webtoon_idx,ifnull(episode_idx,0) as episode_idx
                FROM BANNER where is_deleted='N' order by rand() limit " .$size;

    $st = $pdo->prepare($query);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//READ
function getUsers()
{
    $pdo = pdoSqlConnect();
    $query = "select * from USER;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

//READ
function getUserDetail($userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select * from Users where userIdx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx]);
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
function isValidUserIdx($userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from Users where userIdx = ?) exist;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}


function createUser($nickname, $naver_id, $gender)
{
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO USER (nick,naver_idx,gender) VALUES (?,?,?);";
    $st = $pdo->prepare($query);
    $st->execute([$nickname,$naver_id,$gender]);

    $st = null;
    $pdo = null;
}
function createCookie($userid)
{
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO COOKIE (user_idx,cookie_count) VALUES (?,0);";

    $st = $pdo->prepare($query);
    $st->execute([$userid]);

    $st = null;
    $pdo = null;
}

//READ
function bannersize()
{
    $pdo = pdoSqlConnect();
    $query = "select COUNT(*) AS count FROM BANNER where is_deleted='N';";

    $st = $pdo->prepare($query);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['count'];
}
//READ
function checkNaverid($naver_id)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from USER where naver_idx = ?) exist;";

    $st = $pdo->prepare($query);
    $st->execute([$naver_id]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}
function getUserIdx($naver_id)
{
    $pdo = pdoSqlConnect();
    $query = "select idx from USER WHERE naver_idx=?;";
    $st = $pdo->prepare($query);
    $st->execute([$naver_id]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['idx'];
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
