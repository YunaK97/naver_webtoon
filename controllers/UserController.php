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
               * API No. 8
               * API Name :회원만 에피소드 하트누르기
               * 마지막 수정 날짜 : 20.11.09
               */
        case "createHeart":
            http_response_code(200);
            $episodeIdx=$vars['episodeIdx'];
            $jwt=$_SERVER['HTTP_X_ACCESS_TOKEN'];
            $userIdxInToken=getDataByJWToken($jwt,JWT_SECRET_KEY)->userIdx;
            if(!isValidEpisodeIdx($episodeIdx)){
                $res->is_success = FALSE;
                $res->code = 300;
                $res->message = "유효하지않은 idx입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(alreadExistHeart($episodeIdx,$userIdxInToken)){
                if(alreadydeleteHeart($episodeIdx,$userIdxInToken)){
                    updateHeart($episodeIdx, $userIdxInToken);
                    $res->is_success = TRUE;
                    $res->code = 100;
                    $res->message = "유저 하트다시 누르기 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }else {
                    deleteHeart($episodeIdx, $userIdxInToken);
                    $res->is_success = TRUE;
                    $res->code = 100;
                    $res->message = "유저 하트취소 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
            }else{
                createHeart($episodeIdx,$userIdxInToken);
                $res->is_success = TRUE;
                $res->code = 100;
                $res->message = "유저 하트누르기 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
        /*
            * API No. 10
             * API Name :댓글 좋아요
            * 마지막 수정 날짜 : 20.11.09
         */
        case "createCommentsLike":
            http_response_code(200);
            $commentIdx=$vars['commentIdx'];
            $jwt=$_SERVER['HTTP_X_ACCESS_TOKEN'];
            $userIdxInToken=getDataByJWToken($jwt,JWT_SECRET_KEY)->userIdx;
            if(!isValidCommentIdx($commentIdx)){
                $res->is_success = FALSE;
                $res->code = 300;
                $res->message = "유효하지않은 idx입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(getCommentsUserIdx($commentIdx)==$userIdxInToken){
                $res->is_success = TRUE;
                $res->code = 200;
                $res->message = "자신의 글은 '좋아요'를 하실 수 없습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(alreadyExistCommentsState($commentIdx,$userIdxInToken)){
                if(alreadyCommentsDislike($commentIdx,$userIdxInToken)){
                    $res->is_success = TRUE;
                    $res->code = 101;
                    $res->message = "이미 '싫어요'를 누르셨습니다.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
                else if(alreadyCommentsNo($commentIdx,$userIdxInToken)){
                    updateCommentsLike($commentIdx,$userIdxInToken);
                    $res->is_success = TRUE;
                    $res->code = 100;
                    $res->message = "유저 댓글 좋아요 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
                else {
                    deleteLike($commentIdx, $userIdxInToken);
                    $res->is_success = TRUE;
                    $res->code = 102;
                    $res->message = "좋아요 취소";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
            }else {
                createCommentsLike($commentIdx, $userIdxInToken);
                $res->is_success = TRUE;
                $res->code = 100;
                $res->message = "유저 댓글 좋아요 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
        /*
               * API No. 11
                * API Name :댓글 싫어요
               * 마지막 수정 날짜 : 20.11.09
            */
        case "createCommentsDislike":
            http_response_code(200);
            $commentIdx=$vars['commentIdx'];
            $jwt=$_SERVER['HTTP_X_ACCESS_TOKEN'];
            $userIdxInToken=getDataByJWToken($jwt,JWT_SECRET_KEY)->userIdx;
            if(!isValidCommentIdx($commentIdx)){
                $res->is_success = FALSE;
                $res->code = 300;
                $res->message = "유효하지않은 idx입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(getCommentsUserIdx($commentIdx)==$userIdxInToken){
                $res->is_success = TRUE;
                $res->code = 200;
                $res->message = "자신의 글은 '싫어요'를 하실 수 없습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(alreadyExistCommentsState($commentIdx,$userIdxInToken)){
                if(alreadyCommentsDislike($commentIdx,$userIdxInToken)){
                    deleteDislike($commentIdx, $userIdxInToken);
                    $res->is_success = TRUE;
                    $res->code = 102;
                    $res->message = "싫어요 취소";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
                else if(alreadyCommentsNo($commentIdx,$userIdxInToken)){
                    updateCommentsDislike($commentIdx,$userIdxInToken);
                    $res->is_success = TRUE;
                    $res->code = 100;
                    $res->message = "유저 댓글 싫어요 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
                else {
                    $res->is_success = TRUE;
                    $res->code = 101;
                    $res->message = "이미 '좋아요'를 누르셨습니다.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
            }else{
                createCommentsDislike($commentIdx,$userIdxInToken);
                $res->is_success = TRUE;
                $res->code = 100;
                $res->message = "유저 댓글 싫어요 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
        /*
          * API No. 12
         * API Name :댓글 생성
       * 마지막 수정 날짜 : 20.11.09
       */
        case "createComment":
            http_response_code(200);
            $episodeIdx=$vars['episodeIdx'];
            $jwt=$_SERVER['HTTP_X_ACCESS_TOKEN'];
            $userIdxInToken=getDataByJWToken($jwt,JWT_SECRET_KEY)->userIdx;
            if(!isValidEpisodeIdx($episodeIdx)) {
                $res->is_success = FALSE;
                $res->code = 300;
                $res->message = "유효하지않은 idx입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $contents=$req->contents;
            $nick=getNick($userIdxInToken);
            if(strlen($contents)>=500){
                $res->is_success = FALSE;
                $res->code = 204;
                $res->message = "댓글이 500자가 넘습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(empty($contents)){
                $res->is_success = FALSE;
                 $res->code = 203;
                  $res->message = "필요 정보 모두 입력하지 않았습니다";
                 echo json_encode($res, JSON_NUMERIC_CHECK);
                 break;
            }
            createComments($nick,$userIdxInToken,$episodeIdx,$contents);
             $res->is_success = TRUE;
            $res->code = 100;
            $res->message = "유저 댓글 생성 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
                  * API No. 13
                 * API Name :별점 생성
               * 마지막 수정 날짜 : 20.11.09
               */
        case "createStar":
            http_response_code(200);
            $episodeIdx=$vars['episodeIdx'];
            $jwt=$_SERVER['HTTP_X_ACCESS_TOKEN'];
            $userIdxInToken=getDataByJWToken($jwt,JWT_SECRET_KEY)->userIdx;
            if(!isValidEpisodeIdx($episodeIdx)) {
                $res->is_success = FALSE;
                $res->code = 300;
                $res->message = "유효하지않은 idx입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(checkStar($userIdxInToken,$episodeIdx)){
                $res->is_success = FALSE;
                $res->code = 200;
                $res->message = "이미 별점 생성하였습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $webtoonIdx=getWebtoonIdx($episodeIdx);
            $score=$req->score;
            if(empty($score)){
                $res->is_success = FALSE;
                $res->code = 203;
                $res->message = "점수가 없습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(is_int($score)){
                if($score>=1&&$score<=10){
                    createStar($userIdxInToken,$episodeIdx,$score,$webtoonIdx);
                    $res->is_success = TRUE;
                    $res->code = 100;
                    $res->message = "유저 별점 생성 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }else{
                    $res->is_success = FALSE;
                    $res->code = 201;
                    $res->message = "점수는 1점이상 10점 이하까지만 가능";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
            }else{
                $res->is_success = FALSE;
                $res->code = 202;
                $res->message = "점수는 숫자 입력해야합니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
