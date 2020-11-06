<?php
declare(strict_types=1);

namespace Test\Adapter\DevPro\Infrastructure\Web;

use Behat\Mink\Driver\Goutte\Client;
use Behat\Mink\Driver\GoutteDriver;
use Behat\Mink\Session;
use Behat\Mink\WebAssert;
use DevPro\Application\ScheduleTraining;
use DevPro\Application\Users\CreateUser;
use PHPUnit\Framework\TestCase;
use Test\Adapter\DevPro\Infrastructure\ApplicationSpy;
use Test\Adapter\DevPro\Infrastructure\HardCodedGetSecurityUser;

final class ControllersTest extends TestCase
{
    private string $baseUrl;
    private Session $session;
    private Client $client;

    protected function setUp(): void
    {
        $this->baseUrl = 'http://' . (getenv('WEB_HOSTNAME') ?? 'localhost') . ':8080';

        $this->client = new Client();
        $driver = new GoutteDriver($this->client);
        $this->session = new Session($driver);
        $this->session->start();

        $this->client->followRedirects(false);
        $this->session->setCookie('environment', 'input_adapter_test');
    }

    /**
     * @test
     * @see Controllers::registerUserController()
     */
    public function it_shows_a_form_error_if_the_username_is_invalid(): void
    {
        $this->session->visit($this->baseUrl . '/registerUser');

        $page = $this->session->getPage();
        $page->fillField('username', '');
        $page->pressButton('Submit');

        $this->assertSession()->pageTextContains('Username should not be empty');
    }

    /**
     * @test
     * @see Controllers::registerUserController()
     */
    public function it_calls_the_application_to_register_a_user(): void
    {
        $this->session->visit($this->baseUrl . '/registerUser');

        $page = $this->session->getPage();
        $page->fillField('username', 'Matthias');
        $page->pressButton('Submit');

        $this->assertResponseWasSuccessful();
        $this->assertThatCommandWasProcessed(new CreateUser('Matthias'));

        $this->followRedirect();

        $this->assertSession()->pageTextContains('Registration was successful');
    }

    /**
     * @test
     * @see Controllers::registerUserController()
     */
    public function it_calls_the_application_to_schedule_a_training(): void
    {
        $this->session->visit($this->baseUrl . '/login');
        $page = $this->session->getPage();
        $page->fillField('username', 'Organizer');
        $page->pressButton('Submit');

        $this->assertResponseWasSuccessful();

        $this->session->visit($this->baseUrl . '/scheduleTraining');

        $page = $this->session->getPage();
        $page->fillField('title', 'Nice one');
        $page->fillField('scheduled_date', '01-02-2021');
        $page->fillField('country', 'NL');
        $page->pressButton('Submit');

        $this->assertResponseWasSuccessful();

        $this->assertThatCommandWasProcessed(
            new ScheduleTraining(
                HardCodedGetSecurityUser::ORGANIZER_ID,
                'Nice one',
                '01-02-2021',
                'NL'
            )
        );

        $this->followRedirect();

        $this->assertSession()->pageTextContains('You have successfully scheduled a training');
    }

    private function assertSession(): WebAssert
    {
        // A trick to let PHPUnit know we're making an assertion here
        $this->addToAssertionCount(1);

        return new WebAssert($this->session);
    }

    /**
     * For debugging purposes only
     */
    private function printPage(): void
    {
        echo $this->session->getPage()->getContent();
    }

    private function followRedirect(): void
    {
        $this->client->followRedirect();
    }

    private function assertThatCommandWasProcessed(object $expectedCommand): void
    {
        $serializedCommand = $this->session->getResponseHeader(ApplicationSpy::COMMAND_SENT_HEADER);

        self::assertIsString($serializedCommand);
        self::assertNotEmpty(
            $serializedCommand,
            'The response header ' . ApplicationSpy::COMMAND_SENT_HEADER . ' was not provided'
        );
        $actualCommand = unserialize(base64_decode($serializedCommand));

        self::assertEquals($expectedCommand, $actualCommand);
    }

    private function assertResponseWasSuccessful(): void
    {
        $statusCode = $this->session->getStatusCode();

        if ($statusCode >= 200 && $statusCode < 400) {
            return;
        }

        $this->printPage();

        $this->fail('The response was unsuccessful');
    }
}
