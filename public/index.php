<?php

declare(strict_types=1);

use DI\Container;
use MarkdownBlog\ContentAggregator\ContentAggregatorFactory;
use MarkdownBlog\ContentAggregator\ContentAggregatorInterface;
use Mni\FrontYAML\Parser;
use Psr\Http\Message\{
    ResponseInterface as Response,
    ServerRequestInterface as Request
};
use Slim\Factory\AppFactory;
use Slim\Views\{Twig,TwigMiddleware};

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../', ['.env', '.env.development'], false);
$dotenv->safeLoad();
$dotenv->required('POSTS_DIRECTORY');

$container = new Container();
$container->set('view', fn() => Twig::create(__DIR__ . '/../resources/templates'));
$container->set(
    ContentAggregatorInterface::class,
    fn() => (new ContentAggregatorFactory())->__invoke([
        'path' => __DIR__ . sprintf('/../%s', $_SERVER['POSTS_DIRECTORY']),
        'parser' => new Parser(),
    ])
);

AppFactory::setContainer($container);
$app = AppFactory::create();
$app->add(TwigMiddleware::createFromContainer($app));

$app->map(['GET'], '/', function (Request $request, Response $response, array $args) {
    $view = $this->get('view');
    /** @var ContentAggregatorInterface $contentAggregator */
    $contentAggregator = $this->get(ContentAggregatorInterface::class);
    return $view->render(
        $response,
        'index.html.twig',
        ['items' => $contentAggregator->getItems()]
    );
});

$app->map(['GET'], '/item/{slug}', function (Request $request, Response $response, array $args) {
    $view = $this->get('view');
    /** @var ContentAggregatorInterface $contentAggregator */
    $contentAggregator = $this->get(ContentAggregatorInterface::class);
    return $view->render(
        $response,
        'view.html.twig',
        ['item' => $contentAggregator->findItemBySlug($args['slug'])]
    );
});

// Boot the application
$app->run();

