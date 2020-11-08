<?php
require 'function.php';

const JWT_SECRET_KEY = "TEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEY";

$res = (object)array();
header('Content-Type: json');
$req = json_decode(file_get_contents("php://input"));
try {
    addAccessLogs($accessLogs, $req);
    switch ($handler) {
        case "index":
            echo "API Server";
            break;
        case "ACCESS_LOGS":
            //            header('content-type text/html charset=utf-8');
            header('Content-Type: text/html; charset=UTF-8');
            getLogs("./logs/access.log");
            break;
        case "ERROR_LOGS":
            //            header('content-type text/html charset=utf-8');
            header('Content-Type: text/html; charset=UTF-8');
            getLogs("./logs/errors.log");
            break;
        /*
           * API No. 1
           * API Name : mainscreen
           * 마지막 수정 날짜 : 20.11.04
           */
        case "main":
            http_response_code(200);
            $keyword=$_GET['keyword'];
            if(empty($keyword)){
                $res->is_success = FALSE;
                $res->code = 200;
                $res->message = "필요 정보 모두 입력하지 않았습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if($keyword==='월'||$keyword==='화'||$keyword==='수'||$keyword==='목'||$keyword==='금'||$keyword==='토'||$keyword==='일') {
                $res->result = mainscreen($keyword);
                $res->is_success = TRUE;
                $res->code = 100;
                $res->message = "메인페이지 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            else if($keyword==='신작'){
                $res->result = mainscreenNew();
                $res->is_success = TRUE;
                $res->code = 100;
                $res->message = "메인페이지 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }else if($keyword==='완결'){
                $res->result = mainscreenComplete();
                $res->is_success = TRUE;
                $res->code = 100;
                $res->message = "메인페이지 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            else{
                $res->is_success = FALSE;
                $res->code = 201;
                $res->message = "keyword로 보낸 정보 올바르지않습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
        /*
               * API No. 3
               * API Name : 웹툰 상세조회
               * 마지막 수정 날짜 : 20.11.08
               */
        case "getWebtoonDetail":
            http_response_code(200);
            $webtoonIdx=$vars['webtoonIdx'];
            if(!isValidWebtoonIdx($webtoonIdx)){
                $res->is_success = FALSE;
                $res->code = 300;
                $res->message = "유효하지않은 idx입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $res->result = getWebtoonDetail($webtoonIdx);
            $res->is_success = TRUE;
            $res->code = 100;
            $res->message = "웹툰 상세 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
                      * API No. 4
                      * API Name :웹툰 에피소드 목록 조회
                      * 마지막 수정 날짜 : 20.11.08
                      */
        case "getEpisodeList":
            http_response_code(200);
            $webtoonIdx=$vars['webtoonIdx'];
            if(!isValidWebtoonIdx($webtoonIdx)){
                $res->is_success = FALSE;
                $res->code = 300;
                $res->message = "유효하지않은 idx입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!ischeckWebtoon($webtoonIdx)){
                $res->is_success = FALSE;
                $res->code = 301;
                $res->message = "아직 에피소드 없습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $res->result =getEpisodeList($webtoonIdx);
            $res->is_success = TRUE;
            $res->code = 100;
            $res->message = "에피소드 목록 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
        * API No. 5
          * API Name :에피소드 만화 보기
         * 마지막 수정 날짜 : 20.11.08
       */
        case "getEpisode":
            http_response_code(200);
            $episodeIdx=$vars['episodeIdx'];
            if(!isValidEpisodeIdx($episodeIdx)){
                $res->is_success = FALSE;
                $res->code = 300;
                $res->message = "유효하지않은 idx입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!ischeckEpisode($episodeIdx)){
                $res->is_success = FALSE;
                $res->code = 301;
                $res->message = "아직 만화가 없습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $res->result =getEpisode($episodeIdx);
            $res->is_success = TRUE;
            $res->code = 100;
            $res->message = "에피소드 보기 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
                  * API No. 9
                  * API Name : 전체 댓글 /베스트 댓글조회
                  * 마지막 수정 날짜 : 20.11.08
     */
        case "getComments":
            http_response_code(200);
            $episodeIdx=$vars['episodeIdx'];
            $keyword=$_GET['keyword'];
            if(!isValidEpisodeIdx($episodeIdx)){
                $res->is_success = FALSE;
                $res->code = 300;
                $res->message = "유효하지않은 idx입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if($keyword==='베스트') {
                $res->result = getCommentsB($episodeIdx);
                $res->is_success = TRUE;
                $res->code = 100;
                $res->message = "댓글 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }else if($keyword==='전체'){
                $res->result = getComments($episodeIdx);
                $res->is_success = TRUE;
                $res->code = 100;
                $res->message = "댓글 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }else{
                $res->is_success = FALSE;
                $res->code = 201;
                $res->message = "keyword로 보낸 정보 올바르지않습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
