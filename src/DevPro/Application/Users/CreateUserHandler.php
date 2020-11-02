<?php
declare(strict_types=1);

namespace DevPro\Application\Users;

use Common\EventDispatcher\EventDispatcher;
use DevPro\Application\Users\CreateUser;
use DevPro\Domain\Model\User\User;
use DevPro\Domain\Model\User\UserId;
use DevPro\Domain\Model\User\UserRepository;

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
