<?php

use DI\Container;
use Laminas\Cache\Psr\SimpleCache\SimpleCacheDecorator;
use Laminas\Cache\Storage\Adapter\Memcached;
use Laminas\Cache\Storage\Adapter\MemcachedOptions;
use Laminas\Cache\Storage\Adapter\MemcachedResourceManager;
use MarkdownBlog\ContentAggregator\ContentAggregatorFactory;
use Mni\FrontYAML\Parser;
use Psr\SimpleCache\CacheInterface;
use Twig\Extra\Intl\IntlExtension;
use Slim\Views\Twig;

require __DIR__ . '/../vendor/autoload.php';

/**
 * Load application environment variables from a combination of .env
 * and .env.development files, located in the project's root directory.
 *
 * If the files don't exist, an exception won't be thrown. However,
 * at least one needs to exist, and contain the variable POSTS_DIRECTORY,
 * as that is listed as required. This variable is the path to the directory
 * with the Markdown source files, that contain the blog item information.
 */
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

/**
 * Instantiate the application's DI container
 */
$container = new Container();

/**
 * Add a container service to provide the application's "view" layer,
 * handled by the venerable Twig templating engine.
 */
$container->set('view', function($c) {
    $twig = Twig::create(__DIR__ . '/../resources/templates');
    $twig->addExtension(new IntlExtension());

    return $twig;
});

/**
 * Add a container service to provide the application's article content.
 *
 * The articles will be retrieved from the "cache" service, if available.
 * Otherwise, the full content aggregation pipeline is executed, the
 * aggregated `BlogItem` entities will be stored in the application's
 * cache, and then they will be returned.
 */
$container->set('articles', function($c) {
    /** @var CacheInterface $cache */
    $cache = $c->get('cache');
    if ($cache->has('articles')) {
        return $cache->get('articles');
    }

    $items = (new ContentAggregatorFactory())
        ->__invoke(
            [
                'path' => __DIR__ . "/../{$_SERVER['POSTS_DIRECTORY']}",
                'parser' => new Parser(),
            ]
        );
    $cache->set('articles', $items);

    return $items;
});

/**
 * Add a container service to provide the application's cache service.
 *
 * This is provided by laminas-cache using laminas-cache-storage-adapter-memcached
 * for interacting with a Memcached server. In the current configuration,
 * only one Memcached server is being connected to. Change this, as
 * suits your needs.
 */
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

$container->set('config', function($c): array {
    return [
        'laminas-cli' => [
            'commands' => [
                'markdownblog::queueunpublishedblogitems' => \MarkdownBlog\Command\QueueUnpublishedBlogItems::class,
            ],
        ],
    ];
});

return $container;
