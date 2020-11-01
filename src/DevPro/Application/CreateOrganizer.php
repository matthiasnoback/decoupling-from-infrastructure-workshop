<?php
declare(strict_types=1);

namespace DevPro\Application;

use Common\EventDispatcher\EventDispatcher;
use DevPro\Domain\Model\User\User;
use DevPro\Domain\Model\User\UserId;
use DevPro\Domain\Model\User\UserRepository;

final class CreateOrganizer
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

    public function create(): UserId
    {
        $organizer = User::createOrganizer(
            $this->userRepository->nextIdentity()
        );

        $this->userRepository->save($organizer);

        $this->eventDispatcher->dispatchAll($organizer->releaseEvents());

        return $organizer->userId();
    }
}
