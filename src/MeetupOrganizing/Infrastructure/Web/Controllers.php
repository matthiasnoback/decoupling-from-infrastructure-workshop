<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\Web;

use MeetupOrganizing\Application\ApplicationInterface;
use MeetupOrganizing\Application\Meetups\ScheduleMeetup;
use MeetupOrganizing\Application\Users\CouldNotFindSecurityUser;
use MeetupOrganizing\Application\Users\CreateUser;
use MeetupOrganizing\Application\Users\SecurityUsers;
use MeetupOrganizing\Application\Users\SecurityUser;
use MeetupOrganizing\Infrastructure\Framework\TemplateRenderer;
use MeetupOrganizing\Infrastructure\Session;
use RuntimeException;

final class Controllers
{
    private ApplicationInterface $application;
    private SecurityUsers $getSecurityUser;
    private Session $session;
    private TemplateRenderer $templateRenderer;

    public function __construct(
        ApplicationInterface $application,
        SecurityUsers $getSecurityUser,
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

        $upcomingMeetups = $this->application->upcomingMeetups();

        echo $this->templateRenderer->render(__DIR__ . '/View/index.php', [
            'username' => $username,
            'upcomingMeetups' => $upcomingMeetups
        ]);
    }

    public function registerUserController(): void
    {
        if ($this->isUserLoggedIn()) {
            header('Location: /');
            exit;
        }

        $formErrors = [];
        $formData = ['username' => '', 'isOrganizer' => false];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = array_merge($formData, $_POST);

            if ($formData['username'] === '') {
                $formErrors['username'] = 'Username should not be empty';
            }

            if (empty($formErrors)) {
                $this->application->createUser(
                    new CreateUser(
                        $_POST['username'],
                        isset($_POST['isOrganizer'])
                    )
                );
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

    public function scheduleMeetupController(): void
    {
        if (!$this->isUserLoggedIn()) {
            $this->session->addErrorFlash('In order to schedule a meetup you need to log in first');
            header('Location: /login');
            exit;
        }

        $formErrors = [];
        $formData = ['country' => 'NL', 'title' => '', 'scheduledDate' => date('Y-m-d H:i')];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = array_merge($formData, $_POST);

            if ($formData['title'] === '') {
                $formErrors['title'] = 'Please provide a title';
            }

            if ($formErrors === []) {
                $this->application->scheduleMeetup(
                    new ScheduleMeetup(
                        $this->getLoggedInUser()->id(),
                        $formData['country'],
                        $formData['title'],
                        $formData['scheduledDate']
                    )
                );

                $this->session->addSuccessFlash('You have scheduled a new meetup');

                header('Location: /');
                exit;
            }
        }

        echo $this->templateRenderer->render(
            __DIR__ . '/View/schedule_meetup.php',
            [
                'formData' => $formData,
                'formErrors' => $formErrors
            ]
        );
    }

    public function loginController(): void
    {
        if ($this->isUserLoggedIn()) {
            header('Location: /');
            exit;
        }

        $formErrors = [];
        $formData = ['username' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = array_merge($formData, $_POST);

            if ($formData['username'] === '') {
                $formErrors['username'] = 'Please provide your username';
            }

            if (empty($formErrors)) {
                try {
                    $securityUser = $this->getSecurityUser->getByUsername($_POST['username'] ?? '');
                    $this->session->set('logged_in_user', $securityUser);
                    $this->session->addSuccessFlash('You have successfully logged in');
                    header('Location: /');
                    exit;
                } catch (CouldNotFindSecurityUser $exception) {
                    $formErrors['username'] = 'Invalid username';
                }
            }
        }

        echo $this->templateRenderer->render(
            __DIR__ . '/View/login.php',
            [
                'formData' => $formData,
                'formErrors' => $formErrors
            ]
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

    private function isUserLoggedIn(): bool
    {
        return $this->session->get('logged_in_user') instanceof SecurityUser;
    }
}
