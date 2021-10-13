<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application\Users;

use Common\EventDispatcher\EventDispatcher;
use MeetupOrganizing\Domain\Model\User\User;
use MeetupOrganizing\Domain\Model\User\UserId;
use MeetupOrganizing\Domain\Model\User\UserRepository;

final class CreateUserHandler
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

    public function handle(CreateUser $command): UserId
    {
        $user = User::createNormalUser(
            $this->userRepository->nextIdentity(),
            $command->username()
        );

        $this->userRepository->save($user);

        $this->eventDispatcher->dispatchAll($user->releaseEvents());

        return $user->userId();
    }
}
