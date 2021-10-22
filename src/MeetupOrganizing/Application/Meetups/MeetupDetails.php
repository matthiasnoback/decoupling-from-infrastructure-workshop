<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application\Meetups;

use MeetupOrganizing\Domain\Model\Common\Mapping;

final class MeetupDetails
{
    use Mapping;

    private string $meetupId;
    private string $dateAndTime;
    private string $title;
    private string $description;

    /**
     * @var array<string>
     */
    private array $attendeeNames;

    public function __construct(string $meetupId, string $dateAndTime, string $title, string $description)
    {
        $this->meetupId = $meetupId;
        $this->dateAndTime = $dateAndTime;
        $this->title = $title;
        $this->description = $description;
        $this->attendeeNames = [];
    }

    /**
     * @param array<string,string|null> $record
     */
    public static function fromDatabaseRecord(array $record): self
    {
        return new self(
            self::getString($record, 'meetupId'),
            self::getString($record, 'scheduledDate'),
            self::getString($record, 'title'),
            self::getString($record, 'description')
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

    public function description(): string
    {
        return $this->description;
    }

    public function meetupId(): string
    {
        return $this->meetupId;
    }

    /**
     * @return array<string>
     */
    public function attendeeNames(): array
    {
        return $this->attendeeNames;
    }
}
