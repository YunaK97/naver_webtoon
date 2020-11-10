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
        case "test":
            http_response_code(200);
            $res->isSuccess = TRUE;
            $res->message = "테스트 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
         * API No. 4
         * API Name : 테스트 API
         * 마지막 수정 날짜 : 19.04.29
         */
        case "getUsers":
            http_response_code(200);
            $res->result = getUsers();
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "테스트 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
         * API No. 5
         * API Name : 테스트 Path Variable API
         * 마지막 수정 날짜 : 19.04.29
         */
        case "getUserDetail":
            http_response_code(200);

            $res->result = getUserDetail($vars["userIdx"]);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "테스트 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
         * API No. 6
         * API Name : 테스트 Body & Insert API
         * 마지막 수정 날짜 : 19.04.29
         */
        case "createUser":
            http_response_code(200);

            // Packet의 Body에서 데이터를 파싱합니다.
            $userID = $req->userID;
            $pwd_hash = password_hash($req->pwd, PASSWORD_DEFAULT); // Password Hash
            $name = $req->name;

            $res->result = createUser($userID, $pwd_hash, $name);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "테스트 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
               * API No. 15
               * API Name : 로그인
               * 마지막 수정 날짜 : 20.11.09
               */
        case "login":
            http_response_code(200);
             // function.php 에 구현
            $res->result->jwt = getJWT(1,'F','jennyk97', JWT_SECRET_KEY);
            $res->is_success = TRUE;
            $res->code = 100;
            $res->message = "테스트 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
             * API No. 26
             * API Name : 테스트 Body & Insert API
             * 마지막 수정 날짜 : 20.11.08
             */
        case "banner":
            http_response_code(200);
            $size=(int)$_GET['size'];
            if(empty($size)){
                $res->is_success = FALSE;
                $res->code = 200;
                $res->message = "size 입력하지 않았습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(is_numeric($size)) {
                if(bannersize()>=$size and $size >0) {
                    $res->result = banner($size);
                    $res->is_success = TRUE;
                    $res->code = 100;
                    $res->message = "배너 조회 성공";
                }else{
                    $res->is_success = FALSE;
                    $res->code = 202;
                    $res->message = "size가 data보다 크거나 음수입니다.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
            }else{
                $res->is_success = FALSE;
                $res->code = 201;
                $res->message = "숫자를 입력";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
