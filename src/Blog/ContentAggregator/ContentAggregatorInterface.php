<?php

declare(strict_types=1);

namespace MarkdownBlog\ContentAggregator;

use MarkdownBlog\Entity\BlogItem;
use Traversable;

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

    /**
     * Retrieve only the published blog items
     */
    public function getPublishedItems(): Traversable;

    /**
     * Retrieve only the unpublished blog items
     */
    public function getUnpublishedItems(): Traversable;
}
