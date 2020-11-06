<?php
declare(strict_types=1);

namespace DevPro\Infrastructure\Web;

use DevPro\Application\ApplicationInterface;
use DevPro\Application\ScheduleTraining;
use DevPro\Application\Users\CreateUser;
use DevPro\Application\Users\GetSecurityUser;
use DevPro\Application\Users\SecurityUser;
use DevPro\Infrastructure\Framework\TemplateRenderer;
use DevPro\Infrastructure\Session;
use RuntimeException;

final class Controllers
{
    private ApplicationInterface $application;
    private GetSecurityUser $getSecurityUser;
    private Session $session;
    private TemplateRenderer $templateRenderer;

    public function __construct(
        ApplicationInterface $application,
        GetSecurityUser $getSecurityUser,
        Session $session,
        TemplateRenderer $templateRenderer
    ) {
        $this->application = $application;
        $this->getSecurityUser = $getSecurityUser;
        $this->session = $session;
        $this->templateRenderer = $templateRenderer;
    }

    public function indexController(): void
    {
        if ($this->session->get('logged_in_user')) {
            $username = $this->session->get('logged_in_user')->username();
        } else {
            $username = 'world';
        }

        echo $this->templateRenderer->render(__DIR__ . '/View/index.php', ['username' => $username]);
    }

    public function registerUserController(): void
    {
        $formErrors = [];
        $formData = ['username' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = array_merge($formData, $_POST);

            if ($formData['username'] === '') {
                $formErrors['username'] = 'Username should not be empty';
            }

            if (empty($formErrors)) {
                $this->application->createUser(new CreateUser($_POST['username']));
                $this->session->addSuccessFlash('Registration was successful');

                header('Location: /');
                exit;
            }
        }

        echo $this->templateRenderer->render(
            __DIR__ . '/View/register_user.php',
            [
                'formErrors' => $formErrors,
                'formData' => $formData
            ]
        );
    }

    public function loginController(): void
    {
        $formErrors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $securityUser = $this->getSecurityUser->byUsername($_POST['username'] ?? '');
                $this->session->set('logged_in_user', $securityUser);
                $this->session->addSuccessFlash('You have successfully logged in');
                header('Location: /');
                exit;
            } catch (RuntimeException $exception) {
                $formErrors['username'] = 'Invalid username';
            }
        }

        echo $this->templateRenderer->render(__DIR__ . '/View/login.php', ['formErrors' => $formErrors]);
    }

    public function scheduleTrainingController(): void
    {
        $formErrors = [];
        $formData = ['title' => '', 'scheduled_date' => '', 'country' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = array_merge($formData, $_POST);
            $this->application->scheduleTraining(
                new ScheduleTraining(
                    $this->getLoggedInUser()->id(),
                    $formData['title'],
                    $formData['scheduled_date'],
                    $formData['country']
                )
            );

            $this->session->addSuccessFlash('You have successfully scheduled a training');
            header('Location: /');
            exit;
        }

        echo $this->templateRenderer->render(
            __DIR__ . '/View/schedule_training.php',
            ['formErrors' => $formErrors, 'formData' => $formData]
        );
    }

    private function getLoggedInUser(): SecurityUser
    {
        $loggedInUser = $this->session->get('logged_in_user');

        if (!$loggedInUser instanceof SecurityUser) {
            throw new RuntimeException('There is no logged in user');
        }

        return $loggedInUser;
    }
}
