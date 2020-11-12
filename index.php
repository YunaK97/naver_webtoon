<?php

require './pdos/DatabasePdo.php';
require './pdos/IndexPdo.php';
require './pdos/JWTPdo.php';
require './pdos/webtoonPdo.php';
require './pdos/episodePdo.php';
require './pdos/searchPdo.php';
require './pdos/commentPdo.php';
require './vendor/autoload.php';

use \Monolog\Logger as Logger;
use Monolog\Handler\StreamHandler;

date_default_timezone_set('Asia/Seoul');
ini_set('default_charset', 'utf8mb4');

//에러출력하게 하는 코드
//error_reporting(E_ALL); ini_set("display_errors", 1);

//Main Server API
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    /* ******************   JWT   ****************** */
    $r->addRoute('POST', '/jwt', ['JWTController', 'createJwt']);   // JWT 생성: 로그인 + 해싱된 패스워드 검증 내용 추가
    $r->addRoute('GET', '/jwt', ['JWTController', 'validateJwt']);  // JWT 유효성 검사
    /* ******************   Test   ****************** */
    $r->addRoute('GET', '/', ['IndexController', 'index']);
    $r->addRoute('GET', '/users', ['IndexController', 'getUsers']);
    $r->addRoute('GET', '/users/{userIdx}', ['IndexController', 'getUserDetail']);
    $r->addRoute('POST', '/user', ['IndexController', 'createUser']); // 비밀번호 해싱 예시 추가
    /* ******************  index   ****************** */
    $r->addRoute('GET', '/banner', ['IndexController', 'banner']); // 배너 조회
    $r->addRoute('POST', '/login', ['IndexController', 'login']); // 로그인
    $r->addRoute('GET', '/mainpage', ['IndexController', 'main']); // 인기순 / 추천순 등등 update 해야함
    $r->addRoute('GET', '/more', ['IndexController', 'getMore']); //더보기 조회
    /* ******************   WEBTOON   ****************** */
    $r->addRoute('GET', '/search', ['SearchController', 'search']); // order by 고민
    $r->addRoute('GET', '/webtoons/{webtoonIdx}/episodes', ['WebtoonController', 'getWebtoonDetail']);
    $r->addRoute('DELETE', '/webtoon/favorites', ['WebtoonController', 'deleteFavorite']); //관심 삭제
    $r->addRoute('DELETE', '/webtoon/look', ['WebtoonController', 'deleteLook']); //최근 본 웹툰  삭제
    $r->addRoute('DELETE', '/webtoon/keep', ['WebtoonController', 'deleteKeep']); //보관함 삭제
    $r->addRoute('GET', '/webtoon/favorites', ['WebtoonController', 'getFavorites']); //관심 조회
    $r->addRoute('GET', '/webtoon/look', ['WebtoonController', 'getLookEpisode']); //최근 본웹툰 조회
    $r->addRoute('GET', '/webtoon/keep', ['WebtoonController', 'getKeepEpisode']); //최근 본웹툰 조회
    $r->addRoute('POST', '/webtoon/{webtoonIdx}/favorites', ['WebtoonController', 'createFavorites']); //관심웹툰 등록
    $r->addRoute('PATCH', '/webtoon/{webtoonIdx}/favorites/alarm', ['WebtoonController', 'updateAlarm']); //알람 여부
    /* ******************   EPISODE  ****************** */
    $r->addRoute('GET', '/episodes/{episodeIdx}', ['EpisodeController', 'getEpisode']); //에피소드 보기
    $r->addRoute('GET', '/episodes/{episodeIdx}/preview/details', ['EpisodeController', 'getPreviewDetails']); //미리보기 조회
    $r->addRoute('POST', '/episodes/{episodeIdx}/preview', ['EpisodeController', 'createPreview']); //미리보기 대여 진행
    $r->addRoute('POST', '/episodes/{episodeIdx}/heart', ['EpisodeController', 'createHeart']); //에피소드 하트누르기
    $r->addRoute('GET', '/episodes/{episodeIdx}/comments', ['EpisodeController', 'getComments']); // 댓글 조회
    $r->addRoute('POST', '/comments/{commentIdx}/state', ['CommentController', 'createCommentState']); //댓글 좋아요/싫어요
    $r->addRoute('POST', '/episodes/{episodeIdx}/comments', ['EpisodeController', 'createComment']); //댓글 생성
    $r->addRoute('POST', '/episodes/{episodeIdx}/star-rating', ['EpisodeController', 'createStar']); //별점 생성
//    $r->addRoute('GET', '/users', 'get_all_users_handler');
//    // {id} must be a number (\d+)
//    $r->addRoute('GET', '/user/{id:\d+}', 'get_user_handler');
//    // The /{title} suffix is optional
//    $r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

// 로거 채널 생성
$accessLogs = new Logger('ACCESS_LOGS');
$errorLogs = new Logger('ERROR_LOGS');
// log/your.log 파일에 로그 생성. 로그 레벨은 Info
$accessLogs->pushHandler(new StreamHandler('logs/access.log', Logger::INFO));
$errorLogs->pushHandler(new StreamHandler('logs/errors.log', Logger::ERROR));
// add records to the log
//$log->addInfo('Info log');
// Debug 는 Info 레벨보다 낮으므로 아래 로그는 출력되지 않음
//$log->addDebug('Debug log');
//$log->addError('Error log');

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        echo "404 Not Found";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        echo "405 Method Not Allowed";
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        switch ($routeInfo[1][0]) {
            case 'IndexController':
                $handler = $routeInfo[1][1];
                $vars = $routeInfo[2];
                require './controllers/IndexController.php';
                break;
            case 'JWTController':
                $handler = $routeInfo[1][1];
                $vars = $routeInfo[2];
                require './controllers/JWTController.php';
                break;
            case 'EpisodeController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/EpisodeController.php';
                break;
            case 'WebtoonController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/WebtoonController.php';
                break;
            case 'SearchController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/SearchController.php';
                break;
            case 'CommentController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/CommentController.php';
                break;
//            case 'ElementController':
//                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
//                require './controllers/ElementController.php';
//                break;
//            case 'AskFAQController':
//                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
//                require './controllers/AskFAQController.php';
//                break;
        }

        break;
}
?>