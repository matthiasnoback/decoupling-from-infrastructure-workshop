<?php
declare(strict_types=1);

namespace DevPro\Application\UpcomingTrainings;

final class UpcomingTraining
{
    private string $title;

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public function title(): string
    {
        return $this->title;
    }
}
