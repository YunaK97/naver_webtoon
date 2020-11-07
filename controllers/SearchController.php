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
      * API No. 2
      * API Name : 웹툰 검색
      * 마지막 수정 날짜 : 20.11.06
      */
        case "search":
            http_response_code(200);
            $keyword=$_GET['keyword'];
            if(empty($keyword)){ 
                $res->is_success = FALSE;
                $res->code = 200;
                $res->message = "필요 정보 모두 입력하지 않았습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
                $res->result = search($keyword);
                $res->is_success = TRUE;
                $res->code = 100;
                $res->message = "검색페이지 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;


    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
