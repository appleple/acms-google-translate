<?php

use Acms\Plugins\GoogleTranslate\SpokeController;

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);

/**
 * Error handler
 */
$c = $app->getContainer();
$c['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        return $response->withJson(array(
            'message' => $exception->getMessage(),
            'code' => 500,
        ), 500);
    };
};
$c['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $response->withJson(array(
            'message' => 'Resource not found.',
            'code' => 404,
        ), 404);
    };
};
$c['notAllowedHandler'] = function ($c) {
    return function ($request, $response, $methods) use ($c) {
        return $response->withJson(array(
            'message' => 'Method must be one of: ' . implode(', ', $methods),
            'code' => 405,
        ), 405);
    };
};

/**
 * 翻訳対象エントリー覧
 *
 * get: /api/spoke/entries?limit=100&offset=0
 */
$app->get('/api/spoke/entries/', SpokeController::class . ':index');

/**
 * 翻訳対象エントリー詳細
 *
 * get: /api/spoke/entries/{eid}/
 */
$app->get('/api/spoke/entries/{eid}/', SpokeController::class . ':show');

/**
 * 翻訳対象エントリーアップデート
 *
 * post: /api/spoke/entries/{eid}/
 */
$app->map(array('POST', 'PUT'), '/api/spoke/entries/{eid}/', SpokeController::class . ':update');

$app->run();
die();
