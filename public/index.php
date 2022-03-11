<?php

declare(strict_types=1);

use DI\Container;
use Laminas\Cache\Psr\SimpleCache\SimpleCacheDecorator;
use Laminas\Cache\Storage\Adapter\Memcached;
use Laminas\Cache\Storage\Adapter\MemcachedOptions;
use Laminas\Cache\Storage\Adapter\MemcachedResourceManager;
use MarkdownBlog\ContentAggregator\ContentAggregatorFactory;
use Mni\FrontYAML\Parser;
use Psr\SimpleCache\CacheInterface;
use Psr\Http\Message\{
    ResponseInterface as Response,
    ServerRequestInterface as Request
};
use Slim\Factory\AppFactory;
use Slim\Views\{Twig,TwigMiddleware};
use Twig\Extra\Intl\IntlExtension;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(
    __DIR__ . '/../',
    [
        '.env',
        '.env.development'
    ],
    false
);
$dotenv->safeLoad();
$dotenv->required('POSTS_DIRECTORY');

$container = new Container();

$container->set('view', function($c) {
    $twig = Twig::create(__DIR__ . '/../resources/templates');
    $twig->addExtension(new IntlExtension());
    
    return $twig;
});

$container->set('articles', function($c) {
    /** @var CacheInterface $cache */
    $cache = $c->get('cache');
    if ($cache->has('articles')) {
        return $cache->get('articles');
    }

    $items = (new ContentAggregatorFactory())
        ->__invoke(
            [
                'path' => __DIR__ . '/../data/posts',
                'parser' => new Parser(),
            ]
        );
    $cache->set('articles', $items);

    return $items;
});

$container->set('cache', function($c): CacheInterface {
    $resourceManager = (new MemcachedResourceManager())
        ->addServer(
            'default',
            [
                'host' => 'cache'
            ]
        );

    return new SimpleCacheDecorator(
        new Memcached(
            new MemcachedOptions(
                [
                    'resource_manager' => $resourceManager
                ]
            )
        )
    );
});

AppFactory::setContainer($container);
$app = AppFactory::create();
$app->add(TwigMiddleware::createFromContainer($app));

$app->map(['GET'], '/', function (Request $request, Response $response, array $args) {
    return $this->get('view')->render(
        $response,
        'index.html.twig',
        ['items' => $this->get('articles')->getPublishedItems()]
    );
});

$app->map(['GET'], '/item/{slug}', function (Request $request, Response $response, array $args) {
    $view = $this->get('view');
    return $view->render(
        $response,
        'view.html.twig',
        ['item' => $this->get('articles')->findItemBySlug($args['slug'])]
    );
});

// Boot the application
$app->run();

