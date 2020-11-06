<?php

//READ
function search($keyword)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT idx,title,author,thumbnail,ifnull(starscore,0) as starscore
FROM WEBTOON
left JOIN (SELECT webtoon_idx,AVG(score) AS starscore FROM `STARRATING`) AS TEMP ON TEMP.webtoon_idx=WEBTOON.idx
WHERE author LIKE concat('%',?,'%') OR title LIKE concat('%',?,'%')
ORDER BY (CASE WHEN binary(title)='?' THEN 1
            WHEN binary(title)=concat(?,'%') THEN 2
             WHEN binary(title)=concat('%',?,'%') THEN 3
            WHEN binary(title)=concat('%',?) THEN 4
          WHEN binary(author)='?' THEN 5
            WHEN binary(author)=concat(?,'%') THEN 6
             WHEN binary(author)=concat('%',?,'%') THEN 7
            WHEN binary(author)=concat('%',?) THEN 8
            else 9
    end);";

    $st = $pdo->prepare($query);
    $st->execute([$keyword]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
