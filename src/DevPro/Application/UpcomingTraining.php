<?php
declare(strict_types=1);

namespace DevPro\Application;

final class UpcomingTraining
{
    private string $title;

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    /**
     * @param array<string,string> $record
     */
    public static function fromDatabaseRecord(array $record): self
    {
        return new self($record['title']);
    }

    public function title(): string
    {
        return $this->title;
    }
}
