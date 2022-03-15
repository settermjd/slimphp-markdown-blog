<?php

declare(strict_types=1);

use Psr\Http\Message\{
    ResponseInterface as Response,
    ServerRequestInterface as Request
};
use Slim\Factory\AppFactory;
use Slim\Views\TwigMiddleware;

require __DIR__ . '/../vendor/autoload.php';

$container = require __DIR__ . '/../config/container.php';

/**
 * Instantiate a new Slim Framework application, passing it the DI container.
 */
AppFactory::setContainer($container);
$app = AppFactory::create();
$app->add(TwigMiddleware::createFromContainer($app));

/**
 * Define the default route.
 *
 * This route lists all articles in the application; it's the "blog index" page.
 */
$app->map(['GET'], '/', function (Request $request, Response $response, array $args) {
    return $this->get('view')->render(
        $response,
        'index.html.twig',
        ['items' => $this->get('articles')->getPublishedItems()]
    );
});

/**
 * Define a route to view a blog article.
 *
 * This route retrieves a blog article by its slug and renders it.
 */
$app->map(['GET'], '/item/{slug}', function (Request $request, Response $response, array $args) {
    $view = $this->get('view');
    return $view->render(
        $response,
        'view.html.twig',
        ['item' => $this->get('articles')->findItemBySlug($args['slug'])]
    );
});

/**
 * Boot the application
 */
$app->run();

