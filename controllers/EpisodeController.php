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
               * API No. 4
              * API Name :에피소드 만화 보기
               * 마지막 수정 날짜 : 20.11.12
                     */
        case "getEpisode":
            http_response_code(200);
            $episodeIdx=$vars['episodeIdx'];
            $jwt=$_SERVER['HTTP_X_ACCESS_TOKEN'];
            $page=$_GET['page'];
            $size=$_GET['size'];
            $first=$page-1;
            $first=$first*$size;
            if(empty($page)||empty($size)) {
                $res->is_success = FALSE;
                $res->code = 201;
                $res->message = "page 또는 size가 비어있습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!is_numeric($page)||!is_numeric($size)){
                $res->is_success = FALSE;
                $res->code = 203;
                $res->message = "page 또는 size 타입이 틀렸습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
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
                    $res->result->episode =getEpisode($episodeIdx,$first,$size);
                    $res->result->detail=getEpiosdeDetails($episodeIdx);
                    if(checkLook($userIdxInToken,$episodeIdx)){
                        if(checkLookDeleted($userIdxInToken,$episodeIdx)){
                            updateLookdeletd($userIdxInToken,$episodeIdx);
                        }else{
                            updateLookTime($userIdxInToken,$episodeIdx);
                        }
                    }else{
                        createLook($userIdxInToken,$episodeIdx);
                    }
                    $res->is_success = TRUE;
                    $res->code = 100;
                    $res->message = "에피소드 보기 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
            }else {
                $res->result->episode= getEpisode($episodeIdx,$first,$size);
                $res->result->detail=getEpiosdeDetails($episodeIdx);
                $res->is_success = TRUE;
                $res->code = 100;
                $res->message = "에피소드 보기 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
        /*
          * API No. 5
         * API Name : 미리보기 정보 조회
       * 마지막 수정 날짜 : 20.11.10
       */
        case "getPreviewDetails":
            http_response_code(200);
            $episodeIdx=$vars['episodeIdx'];
            $jwt=$_SERVER['HTTP_X_ACCESS_TOKEN'];
            if(!isValidEpisodeIdx($episodeIdx)) {
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
                    $res->result->myCookie=getCookie($userIdxInToken);
                    $res->result->preview=getPreviewDetails($episodeIdx);
                    $res->is_success = TRUE;
                    $res->code = 100;
                    $res->message = "미리보기 정보 조회 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
            }else{
                $res->isSuccess = FALSE;
                $res->code = 203;
                $res->message = "토큰이 없습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
        /*
           * API No. 6
        * API Name : 미리보기 확인버튼 1. 결제 2. 보관함 추가
       * 마지막 수정 날짜 : 20.11.12
        */
        case "createPreview":
            http_response_code(200);
            $episodeIdx=$vars['episodeIdx'];
            $jwt=$_SERVER['HTTP_X_ACCESS_TOKEN'];
            $cookie=$req->cookie;
            if(empty($cookie)||!is_numeric($cookie)){
                $res->is_success = FALSE;
                $res->code = 301;
                $res->message = "쿠키를 보내지 않거나 형식 잘못됨.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!isValidEpisodeIdx($episodeIdx)) {
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
                    if((getCookie($userIdxInToken)-$cookie)<0){
                        $res->isSuccess = FALSE;
                        $res->code = 400;
                        $res->message = "쿠키부족 결제 불가";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        addErrorLogs($errorLogs, $res, $req);
                        return;
                    }else {
                        if(checkLook($userIdxInToken,$episodeIdx)) {
                            if (checkLookDeleted($userIdxInToken, $episodeIdx)) {
                                updateLookdeletd($userIdxInToken, $episodeIdx);
                            } else {
                                updateLookTime($userIdxInToken, $episodeIdx);
                            }
                        }
                        else {
                            createLook($userIdxInToken, $episodeIdx);
                        }
                        if(checkKeep($userIdxInToken,$episodeIdx)) {
                            if (checkkeepDeleted($userIdxInToken, $episodeIdx)) {
                                updatekeepdeletd($userIdxInToken, $episodeIdx);
                            } else {
                                $res->isSuccess = FALSE;
                                $res->code = 302;
                                $res->message = "이미 구매한 상품입니다.";
                                echo json_encode($res, JSON_NUMERIC_CHECK);
                                addErrorLogs($errorLogs, $res, $req);
                                return;
                            }
                        }
                        else {
                            createKeep($userIdxInToken, $episodeIdx);
                        }
                        updateCookie($cookie, $userIdxInToken);
                        updateTransaction($userIdxInToken, $episodeIdx);
                        $res->is_success = TRUE;
                        $res->code = 100;
                        $res->message = "미리보기 결제 성공";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        break;
                    }
                }
            }else{
                $res->isSuccess = FALSE;
                $res->code = 203;
                $res->message = "토큰이 없습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
        /*
               * API No. 7
               * API Name :에피소드 하트누르기
               * 마지막 수정 날짜 : 20.11.09
               */
        case "createHeart":
            http_response_code(200);
            $episodeIdx=$vars['episodeIdx'];
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
                    if (!isValidEpisodeIdx($episodeIdx)) {
                        $res->is_success = FALSE;
                        $res->code = 300;
                        $res->message = "유효하지않은 idx입니다.";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        break;
                    }
                    if (alreadExistHeart($episodeIdx, $userIdxInToken)) {
                        if (alreadydeleteHeart($episodeIdx, $userIdxInToken)) {
                            updateHeart($episodeIdx, $userIdxInToken);
                            $res->is_success = TRUE;
                            $res->code = 100;
                            $res->message = "유저 하트다시 누르기 성공";
                            echo json_encode($res, JSON_NUMERIC_CHECK);
                            break;
                        } else {
                            deleteHeart($episodeIdx, $userIdxInToken);
                            $res->is_success = TRUE;
                            $res->code = 100;
                            $res->message = "유저 하트취소 성공";
                            echo json_encode($res, JSON_NUMERIC_CHECK);
                            break;
                        }
                    } else {
                        createHeart($episodeIdx, $userIdxInToken);
                        $res->is_success = TRUE;
                        $res->code = 100;
                        $res->message = "유저 하트누르기 성공";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        break;
                    }
                }
            }else{
        $res->isSuccess = FALSE;
        $res->code = 203;
        $res->message = "토큰이 없습니다.";
        echo json_encode($res, JSON_NUMERIC_CHECK);
        addErrorLogs($errorLogs, $res, $req);
        return;
        }
        /*
                * API No. 8
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
          * API No. 10
         * API Name :댓글 생성
       * 마지막 수정 날짜 : 20.11.09
       */
        case "createComment":
            http_response_code(200);
            $episodeIdx=$vars['episodeIdx'];
            $jwt=$_SERVER['HTTP_X_ACCESS_TOKEN'];

            if(!isValidEpisodeIdx($episodeIdx)) {
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
                    $userIdxInToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
                    $contents = $req->contents;
                    $nick = getNick($userIdxInToken);
                    if (strlen($contents) >= 500) {
                        $res->is_success = FALSE;
                        $res->code = 204;
                        $res->message = "댓글이 500자가 넘습니다";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        break;
                    }
                    if (empty($contents)) {
                        $res->is_success = FALSE;
                        $res->code = 205;
                        $res->message = "필요 정보 모두 입력하지 않았습니다";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        break;
                    }
                    if(checkCommentsTime($userIdxInToken, $episodeIdx)){
                        $res->is_success = FALSE;
                        $res->code = 206;
                        $res->message = "도배 금지";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        break;
                    }
                    createComments($nick, $userIdxInToken, $episodeIdx, $contents);
                    $res->is_success = TRUE;
                    $res->code = 100;
                    $res->message = "유저 댓글 생성 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
            }else{
                $res->isSuccess = FALSE;
                $res->code = 203;
                $res->message = "토큰이 없습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

        /*
                  * API No. 11
                 * API Name :별점 생성
               * 마지막 수정 날짜 : 20.11.09
               */
        case "createStar":
            http_response_code(200);
            $episodeIdx=$vars['episodeIdx'];
            $jwt=$_SERVER['HTTP_X_ACCESS_TOKEN'];
            if(!isValidEpisodeIdx($episodeIdx)) {
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
                    $res->code = 401;
                    $res->message = "유효하지 않은 토큰입니다";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    addErrorLogs($errorLogs, $res, $req);
                    return;
                } else {
                    $userIdxInToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

                    if (checkStar($userIdxInToken, $episodeIdx)) {
                        $res->is_success = FALSE;
                        $res->code = 200;
                        $res->message = "이미 별점 생성하였습니다.";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        break;
                    }
                    $webtoonIdx = getWebtoonIdx($episodeIdx);
                    $score = $req->score;
                    if (empty($score)) {
                        $res->is_success = FALSE;
                        $res->code = 203;
                        $res->message = "점수가 없습니다.";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        break;
                    }
                    if (is_int($score)) {
                        if ($score >= 1 && $score <= 10) {
                            createStar($userIdxInToken, $episodeIdx, $score, $webtoonIdx);
                            $res->is_success = TRUE;
                            $res->code = 100;
                            $res->message = "유저 별점 생성 성공";
                            echo json_encode($res, JSON_NUMERIC_CHECK);
                            break;
                        } else {
                            $res->is_success = FALSE;
                            $res->code = 201;
                            $res->message = "점수는 1점이상 10점 이하까지만 가능";
                            echo json_encode($res, JSON_NUMERIC_CHECK);
                            break;
                        }
                    } else {
                        $res->is_success = FALSE;
                        $res->code = 202;
                        $res->message = "점수는 숫자 입력해야합니다.";
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
              * API No. 14
              * API Name : 더보기란
              * 마지막 수정 날짜 : 20.11.09
              */
        case "getMore":
            http_response_code(200);
            $jwt=$_SERVER['HTTP_X_ACCESS_TOKEN'];
            $userIdxInToken=getDataByJWToken($jwt,JWT_SECRET_KEY)->userIdx;
            $res->result = getMore($userIdxInToken);
            $res->is_success = TRUE;
            $res->code = 100;
            $res->message = "더보기 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;



    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
