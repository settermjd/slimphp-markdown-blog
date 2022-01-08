<?php

declare(strict_types=1);

namespace MarkdownBlog\Iterator;

use DirectoryIterator;
use SplFileInfo;

class MarkdownFileFilterIterator extends \FilterIterator
{
    public function __construct(DirectoryIterator $iterator)
    {
        parent::__construct($iterator);
        $this->rewind();
    }

    /**
     * Determine what is a valid element in this iterator.
     */
    public function accept(): bool
    {
        /** @var SplFileInfo $item */
        $item = $this->getInnerIterator()->current();

        if (!$item instanceof SplFileInfo) {
            return false;
        }

        if ($item->isDot() || !$item->isFile() || !$item->isReadable()) {
            return false;
        }

        if (!in_array($item->getExtension(), ['md', 'markdown'])) {
            return false;
        }

        return true;
    }
}
