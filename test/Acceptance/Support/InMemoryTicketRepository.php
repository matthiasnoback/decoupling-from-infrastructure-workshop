<?php
declare(strict_types=1);

namespace Test\Acceptance\Support;

use DevPro\Domain\Model\Ticket\Ticket;
use DevPro\Domain\Model\Ticket\TicketId;
use DevPro\Domain\Model\Ticket\TicketRepository;
use Ramsey\Uuid\Uuid;
use RuntimeException;

final class InMemoryTicketRepository implements TicketRepository
{
    /**
     * @var array & Ticket[]
     */
    private $entities = [];

    public function save(Ticket $entity): void
    {
        $this->entities[$entity->ticketId()->asString()] = $entity;
    }

    public function getById(TicketId $id): Ticket
    {
        if (!isset($this->entities[$id->asString()])) {
            throw new RuntimeException('Could not find Ticket with ID ' . $id->asString());
        }

        return $this->entities[$id->asString()];
    }

    public function nextIdentity(): TicketId
    {
        return TicketId::fromString(Uuid::uuid4()->toString());
    }
}
