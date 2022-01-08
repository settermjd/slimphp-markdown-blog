<?php

declare(strict_types=1);

namespace MarkdownBlog\ContentAggregator;

use MarkdownBlog\Entity\BlogItem;

interface ContentAggregatorInterface
{
    public function findItemBySlug(string $slug): ?BlogItem;
    public function getItems();
}
