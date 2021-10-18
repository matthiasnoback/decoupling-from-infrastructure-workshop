<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application\Meetups;

use MeetupOrganizing\Domain\Model\Common\Mapping;

final class UpcomingMeetup
{
    use Mapping;

    private string $dateAndTime;
    private string $title;

    private function __construct(string $dateAndTime, string $title)
    {
        $this->dateAndTime = $dateAndTime;
        $this->title = $title;
    }

    /**
     * @param array<string,string|null> $record
     */
    public static function fromDatabaseRecord(array $record): self
    {
        return new self(
            self::getString($record, 'scheduledDate'),
            self::getString($record, 'title')
        );
    }

    public function dateAndTime(): string
    {
        return (new \DateTimeImmutable($this->dateAndTime))->format('r');
    }

    public function title(): string
    {
        return $this->title;
    }
}
