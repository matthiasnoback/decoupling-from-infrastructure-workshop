<?php
declare(strict_types=1);

namespace DevPro\Application\Users;

use Common\EventDispatcher\EventDispatcher;
use DevPro\Application\Users\CreateOrganizer;
use DevPro\Domain\Model\User\User;
use DevPro\Domain\Model\User\UserId;
use DevPro\Domain\Model\User\UserRepository;

final class CreateOrganizerHandler
{
    private UserRepository $userRepository;
    private EventDispatcher $eventDispatcher;

    public function __construct(
        UserRepository $userRepository,
        EventDispatcher $eventDispatcher
    ) {
        $this->userRepository = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function handle(CreateOrganizer $command): UserId
    {
        $organizer = User::createOrganizer(
            $this->userRepository->nextIdentity()
        );

        $this->userRepository->save($organizer);

        $this->eventDispatcher->dispatchAll($organizer->releaseEvents());

        return $organizer->userId();
    }
}
