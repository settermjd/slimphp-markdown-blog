<?php

declare(strict_types=1);

namespace MarkdownBlog\Iterator;

use DateTime;
use Iterator;
use MarkdownBlog\Entity\BlogItem;

/**
 * This class filters out any BlogItem that, based on its
 * publish date, isn't published.
 */
class PublishedItemFilterIterator extends \FilterIterator
{
    public function __construct(Iterator $iterator)
    {
        parent::__construct($iterator);
        $this->rewind();
    }

    public function accept(): bool
    {
        /** @var BlogItem $episode */
        $episode = $this->getInnerIterator()->current();

        return $episode->getPublishDate() <= new DateTime();
    }
}
