<?php

//READ
function mainscreenStar($keyword)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT idx,title,author,thumbnail,ifnull(starscore,0) as starscore,
 CASE WHEN TIMESTAMPDIFF(DAY,updated_at,now())<1 THEN 'up' ELSE 'down' END UP,form,rest
FROM WEBTOON
join DATE ON DATE.webtoon_idx=WEBTOON.idx
left JOIN (SELECT webtoon_idx,AVG(score) AS starscore FROM `STARRATING`) AS TEMP ON TEMP.webtoon_idx=WEBTOON.idx
WHERE days=? and is_completed='N'
order by starscore desc;";
    $st = $pdo->prepare($query);
   $st->execute([$keyword]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//READ
function mainscreenFemale($keyword)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT idx,title,author,thumbnail,ifnull(starscore,0) as starscore,updated_at,
 CASE WHEN TIMESTAMPDIFF(DAY,updated_at,now())<1 THEN 'up' ELSE 'down' END UP,form,rest
FROM WEBTOON
join DATE ON DATE.webtoon_idx=WEBTOON.idx
left JOIN (SELECT webtoon_idx,AVG(score) AS starscore FROM `STARRATING`) AS TEMP ON TEMP.webtoon_idx=WEBTOON.idx
left JOIN (select count(*) as count,webtoon_idx
FROM FAVORITES
join USER ON USER.idx=FAVORITES.user_idx
WHERE is_deleted='N' and gender='F'
group by webtoon_idx) as temp2 on temp2.webtoon_idx=WEBTOON.idx
WHERE days=? and is_completed='N'
order by count desc;";
    $st = $pdo->prepare($query);
    $st->execute([$keyword]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//READ
function mainscreenMale($keyword)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT idx,title,author,thumbnail,ifnull(starscore,0) as starscore,updated_at,
 CASE WHEN TIMESTAMPDIFF(DAY,updated_at,now())<1 THEN 'up' ELSE 'down' END UP,form,rest
FROM WEBTOON
join DATE ON DATE.webtoon_idx=WEBTOON.idx
left JOIN (SELECT webtoon_idx,AVG(score) AS starscore FROM `STARRATING`) AS TEMP ON TEMP.webtoon_idx=WEBTOON.idx
left JOIN (select count(*) as count,webtoon_idx
FROM FAVORITES
join USER ON USER.idx=FAVORITES.user_idx
WHERE is_deleted='N' and gender='M'
group by webtoon_idx) as temp2 on temp2.webtoon_idx=WEBTOON.idx
WHERE days=? and is_completed='N'
order by count desc;";
    $st = $pdo->prepare($query);
    $st->execute([$keyword]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//READ
function mainscreenUpdate($keyword)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT idx,title,author,thumbnail,ifnull(starscore,0) as starscore,updated_at,
 CASE WHEN TIMESTAMPDIFF(DAY,updated_at,now())<1 THEN 'up' ELSE 'down' END UP,form,rest
FROM WEBTOON
join DATE ON DATE.webtoon_idx=WEBTOON.idx
left JOIN (SELECT webtoon_idx,AVG(score) AS starscore FROM `STARRATING`) AS TEMP ON TEMP.webtoon_idx=WEBTOON.idx
WHERE days=? and is_completed='N'
order by updated_at desc;";
    $st = $pdo->prepare($query);
    $st->execute([$keyword]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//READ
function mainscreenLook($keyword)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT idx,title,author,thumbnail,ifnull(starscore,0) as starscore,updated_at,
 CASE WHEN TIMESTAMPDIFF(DAY,updated_at,now())<1 THEN 'up' ELSE 'down' END UP,form,rest
FROM WEBTOON
join DATE ON DATE.webtoon_idx=WEBTOON.idx
left JOIN (SELECT webtoon_idx,AVG(score) AS starscore FROM `STARRATING`) AS TEMP ON TEMP.webtoon_idx=WEBTOON.idx
left JOIN (select webtoon_idx,count(*) as count from `LOOK` join EPISODE ON EPISODE.idx=LOOK.episode_idx) as T on T.webtoon_idx=WEBTOON.idx
WHERE days=? and is_completed='N'
order by count desc;";
    $st = $pdo->prepare($query);
    $st->execute([$keyword]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//READ
function mainscreenFamous($keyword)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT idx,title,author,thumbnail,ifnull(starscore,0) as starscore,updated_at,
 CASE WHEN TIMESTAMPDIFF(DAY,updated_at,now())<1 THEN 'up' ELSE 'down' END UP,form,rest
FROM WEBTOON
join DATE ON DATE.webtoon_idx=WEBTOON.idx
left JOIN (SELECT webtoon_idx,AVG(score) AS starscore FROM `STARRATING`) AS TEMP ON TEMP.webtoon_idx=WEBTOON.idx
left JOIN (select count(*) as count,webtoon_idx
FROM FAVORITES
join USER ON USER.idx=FAVORITES.user_idx
WHERE is_deleted='N'
group by webtoon_idx) as temp2 on temp2.webtoon_idx=WEBTOON.idx
WHERE days=? and is_completed='N'
order by count desc;";
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
    $query = "SELECT idx,title,author,thumbnail,ifnull(starscore,0) as starscore,
CASE WHEN TIMESTAMPDIFF(DAY,updated_at,now())<1 THEN 'up' ELSE 'down' END UP,form,rest
FROM WEBTOON
left JOIN (SELECT webtoon_idx,AVG(score) AS starscore FROM `STARRATING`) AS TEMP ON TEMP.webtoon_idx=WEBTOON.idx
WHERE TIMESTAMPDIFF(MONTH,updated_at,NOW())<1 and is_completed='N'
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
function mainscreenNewF()
{
    $pdo = pdoSqlConnect();
    $query = "SELECT idx,title,author,thumbnail,ifnull(starscore,0) as starscore,
CASE WHEN TIMESTAMPDIFF(DAY,updated_at,now())<1 THEN 'up' ELSE 'down' END UP,form,rest
FROM WEBTOON
left JOIN (SELECT webtoon_idx,AVG(score) AS starscore FROM `STARRATING`) AS TEMP ON TEMP.webtoon_idx=WEBTOON.idx
left JOIN (select count(*) as count,webtoon_idx
FROM FAVORITES
join USER ON USER.idx=FAVORITES.user_idx
WHERE is_deleted='N' and gender='F'
group by webtoon_idx) as temp2 on temp2.webtoon_idx=WEBTOON.idx
WHERE TIMESTAMPDIFF(MONTH,updated_at,NOW())<1 and is_completed='N'
order by count desc;";
    $st = $pdo->prepare($query);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//READ
function mainscreenNewM()
{
    $pdo = pdoSqlConnect();
    $query = "SELECT idx,title,author,thumbnail,ifnull(starscore,0) as starscore,
CASE WHEN TIMESTAMPDIFF(DAY,updated_at,now())<1 THEN 'up' ELSE 'down' END UP,form,rest
FROM WEBTOON
left JOIN (SELECT webtoon_idx,AVG(score) AS starscore FROM `STARRATING`) AS TEMP ON TEMP.webtoon_idx=WEBTOON.idx
left JOIN (select count(*) as count,webtoon_idx
FROM FAVORITES
join USER ON USER.idx=FAVORITES.user_idx
WHERE is_deleted='N' and gender='M'
group by webtoon_idx) as temp2 on temp2.webtoon_idx=WEBTOON.idx
WHERE TIMESTAMPDIFF(MONTH,updated_at,NOW())<1 and is_completed='N'
order by count desc;";
    $st = $pdo->prepare($query);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//READ
function mainscreenNewL()
{
    $pdo = pdoSqlConnect();
    $query = "SELECT idx,title,author,thumbnail,ifnull(starscore,0) as starscore,
CASE WHEN TIMESTAMPDIFF(DAY,updated_at,now())<1 THEN 'up' ELSE 'down' END UP,form,rest
FROM WEBTOON
left JOIN (SELECT webtoon_idx,AVG(score) AS starscore FROM `STARRATING`) AS TEMP ON TEMP.webtoon_idx=WEBTOON.idx
left JOIN (select webtoon_idx,count(*) as count from `LOOK` join EPISODE ON EPISODE.idx=LOOK.episode_idx) as T on T.webtoon_idx=WEBTOON.idx
WHERE TIMESTAMPDIFF(MONTH,updated_at,NOW())<1 and is_completed='N'
order by count desc;";
    $st = $pdo->prepare($query);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//READ
function mainscreenNewU()
{
    $pdo = pdoSqlConnect();
    $query = "SELECT idx,title,author,thumbnail,ifnull(starscore,0) as starscore,
CASE WHEN TIMESTAMPDIFF(DAY,updated_at,now())<1 THEN 'up' ELSE 'down' END UP,form,rest
FROM WEBTOON
left JOIN (SELECT webtoon_idx,AVG(score) AS starscore FROM `STARRATING`) AS TEMP ON TEMP.webtoon_idx=WEBTOON.idx
WHERE TIMESTAMPDIFF(MONTH,updated_at,NOW())<1 and is_completed='N'
order by updated_at desc;";
    $st = $pdo->prepare($query);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//READ
function mainscreenNewP()
{
    $pdo = pdoSqlConnect();
    $query = "SELECT idx,title,author,thumbnail,ifnull(starscore,0) as starscore,
CASE WHEN TIMESTAMPDIFF(DAY,updated_at,now())<1 THEN 'up' ELSE 'down' END UP,form,rest
FROM WEBTOON
left JOIN (SELECT webtoon_idx,AVG(score) AS starscore FROM `STARRATING`) AS TEMP ON TEMP.webtoon_idx=WEBTOON.idx
left JOIN (select count(*) as count,webtoon_idx
FROM FAVORITES
join USER ON USER.idx=FAVORITES.user_idx
WHERE is_deleted='N'
group by webtoon_idx) as temp2 on temp2.webtoon_idx=WEBTOON.idx
WHERE TIMESTAMPDIFF(MONTH,updated_at,NOW())<1 and is_completed='N'
order by count desc;";
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
    $query = "SELECT idx,title,author,thumbnail,ifnull(starscore,0) as starscore,
CASE WHEN TIMESTAMPDIFF(DAY,updated_at,now())<1 THEN 'up' ELSE 'down' END UP,form,rest
FROM WEBTOON
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
function mainscreenCompleteF()
{
    $pdo = pdoSqlConnect();
    $query = "SELECT idx,title,author,thumbnail,ifnull(starscore,0) as starscore,
CASE WHEN TIMESTAMPDIFF(DAY,updated_at,now())<1 THEN 'up' ELSE 'down' END UP,form,rest
FROM WEBTOON
left JOIN (SELECT webtoon_idx,AVG(score) AS starscore FROM `STARRATING`) AS TEMP ON TEMP.webtoon_idx=WEBTOON.idx
left JOIN (select count(*) as count,webtoon_idx
FROM FAVORITES
join USER ON USER.idx=FAVORITES.user_idx
WHERE is_deleted='N' and gender='F'
group by webtoon_idx) as temp2 on temp2.webtoon_idx=WEBTOON.idx
WHERE is_completed='Y'
order by count desc;";
    $st = $pdo->prepare($query);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//READ
function mainscreenCompleteM()
{
    $pdo = pdoSqlConnect();
    $query = "SELECT idx,title,author,thumbnail,ifnull(starscore,0) as starscore,
CASE WHEN TIMESTAMPDIFF(DAY,updated_at,now())<1 THEN 'up' ELSE 'down' END UP,form,rest
FROM WEBTOON
left JOIN (SELECT webtoon_idx,AVG(score) AS starscore FROM `STARRATING`) AS TEMP ON TEMP.webtoon_idx=WEBTOON.idx
left JOIN (select count(*) as count,webtoon_idx
FROM FAVORITES
join USER ON USER.idx=FAVORITES.user_idx
WHERE is_deleted='N' and gender='M'
group by webtoon_idx) as temp2 on temp2.webtoon_idx=WEBTOON.idx
WHERE is_completed='Y'
order by count desc;";
    $st = $pdo->prepare($query);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//READ
function mainscreenCompleteP()
{
    $pdo = pdoSqlConnect();
    $query = "SELECT idx,title,author,thumbnail,ifnull(starscore,0) as starscore,
CASE WHEN TIMESTAMPDIFF(DAY,updated_at,now())<1 THEN 'up' ELSE 'down' END UP,form,rest
FROM WEBTOON
left JOIN (SELECT webtoon_idx,AVG(score) AS starscore FROM `STARRATING`) AS TEMP ON TEMP.webtoon_idx=WEBTOON.idx
left JOIN (select count(*) as count,webtoon_idx
FROM FAVORITES
join USER ON USER.idx=FAVORITES.user_idx
WHERE is_deleted='N'
group by webtoon_idx) as temp2 on temp2.webtoon_idx=WEBTOON.idx
WHERE is_completed='Y'
order by count desc;";
    $st = $pdo->prepare($query);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

//READ
function mainscreenCompleteL()
{
    $pdo = pdoSqlConnect();
    $query = "SELECT idx,title,author,thumbnail,ifnull(starscore,0) as starscore,
CASE WHEN TIMESTAMPDIFF(DAY,updated_at,now())<1 THEN 'up' ELSE 'down' END UP,form,rest
FROM WEBTOON
left JOIN (SELECT webtoon_idx,AVG(score) AS starscore FROM `STARRATING`) AS TEMP ON TEMP.webtoon_idx=WEBTOON.idx
left JOIN (select webtoon_idx,count(*) as count from `LOOK` join EPISODE ON EPISODE.idx=LOOK.episode_idx) as T on T.webtoon_idx=WEBTOON.idx
WHERE is_completed='Y'
order by count desc;";
    $st = $pdo->prepare($query);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//READ
function mainscreenCompleteU()
{
    $pdo = pdoSqlConnect();
    $query = "SELECT idx,title,author,thumbnail,ifnull(starscore,0) as starscore,
CASE WHEN TIMESTAMPDIFF(DAY,updated_at,now())<1 THEN 'up' ELSE 'down' END UP,form,rest
FROM WEBTOON
left JOIN (SELECT webtoon_idx,AVG(score) AS starscore FROM `STARRATING`) AS TEMP ON TEMP.webtoon_idx=WEBTOON.idx
WHERE is_completed='Y'
order by updated_at desc;";
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
function getKeepEpisode($userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "select KEEP.episode_idx,WEBTOON.title as title ,WEBTOON.thumbnail as thumbnail,concat(author,' 저') as author,concat(artist,' 그림') as artist,
       CASE WHEN DATEDIFF(T.finish_at,now())>0
                 THEN concat(TIMESTAMPDIFF(DAY,NOW(),T.finish_at),'일 ',TIMESTAMPDIFF(HOUR ,NOW(),T.finish_at)-TIMESTAMPDIFF(DAY,NOW(),T.finish_at)*24 ,'시간 남음 ')
                    ELSE '완료'
        END AS time
from KEEP
 join EPISODE ON EPISODE.idx=KEEP.episode_idx
 join WEBTOON ON WEBTOON.idx=EPISODE.webtoon_idx
 join TRANSACTION T on KEEP.episode_idx = T.episode_idx
where (webtoon_idx) in
(select webtoon_idx from KEEP left join EPISODE ON EPISODE.idx=KEEP.episode_idx
left join WEBTOON ON WEBTOON.idx=EPISODE.webtoon_idx group by webtoon_idx);";

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
function getLookEpisode($userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "select episode_idx,CONCAT(EPISODE.title,' 이어보기') AS episode ,WEBTOON.title as title ,WEBTOON.thumbnail as thumbnail,
       CASE WHEN DATEDIFF(now(),LOOK.created_at)<1
                 THEN '오늘'
                    ELSE CONCAT(DATEDIFF(now(),LOOK.created_at),'일전')
        END AS time,EPISODE.form as form
from LOOK
left join EPISODE ON EPISODE.idx=LOOK.episode_idx
left join WEBTOON ON WEBTOON.idx=EPISODE.webtoon_idx
where (webtoon_idx, LOOK.created_at) in
(select webtoon_idx,max(LOOK.created_at) from LOOK left join EPISODE ON EPISODE.idx=LOOK.episode_idx
left join WEBTOON ON WEBTOON.idx=EPISODE.webtoon_idx group by webtoon_idx);";

    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
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
function deleteFavorite($webtoonIdx,$userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "UPDATE `FAVORITES` SET is_deleted='Y' where user_idx=? and webtoon_idx=?;";
    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken,$webtoonIdx]);
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
function deleteFavorites($webtoonIdx,$userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "UPDATE `FAVORITES` SET is_deleted='Y' where user_idx=? and webtoon_idx=?;";
    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken,$webtoonIdx]);
    $st = null;
    $pdo = null;
}
function deleteLook($episodeIdx,$userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "UPDATE `LOOK`
SET is_deleted='Y'
where user_idx=? and episode_idx in(select idx from EPISODE where webtoon_idx =(select webtoon_idx from EPISODE WHERE idx=?));";
    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken,$episodeIdx]);
    $st = null;
    $pdo = null;
}
function deleteKeep($episodeIdx,$userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "UPDATE `KEEP`
SET is_deleted='Y'
where user_idx=? and episode_idx in(select idx from EPISODE where webtoon_idx =(select webtoon_idx from EPISODE WHERE idx=?));";
    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken,$episodeIdx]);
    $st = null;
    $pdo = null;
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
function getFavorites($userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "select webtoon_idx,thumbnail,title,alarm,date_format(updated_at,'%y.%m.%d') as date
from FAVORITES
join WEBTOON ON WEBTOON.idx=FAVORITES.webtoon_idx
WHERE user_idx=? and FAVORITES.is_deleted='N'
order by updated_at desc;";

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
function getFavoritesCount($userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "select count(*) as count
from FAVORITES
WHERE user_idx=? and FAVORITES.is_deleted='N';";

    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['count'];
}

//READ
function getLookCount($userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "select count(*) as count
from LOOK
left join EPISODE ON EPISODE.idx=LOOK.episode_idx
left join WEBTOON ON WEBTOON.idx=EPISODE.webtoon_idx
where (webtoon_idx, LOOK.created_at) in
(select webtoon_idx,max(LOOK.created_at) from LOOK left join EPISODE ON EPISODE.idx=LOOK.episode_idx
left join WEBTOON ON WEBTOON.idx=EPISODE.webtoon_idx group by webtoon_idx);";

    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['count'];
}
//READ
function getEpisodeUserList($webtoonIdx,$userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "select idx,thumbnail,title,CASE WHEN TIMESTAMPDIFF(MONTH,created_at,now())<1
                 THEN CONCAT(30-TIMESTAMPDIFF(DAY,created_at,now()),'일 후 무료')
                    ELSE date_format(created_at,'%y.%m.%d')
        END AS date,form,ifnull(starscore,0) as starscore,case when TIMESTAMPDIFF(MONTH,created_at,now())<1
THEN 'N'
ELSE 'Y' END AS type,CASE WHEN isnull(look) THEN 'N' ELSE 'Y' END look
from EPISODE
left JOIN (SELECT episode_idx,AVG(score) AS starscore FROM `STARRATING`) AS TEMP ON TEMP.episode_idx=EPISODE.idx
left JOIN(SELECT user_idx as look,episode_idx FROM LOOK WHERE user_idx=?) AS TEMP2 ON TEMP2.episode_idx=EPISODE.idx
WHERE EPISODE.webtoon_idx=?
order by created_at desc;";
    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken,$webtoonIdx]);
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

function updateFavorites($webtoonIdx,$userIdxInToken)
{
    $pdo = pdoSqlConnect();
    $query = "UPDATE `FAVORITES` SET is_deleted='N' where user_idx=? and webtoon_idx=?;";
    $st = $pdo->prepare($query);
    $st->execute([$userIdxInToken,$webtoonIdx]);
    $st = null;
    $pdo = null;
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
