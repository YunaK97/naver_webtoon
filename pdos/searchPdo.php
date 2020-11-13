<?php

//READ
function search($keyword)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT idx,title,author,thumbnail,ifnull(starscore,0) as starscore
FROM WEBTOON
left JOIN (SELECT webtoon_idx,AVG(score) AS starscore FROM `STARRATING`) AS TEMP ON TEMP.webtoon_idx=WEBTOON.idx
WHERE title =?
UNION
SELECT idx,title,author,thumbnail,ifnull(starscore,0) as starscore
FROM WEBTOON
left JOIN (SELECT webtoon_idx,AVG(score) AS starscore FROM `STARRATING`) AS TEMP ON TEMP.webtoon_idx=WEBTOON.idx
WHERE title LIKE CONCAT(?,'%')
UNION
SELECT idx,title,author,thumbnail,ifnull(starscore,0) as starscore
FROM WEBTOON
left JOIN (SELECT webtoon_idx,AVG(score) AS starscore FROM `STARRATING`) AS TEMP ON TEMP.webtoon_idx=WEBTOON.idx
WHERE title LIKE CONCAT('%',?,'%')
UNION
SELECT idx,title,author,thumbnail,ifnull(starscore,0) as starscore
FROM WEBTOON
left JOIN (SELECT webtoon_idx,AVG(score) AS starscore FROM `STARRATING`) AS TEMP ON TEMP.webtoon_idx=WEBTOON.idx
WHERE title LIKE CONCAT('%',?)
UNION
SELECT idx,title,author,thumbnail,ifnull(starscore,0) as starscore
FROM WEBTOON
left JOIN (SELECT webtoon_idx,AVG(score) AS starscore FROM `STARRATING`) AS TEMP ON TEMP.webtoon_idx=WEBTOON.idx
WHERE author =?
UNION
SELECT idx,title,author,thumbnail,ifnull(starscore,0) as starscore
FROM WEBTOON
left JOIN (SELECT webtoon_idx,AVG(score) AS starscore FROM `STARRATING`) AS TEMP ON TEMP.webtoon_idx=WEBTOON.idx
WHERE author LIKE CONCAT(?,'%')
UNION
SELECT idx,title,author,thumbnail,ifnull(starscore,0) as starscore
FROM WEBTOON
left JOIN (SELECT webtoon_idx,AVG(score) AS starscore FROM `STARRATING`) AS TEMP ON TEMP.webtoon_idx=WEBTOON.idx
WHERE author LIKE CONCAT('%',?,'%')
UNION
SELECT idx,title,author,thumbnail,ifnull(starscore,0) as starscore
FROM WEBTOON
left JOIN (SELECT webtoon_idx,AVG(score) AS starscore FROM `STARRATING`) AS TEMP ON TEMP.webtoon_idx=WEBTOON.idx
WHERE author LIKE CONCAT('%',?);";

    $st = $pdo->prepare($query);
    $st->execute([$keyword,$keyword,$keyword,$keyword,$keyword,$keyword,$keyword,$keyword]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
function searchfive($keyword,$size)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT idx,title,author,thumbnail,ifnull(starscore,0) as starscore
from WEBTOON
left JOIN (SELECT webtoon_idx,AVG(score) AS starscore FROM `STARRATING`) AS TEMP ON TEMP.webtoon_idx=WEBTOON.idx
where WEBTOON.title LIKE CONCAT('%',?,'%') or WEBTOON.author LIKE CONCAT('%',?,'%') limit $size;";

    $st = $pdo->prepare($query);
    $st->execute([$keyword,$keyword]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
function getKeywordcount($keyword){
    $pdo = pdoSqlConnect();
    $query ="select count(*) as count
from WEBTOON
where WEBTOON.title LIKE CONCAT('%',?,'%') or WEBTOON.author LIKE CONCAT('%',?,'%');";
    $st = $pdo->prepare($query);
    $st->execute([$keyword,$keyword]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['count'];
}
