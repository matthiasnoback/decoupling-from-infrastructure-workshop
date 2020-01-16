<?php
declare(strict_types=1);

namespace Test\Acceptance\Support;

use Common\EventDispatcher\EventDispatcher;
use DevPro\Domain\Model\Ticket\Ticket;
use DevPro\Domain\Model\Ticket\TicketId;
use DevPro\Domain\Model\Ticket\TicketRepository;
use RuntimeException;

final class InMemoryTicketRepository implements TicketRepository
{
    /**
     * @var array & Ticket[]
     */
    private $entities = [];

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    public function __construct(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function save(Ticket $entity): void
    {
        $this->entities[$entity->ticketId()->asString()] = $entity;

        $this->eventDispatcher->dispatchAll($entity->releaseEvents());
    }

    public function getById(TicketId $id): Ticket
    {
        if (!isset($this->entities[$id->asString()])) {
            throw new RuntimeException('Could not find Ticket with ID ' . $id->asString());
        }

        return $this->entities[$id->asString()];
    }
}
