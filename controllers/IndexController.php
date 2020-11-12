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
            $order=$_GET['order'];
            if(empty($keyword)||empty($order)){
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
         * API No. 13
        * API Name : 더보기란
       * 마지막 수정 날짜 : 20.11.12
         */
        case "getMore":
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
                    $userIdxInToken=getDataByJWToken($jwt,JWT_SECRET_KEY)->userIdx;
                    $res->result = getMore($userIdxInToken);
                    $res->is_success = TRUE;
                    $res->code = 100;
                    $res->message = "더보기 성공";
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
               * API No. 13
               * API Name : 로그인
               * 마지막 수정 날짜 : 20.11.09
               */
        case "login":
            http_response_code(200);
             // function.php 에 구현
            $token = $req->token;
            $header = "Bearer ".$token;
            $url = "https://openapi.naver.com/v1/nid/me";
            $is_post = false;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, $is_post);
           curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $headers = array();
            $headers[] = "Authorization: ".$header;
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $response = curl_exec ($ch);
            $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            echo "status_code:".$status_code."<br>";
            curl_close ($ch);
            if($status_code == 200) {
                echo $response;
            } else {
                echo "Error 내용:".$response;
            }
           // $res->result->jwt = getJWT(1,'F','jennyk97', JWT_SECRET_KEY);
         //   $res->is_success = TRUE;
          //  $res->code = 100;
            $res->message = "테스트 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
             * API No. 25
             * API Name : 배너 조회
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
