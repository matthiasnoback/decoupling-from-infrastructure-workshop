<?php
declare(strict_types=1);

namespace DevPro\Infrastructure\Web;

use DevPro\Application\ApplicationInterface;
use DevPro\Domain\Model\User\UserRepository;
use RuntimeException;

final class Controllers
{
    private ApplicationInterface $application;
    private UserRepository $userRepository;

    public function bootstrap(): void
    {
        session_start();
    }

    public function __construct(ApplicationInterface $application, UserRepository $userRepository)
    {
        $this->application = $application;
        $this->userRepository = $userRepository;
    }

    public function indexController(): void
    {
        header('Content-Type: text/plain');
        echo 'Hello, ' . ($_SESSION['logged_in_user'] ?? 'world') . '!';
    }

    public function registerUserController(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->application->createUser($_POST['name'] ?? '');

            header('Location: /');
            exit;
        }

        include __DIR__ . '/View/register_user.php';
    }

    public function loginController(): void
    {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $user = $this->userRepository->getUserByName($_POST['username'] ?? '');
                $_SESSION['logged_in_user'] = $user->userId()->asString();
            } catch (RuntimeException $exception) {
                $error = 'Invalid username';
            }

            header('Location: /');
            exit;
        }

        include __DIR__ . '/View/login.php';
    }
}
