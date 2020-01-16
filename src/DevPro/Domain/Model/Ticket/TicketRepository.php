<?php
declare(strict_types=1);

namespace DevPro\Domain\Model\Ticket;

use RuntimeException;

interface TicketRepository
{
    public function save(Ticket $entity): void;

    /**
     * @throws RuntimeException When the entity could not be found
     */
    public function getById(TicketId $id): Ticket;
}
