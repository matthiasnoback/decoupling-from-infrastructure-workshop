<?php
declare(strict_types=1);

namespace DevPro\Infrastructure\Web;

use DevPro\Application\ApplicationInterface;
use DevPro\Application\Users\GetSecurityUser;
use RuntimeException;

final class Controllers
{
    private ApplicationInterface $application;
    private GetSecurityUser $getSecurityUser;

    public function bootstrap(): void
    {
        session_start();
    }

    public function __construct(ApplicationInterface $application, GetSecurityUser $getSecurityUser)
    {
        $this->application = $application;
        $this->getSecurityUser = $getSecurityUser;
    }

    public function indexController(): void
    {
        if (isset($_SESSION['logged_in_user'])) {
            $username = $_SESSION['logged_in_user']->username();
        } else {
            $username = 'world';
        }

        include __DIR__ . '/View/index.php';
    }

    public function registerUserController(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->application->createUser($_POST['username'] ?? '');

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
                $securityUser = $this->getSecurityUser->byUsername($_POST['username'] ?? '');
                $_SESSION['logged_in_user'] = $securityUser;
                header('Location: /');
                exit;
            } catch (RuntimeException $exception) {
                $error = 'Invalid username';
            }
        }

        include __DIR__ . '/View/login.php';
    }
}
