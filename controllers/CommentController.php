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
            * API No. 9
             * API Name :댓글 좋아요/싫어요
            * 마지막 수정 날짜 : 20.11.12
       */
        case "createCommentState":
            http_response_code(200);
            $commentIdx=$vars['commentIdx'];
            $jwt=$_SERVER['HTTP_X_ACCESS_TOKEN'];
            $userIdxInToken=getDataByJWToken($jwt,JWT_SECRET_KEY)->userIdx;
            $state=$req->state;
            if($state==='L'){
                $word='좋아요';
            }else if($state==='D'){
                $word='싫어요';
            }else{
                $res->is_success = FALSE;
                $res->code = 103;
                $res->message = "state가 없거나 값이 틀렸습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
             }
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
                $res->message = "자신의 글은 '$word'를 하실 수 없습니다.";
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
                    if(alreadyExistCommentsState($commentIdx, $userIdxInToken)) {
                        if(getCommentsState($commentIdx, $userIdxInToken)=='N'){
                            updateCommentsState($commentIdx, $userIdxInToken,$state);
                            $res->is_success = TRUE;
                            $res->code = 100;
                            $res->message = "유저 댓글 '$word' 성공";
                            echo json_encode($res, JSON_NUMERIC_CHECK);
                            break;
                        }
                        else if (getCommentsState($commentIdx, $userIdxInToken)!=$state) {
                            if(getCommentsState($commentIdx, $userIdxInToken)==='L')
                                $word1='좋아요';
                            else
                                $word1='싫어요';
                            $res->is_success = FALSE;
                            $res->code = 101;
                            $res->message = "이미 '$word1'를 누르셨습니다.";
                            echo json_encode($res, JSON_NUMERIC_CHECK);
                            break;
                         }
                        else if (getCommentsState($commentIdx, $userIdxInToken)==$state) {
                            deleteState($commentIdx, $userIdxInToken);
                            $res->is_success = TRUE;
                            $res->code = 102;
                            $res->message = "'$word' 취소";
                            echo json_encode($res, JSON_NUMERIC_CHECK);
                            break;
                        } else {
                            $res->is_success = FALSE;
                            $res->code = 101;
                            $res->message = "오류";
                            echo json_encode($res, JSON_NUMERIC_CHECK);
                            break;
                        }
                    } else {
                        createCommentsState($commentIdx, $userIdxInToken,$state);
                        $res->is_success = TRUE;
                        $res->code = 100;
                        $res->message = "유저 댓글 '$word' 성공";
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

    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
