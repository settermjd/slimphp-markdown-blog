<?php

declare(strict_types=1);

namespace MarkdownBlog\Sorter;

use MarkdownBlog\Entity\BlogItem;

/**
 * This class sorts an array of BlogItems, based on their
 * publish date, in reverse chronological order.
 */
class SortByReverseDateOrder
{
    public function __invoke(BlogItem $a, BlogItem $b): int
    {
        $firstDate = $a->getPublishDate();
        $secondDate = $b->getPublishDate();

        if ($firstDate == $secondDate) {
            return 0;
        }

        return ($firstDate > $secondDate) ? -1 : 1;
    }
}
