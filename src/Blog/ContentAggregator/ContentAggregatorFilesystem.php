<?php

namespace MarkdownBlog\ContentAggregator;

use DirectoryIterator;
use MarkdownBlog\Iterator\MarkdownFileFilterIterator;
use MarkdownBlog\Entity\BlogItem;
use Mni\FrontYAML\Document;
use Mni\FrontYAML\Parser;

class ContentAggregatorFilesystem implements ContentAggregatorInterface
{
    protected string $postDirectory;
    protected Parser $fileParser;
    protected MarkdownFileFilterIterator $fileIterator;
    private array $items = [];

    public function __construct(
        string $postDirectory,
        Parser $fileParser
    ) {
        $this->postDirectory = $postDirectory;
        $this->fileParser = $fileParser;
        $this->fileIterator = new MarkdownFileFilterIterator(
            new DirectoryIterator(
                $this->postDirectory
            )
        );
        $this->items = $this->buildItemsList();
    }

    public function getItems(): array
    {
        return $this->items;
    }

    protected function buildItemsList(): array
    {
        $episodeListing = [];
        foreach ($this->fileIterator as $file) {
            $article = $this->buildItemFromFile($file);
            if (! is_null($article)) {
                $episodeListing[] = $article;
            }
        }

        return $episodeListing;
    }

    protected function getMarkdownFileDataDirectory(): string
    {
        return $this
            ->fileIterator
            ->getInnerIterator()
            ->getPath();
    }

    public function findItemBySlug(string $slug): ?BlogItem
    {
        foreach ($this->items as $article) {
            if ($article->getSlug() === $slug) {
                return $article;
            }
        }

        return null;
    }

    public function buildItemFromFile(\SplFileInfo $file): ?BlogItem
    {
        $fileContent = file_get_contents($file->getPathname());
        $document = $this->fileParser->parse($fileContent, false);

        $item = new BlogItem();
        $item->populate($this->getItemData($document));

        return $item;
    }

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
