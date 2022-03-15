<?php

declare(strict_types=1);

namespace MarkdownBlog\Command;

use MarkdownBlog\ContentAggregator\ContentAggregatorInterface;
use MarkdownBlog\Job\UnpublishedBlogItemJob;
use SlmQueue\Queue\QueueInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class QueueUnpublishedBlogItems extends Command
{
    protected static $defaultName = 'blog:item:queue-unpublished-items';
    protected static $defaultDescription = 'Loads unpublished blog items into a processable queue';

    private ContentAggregatorInterface $contentAggregator;
    private QueueInterface $queue;

    protected function configure(): void
    {
        $this->setHelp('This command loads unpublished blog items into the queue which will later, automatically, be added to the published blog items cache.');
    }

    public function __construct(QueueInterface $queue, ContentAggregatorInterface $contentAggregator)
    {
        parent::__construct();

        $this->contentAggregator = $contentAggregator;
        $this->queue = $queue;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /**
         * Retrieve the list of unpublished blog items
         */
        $blogItems = $this
            ->contentAggregator
            ->getUnpublishedItems();

        /**
         * Add each of the unpublished blog items to the queue.
         */
        foreach ($blogItems as $blogItem) {
            $this->queue->push(UnpublishedBlogItemJob::create($blogItem));
        }

        return Command::SUCCESS;
    }
}