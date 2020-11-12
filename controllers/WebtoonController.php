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
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            if(!isValidWebtoonIdx($webtoonIdx)){
            $res->is_success = FALSE;
            $res->code = 300;
            $res->message = "유효하지않은 idx입니다.";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
         }
            // 1) JWT 유효성 검사
        if(!empty($jwt)) {
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            else{
                $userIdxInToken=getDataByJWToken($jwt,JWT_SECRET_KEY)->userIdx;
                $res->result->detail= getWebtoonDetail($webtoonIdx);
                if(!ischeckWebtoon($webtoonIdx)){
                    $res->is_success = FALSE;
                    $res->code = 301;
                    $res->message = "아직 에피소드 없습니다.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
                $res->result->Episodelist =getEpisodeUserList($webtoonIdx,$userIdxInToken);
                $res->is_success = TRUE;
                $res->code = 100;
                $res->message = "웹툰 상세 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
        }else{
            $res->result->detail= getWebtoonDetail($webtoonIdx);
            if(!ischeckWebtoon($webtoonIdx)){
                $res->is_success = FALSE;
                $res->code = 301;
                $res->message = "아직 에피소드 없습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $res->result->Episodelist =getEpisodeList($webtoonIdx);
            $res->is_success = TRUE;
            $res->code = 100;
            $res->message = "웹툰 상세 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        }
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
        /*
                 * API No. 14
             * API Name : 관심 웹툰 목록 조회
              * 마지막 수정 날짜 : 20.11.10
                      */
        case "getFavorites":
            http_response_code(200);
            $jwt=$_SERVER['HTTP_X_ACCESS_TOKEN'];
            // 1) JWT 유효성 검사
            if(!empty($jwt)) {
                if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                    $res->isSuccess = FALSE;
                    $res->code = 202;
                    $res->message = "유효하지 않은 토큰입니다";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    addErrorLogs($errorLogs, $res, $req);
                    return;
                } else {
                    $userIdxInToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
                    $res->result->count=getFavoritesCount($userIdxInToken);
                   $res->result->list = getFavorites($userIdxInToken);
                    $res->is_success = TRUE;
                    $res->code = 100;
                    $res->message = "관심웹툰 조회 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
            }else{
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "토큰이 없습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
        /*
              * API No. 15
                * API Name : 관심 웹툰 등록
               * 마지막 수정 날짜 : 20.11.10
            */
        case "createFavorites":
            http_response_code(200);
            $jwt=$_SERVER['HTTP_X_ACCESS_TOKEN'];
            $userIdxInToken=getDataByJWToken($jwt,JWT_SECRET_KEY)->userIdx;
            $webtoonIdx=$vars['webtoonIdx'];
            if(!isValidWebtoonIdx($webtoonIdx)) {
                $res->is_success = FALSE;
                $res->code = 300;
                $res->message = "유효하지않은 idx입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            // 1) JWT 유효성 검사
            if(!empty($jwt)) {
                if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                    $res->isSuccess = FALSE;
                    $res->code = 202;
                    $res->message = "유효하지 않은 토큰입니다";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    addErrorLogs($errorLogs, $res, $req);
                    return;
                } else {
                    if (alreadExistFavorites($webtoonIdx, $userIdxInToken)) {
                        if (alreadydeleteFavorites($webtoonIdx, $userIdxInToken)) {
                            updateFavorites($webtoonIdx, $userIdxInToken);
                            $res->is_success = TRUE;
                            $res->code = 101;
                            $res->message = "유저 웹툰 관심 다시 누르기 성공";
                            echo json_encode($res, JSON_NUMERIC_CHECK);
                            break;
                        } else {
                            deleteFavorites($webtoonIdx, $userIdxInToken);
                            $res->is_success = TRUE;
                            $res->code = 102;
                            $res->message = "유저 관심 취소 성공";
                            echo json_encode($res, JSON_NUMERIC_CHECK);
                            break;
                        }
                    } else {
                        createFavorites($webtoonIdx, $userIdxInToken);
                        $res->is_success = TRUE;
                        $res->code = 100;
                        $res->message = "유저 웹툰 관심 등록 성공";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        break;
                    }
                }
            } else{
                    $res->isSuccess = FALSE;
                    $res->code = 400;
                    $res->message = "토큰이 없습니다.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    addErrorLogs($errorLogs, $res, $req);
                    return;
                }

        /*
                  * API No. 16
                    * API Name : 관심 웹툰 알람 등록
                   * 마지막 수정 날짜 : 20.11.10
                */
        case "updateAlarm":
            http_response_code(200);
            $jwt=$_SERVER['HTTP_X_ACCESS_TOKEN'];
            $webtoonIdx=$vars['webtoonIdx'];
            if(!isValidWebtoonIdx($webtoonIdx)) {
                $res->is_success = FALSE;
                $res->code = 300;
                $res->message = "유효하지않은 idx입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            // 1) JWT 유효성 검사
            if(!empty($jwt)) {
                if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                    $res->isSuccess = FALSE;
                    $res->code = 202;
                    $res->message = "유효하지 않은 토큰입니다";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    addErrorLogs($errorLogs, $res, $req);
                    return;
                } else {
                    $userIdxInToken=getDataByJWToken($jwt,JWT_SECRET_KEY)->userIdx;
                    if (alreadExistFavorites($webtoonIdx, $userIdxInToken)) {
                        if (alreadydeleteFavorites($webtoonIdx, $userIdxInToken)) {
                            $res->is_success = FALSE;
                            $res->code = 200;
                            $res->message = "관심등록 하지 않았습니다.";
                            echo json_encode($res, JSON_NUMERIC_CHECK);
                            break;
                        } else {
                            if (checkAlarm($webtoonIdx, $userIdxInToken)) {
                                deleteAlarm($webtoonIdx, $userIdxInToken);
                                $res->is_success = TRUE;
                                $res->code = 100;
                                $res->message = "알람 취소 성공";
                                echo json_encode($res, JSON_NUMERIC_CHECK);
                                break;
                            } else {
                                updateAlarm($webtoonIdx, $userIdxInToken);
                                $res->is_success = TRUE;
                                $res->code = 100;
                                $res->message = "알람 성공";
                                echo json_encode($res, JSON_NUMERIC_CHECK);
                                break;
                            }

                        }
                    } else {
                        $res->is_success = FALSE;
                        $res->code = 200;
                        $res->message = "관심등록 하지 않았습니다.";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        break;
                    }
                }
            }else{
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "토큰이 없습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
        /*
                 * API No. 17
               * API Name : 최근 본 웹툰 목록 조회
             * 마지막 수정 날짜 : 20.11.10
              */
        case "getLookEpisode":
            http_response_code(200);
            $jwt=$_SERVER['HTTP_X_ACCESS_TOKEN'];
            // 1) JWT 유효성 검사
            if(!empty($jwt)) {
                if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                    $res->isSuccess = FALSE;
                    $res->code = 202;
                    $res->message = "유효하지 않은 토큰입니다";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    addErrorLogs($errorLogs, $res, $req);
                    return;
                } else {
                    $userIdxInToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
                    $res->result->count=getLookCount($userIdxInToken);
                    $res->result->list = getLookEpisode($userIdxInToken);
                    $res->is_success = TRUE;
                    $res->code = 100;
                    $res->message = "최근 본 웹툰 조회 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
            }else{
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "토큰이 없습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
        /*
                 * API No. 18
               * API Name : 보관함 조회
             * 마지막 수정 날짜 : 20.11.12
              */
        case "getKeepEpisode":
            http_response_code(200);
            $jwt=$_SERVER['HTTP_X_ACCESS_TOKEN'];
            // 1) JWT 유효성 검사
            if(!empty($jwt)) {
                if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                    $res->isSuccess = FALSE;
                    $res->code = 202;
                    $res->message = "유효하지 않은 토큰입니다";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    addErrorLogs($errorLogs, $res, $req);
                    return;
                } else {
                    $userIdxInToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
                    $res->result = getKeepEpisode($userIdxInToken);
                    $res->is_success = TRUE;
                    $res->code = 100;
                    $res->message = "보관함 웹툰 조회 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
            }else{
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "토큰이 없습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
        /*
    * API No. 19
       * API Name : 관심웹툰 삭제
      * 마지막 수정 날짜 : 20.11.12
       */
        case "deleteFavorite":
            http_response_code(200);
            $jwt=$_SERVER['HTTP_X_ACCESS_TOKEN'];
            // 1) JWT 유효성 검사
            if(!empty($jwt)) {
                if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                    $res->isSuccess = FALSE;
                    $res->code = 202;
                    $res->message = "유효하지 않은 토큰입니다";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    addErrorLogs($errorLogs, $res, $req);
                    break;
                } else {
                    $webtoonIdx = explode(",", $_GET['webtoonIdx']);
                    $userIdxInToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
                    for ($i = 0; $i < count($webtoonIdx); $i++) {
                        if (!isValidWebtoonIdx($webtoonIdx[$i])) {
                            $res->is_success = FALSE;
                            $res->code = 300;
                            $res->message = "유효하지않은 idx입니다.";
                            echo json_encode($res, JSON_NUMERIC_CHECK);
                            return;
                        }
                    }
                    for ($i = 0; $i < count($webtoonIdx); $i++) {
                       deleteFavorite($webtoonIdx[$i],$userIdxInToken);
                    }
                    $res->is_success = TRUE;
                    $res->code = 100;
                    $res->message = "관심 웹툰 삭제 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
            }else{
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "토큰이 없습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
        /*
    * API No. 20
       * API Name : 최근본 웹툰 삭제
      * 마지막 수정 날짜 : 20.11.13
       */
        case "deleteLook":
            http_response_code(200);
            $jwt=$_SERVER['HTTP_X_ACCESS_TOKEN'];
            // 1) JWT 유효성 검사
            if(!empty($jwt)) {
                if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                    $res->isSuccess = FALSE;
                    $res->code = 202;
                    $res->message = "유효하지 않은 토큰입니다";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    addErrorLogs($errorLogs, $res, $req);
                    break;
                } else {
                    $episodeIdx = explode(",", $_GET['episodeIdx']);
                    $userIdxInToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
                    for ($i = 0; $i < count($episodeIdx); $i++) {
                        if (!isValidEpisodeIdx($episodeIdx[$i])) {
                            $res->is_success = FALSE;
                            $res->code = 300;
                            $res->message = "유효하지않은 idx입니다.";
                            echo json_encode($res, JSON_NUMERIC_CHECK);
                            return;
                        }
                    }
                    for ($i = 0; $i < count($episodeIdx); $i++) {
                        deleteLook($episodeIdx[$i],$userIdxInToken);
                    }
                    $res->is_success = TRUE;
                    $res->code = 100;
                    $res->message = "최근 본 웹툰 삭제 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
            }else{
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "토큰이 없습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
        /*
       * API No. 21
          * API Name : 보관함 웹툰 삭제
         * 마지막 수정 날짜 : 20.11.13
          */
        case "deleteKeep":
            http_response_code(200);
            $jwt=$_SERVER['HTTP_X_ACCESS_TOKEN'];
            // 1) JWT 유효성 검사
            if(!empty($jwt)) {
                if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                    $res->isSuccess = FALSE;
                    $res->code = 202;
                    $res->message = "유효하지 않은 토큰입니다";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    addErrorLogs($errorLogs, $res, $req);
                    break;
                } else {
                    $episodeIdx = explode(",", $_GET['episodeIdx']);
                    $userIdxInToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
                    for ($i = 0; $i < count($episodeIdx); $i++) {
                        if (!isValidEpisodeIdx($episodeIdx[$i])) {
                            $res->is_success = FALSE;
                            $res->code = 300;
                            $res->message = "유효하지않은 idx입니다.";
                            echo json_encode($res, JSON_NUMERIC_CHECK);
                            return;
                        }
                    }
                    for ($i = 0; $i < count($episodeIdx); $i++) {
                        deleteKeep($episodeIdx[$i],$userIdxInToken);
                    }
                    $res->is_success = TRUE;
                    $res->code = 100;
                    $res->message = "보관함 웹툰 삭제 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
            }else{
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "토큰이 없습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
