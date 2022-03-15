<?php

namespace MarkdownBlog\ContentAggregator;

use ArrayIterator;
use MarkdownBlog\Iterator\MarkdownFileFilterIterator;
use MarkdownBlog\Entity\BlogItem;
use MarkdownBlog\Iterator\PublishedItemFilterIterator;
use MarkdownBlog\Iterator\UnpublishedItemFilterIterator;
use MarkdownBlog\Sorter\SortByReverseDateOrder;
use Mni\FrontYAML\Document;
use Mni\FrontYAML\Parser;

class ContentAggregatorFilesystem implements ContentAggregatorInterface
{
    protected Parser $fileParser;
    protected MarkdownFileFilterIterator $fileIterator;
    private array $items = [];

    public function __construct(
        MarkdownFileFilterIterator $fileIterator,
        Parser $fileParser
    ) {
        $this->fileParser = $fileParser;
        $this->fileIterator = $fileIterator;

        $this->buildItemsList();
    }

    /**
     * Retrieve all, available, blog items
     */
    public function getItems(): array
    {
        $sorter = new SortByReverseDateOrder();
        usort($this->items, $sorter);

        return $this->items;
    }

    /**
     * Retrieve only the published blog items
     */
    public function getPublishedItems(): \Traversable
    {
        return new PublishedItemFilterIterator(
            new ArrayIterator($this->items)
        );
    }

    public function getUnpublishedItems(): \Traversable
    {
        return new UnpublishedItemFilterIterator(
            new ArrayIterator($this->items)
        );
    }

    /**
     * Build an array of MarkdownBlog\Entity\BlogItem objects
     * from the available Markdown files.
     */
    protected function buildItemsList(): void
    {
        foreach ($this->fileIterator as $file) {
            $article = $this->buildItemFromFile($file);
            if (! is_null($article)) {
                $this->items[] = $article;
            }
        }
    }

    /**
     * Retrieve a BlogItem object from the list of available items,
     * based on its slug.
     */
    public function findItemBySlug(string $slug): ?BlogItem
    {
        foreach ($this->items as $article) {
            if ($article->getSlug() === $slug) {
                return $article;
            }
        }

        return null;
    }

    /**
     * Instantiate a BlogItem object based on the information retrieved
     * from the Markdown file.
     */
    public function buildItemFromFile(\SplFileInfo $file): ?BlogItem
    {
        $fileContent = file_get_contents($file->getPathname());
        $document = $this->fileParser->parse($fileContent, false);

        $item = new BlogItem();
        $item->populate($this->getItemData($document));

        return $item;
    }

    /**
     * Retrieve raw file data.
     */
    public function getItemData(Document $document): array
    {
        return [
            'publishDate' => $document->getYAML()['publish_date'] ?? '',
            'slug' => $document->getYAML()['slug'] ?? '',
            'synopsis' => $document->getYAML()['synopsis'] ?? '',
            'title' => $document->getYAML()['title'] ?? '',
            'image' => $document->getYAML()['image'] ?? '',
            'categories' => $document->getYAML()['categories'] ?? [],
            'tags' => $document->getYAML()['tags'] ?? [],
            'content' => $document->getContent(),
        ];
    }
}
