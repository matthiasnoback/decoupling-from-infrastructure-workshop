<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\Web;

use MeetupOrganizing\Application\ApplicationInterface;
use MeetupOrganizing\Application\Meetups\ScheduleMeetup;
use MeetupOrganizing\Application\Users\CouldNotFindUser;
use MeetupOrganizing\Application\Users\CreateUser;
use MeetupOrganizing\Application\Users\Users;
use MeetupOrganizing\Application\Users\User;
use MeetupOrganizing\Infrastructure\Framework\TemplateRenderer;
use MeetupOrganizing\Infrastructure\Session;
use RuntimeException;

final class Controllers
{
    private ApplicationInterface $application;
    private Users $users;
    private Session $session;
    private TemplateRenderer $templateRenderer;

    public function __construct(
        ApplicationInterface $application,
        Users $users,
        Session $session,
        TemplateRenderer $templateRenderer
    ) {
        $this->application = $application;
        $this->users = $users;
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

    public function meetupDetailsController(): void
    {
        $meetupDetails = $this->application->meetupDetails($_GET['meetupId']);

        echo $this->templateRenderer->render(__DIR__ . '/View/meetup_details.php', [
            'meetupDetails' => $meetupDetails
        ]);
    }

    public function registerUserController(): void
    {
        if ($this->isUserLoggedIn()) {
            $this->redirectTo('/');
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

                $this->redirectTo('/');
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
            $this->redirectTo('/login');
        }

        $formErrors = [];
        $formData = ['country' => 'NL', 'title' => '', 'description' => '', 'scheduledDate' => date('Y-m-d 20:00')];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = array_merge($formData, $_POST);

            if ($formData['title'] === '') {
                $formErrors['title'] = 'Please provide a title';
            }
            if ($formData['description'] === '') {
                $formErrors['description'] = 'Please provide a description';
            }

            if ($formErrors === []) {
                $this->application->scheduleMeetup(
                    new ScheduleMeetup(
                        $this->getLoggedInUser()->userId()->asString(),
                        $formData['country'],
                        $formData['title'],
                        $formData['description'],
                        $formData['scheduledDate']
                    )
                );

                $this->session->addSuccessFlash('You have scheduled a new meetup');

                $this->redirectTo('/');
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

    public function rsvpToMeetupController(): void
    {
        if (!$this->isUserLoggedIn()) {
            $this->session->addErrorFlash('You need to log in in order to RSVP to a meetup');
            $this->redirectTo('/login');
        }

        $meetupId = $_POST['meetupId'];

        // @TODO implement

        $this->redirectTo('/meetupDetails?meetupId=' . $meetupId);
    }

    public function loginController(): void
    {
        if ($this->isUserLoggedIn()) {
            $this->redirectTo('/');
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
                    $securityUser = $this->users->getByUsername($_POST['username'] ?? '');
                    $this->session->set('logged_in_user', $securityUser);
                    $this->session->addSuccessFlash('You have successfully logged in');
                    $this->redirectTo('/');
                } catch (CouldNotFindUser $exception) {
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

    public function logoutController(): void
    {
        $this->session->clear();
        $this->redirectTo('/');
    }

    private function getLoggedInUser(): User
    {
        $loggedInUser = $this->session->get('logged_in_user');

        if (!$loggedInUser instanceof User) {
            throw new RuntimeException('There is no logged in user');
        }

        return $loggedInUser;
    }

    private function isUserLoggedIn(): bool
    {
        return $this->session->get('logged_in_user') instanceof User;
    }

    private function redirectTo(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}
