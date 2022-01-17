<?php

declare(strict_types=1);

namespace MarkdownBlog\ContentAggregator;

use MarkdownBlog\Iterator\MarkdownFileFilterIterator;

class ContentAggregatorFactory
{
    /**
     * Build an ContentAggregatorInterface object based on a configuration array.
     *
     * The array has to have the following structure:
     *
     * 'blog' => [
     *   'type' => 'filesystem',
     *   'path' => __DIR__ . '/../../data/posts',
     *   'parser' => new Parser(),
     * ]
     */
    public function __invoke(array $config): ContentAggregatorInterface
    {
        $iterator = new MarkdownFileFilterIterator(
            new \DirectoryIterator($config['path'])
        );
        return new ContentAggregatorFilesystem($iterator, $config['parser']);
    }
}
