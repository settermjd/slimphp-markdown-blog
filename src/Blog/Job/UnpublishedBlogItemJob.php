<?php

declare(strict_types=1);

namespace MarkdownBlog\Job;

use MarkdownBlog\Entity\BlogItem;
use MarkdownBlog\Sorter\SortByReverseDateOrder;
use Psr\SimpleCache\CacheInterface;
use SlmQueue\Job\AbstractJob;

class UnpublishedBlogItemJob extends AbstractJob
{
    private CacheInterface $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public static function create(BlogItem $blogItem): self
    {
        $job = self::createEmptyJob(
            [
                'blogItem' => $blogItem
            ]
        );

        $job->setMetadata(
            'publishDate',
            $blogItem->getPublishDate()->getTimestamp()
        );

        return $job;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $payload = $this->getContent();

        /**
         * Retrieve the list of cached articles
         * Might have to check if the cache was empty, and fill it, if not.
         */
        $blogItems = $this->cache->get('articles');

        /**
         * Retrieve the current blog item from the job's payload.
         */
        $blogItem = $payload['blogItem'];

        /**
         * Add the item to the list of items
         */
        $blogItems[] = $blogItem;

        /**
         * Sort the items in reverse date order
         */
        $sorter = new SortByReverseDateOrder();
        usort($blogItems, $sorter);

        /**
         * Overwrite the existing set of articles with the new set
         */
        $this->cache->set('articles', $blogItems);
    }
}