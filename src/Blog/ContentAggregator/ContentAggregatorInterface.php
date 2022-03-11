<?php

declare(strict_types=1);

namespace MarkdownBlog\ContentAggregator;

use MarkdownBlog\Entity\BlogItem;

interface ContentAggregatorInterface
{
    /**
     * Retrieve a BlogItem object from the list of available items,
     * based on its slug.
     */
    public function findItemBySlug(string $slug): ?BlogItem;

    /**
     * Retrieve all, available, blog items
     */
    public function getItems();
}
