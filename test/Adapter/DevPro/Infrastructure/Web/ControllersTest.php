<?php
declare(strict_types=1);

namespace Test\Adapter\DevPro\Infrastructure\Web;

use DevPro\Application\Users\CreateUser;
use Test\Adapter\DevPro\Infrastructure\ApplicationSpy;
use Test\Common\BrowserTest;

final class ControllersTest extends BrowserTest
{
    protected function environment(): string
    {
        return 'input_adapter_test';
    }

    /**
     * @test
     * @see Controllers::registerUserController()
     */
    public function it_shows_a_form_error_if_the_username_is_invalid(): void
    {
        $this->browserSession->visit($this->url('/registerUser'));

        $page = $this->browserSession->getPage();
        $page->fillField('username', '');
        $page->pressButton('Submit');

        $this->assertBrowserSession()->pageTextContains('Username should not be empty');
    }

    /**
     * @test
     * @see Controllers::registerUserController()
     */
    public function it_calls_the_application_to_register_a_user(): void
    {
        $this->browserSession->visit($this->url('/registerUser'));
        $this->assertResponseWasSuccessful();

        $page = $this->browserSession->getPage();
        $page->fillField('username', 'Matthias');
        $page->pressButton('Submit');

        $this->assertResponseWasSuccessful();
        $this->assertThatCommandWasProcessed(new CreateUser('Matthias'));

        $this->followRedirect();

        $this->assertBrowserSession()->pageTextContains('Registration was successful');
    }

    /**
     * @test
     */
    public function it_calls_the_application_to_schedule_a_training(): void
    {
        $this->logInAsOrganizer();

        $this->markTestIncomplete('TODO Assignment 7');
    }

    private function logInAsOrganizer(): void
    {
        $this->browserSession->visit($this->url('/login'));
        $this->assertResponseWasSuccessful();

        $page = $this->browserSession->getPage();
        $page->fillField('username', 'Organizer');
        $page->pressButton('Submit');
        $this->assertResponseWasSuccessful();
    }

    private function assertThatCommandWasProcessed(object $expectedCommand): void
    {
        $serializedCommand = $this->browserSession->getResponseHeader(ApplicationSpy::COMMAND_SENT_HEADER);

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
        $this->addToAssertionCount(1);

        $statusCode = $this->browserSession->getStatusCode();
        if ($statusCode >= 200 && $statusCode < 400) {
            // successful response
            return;
        }

        $this->fail(
            sprintf(
                "Response was unsuccessful. Status code: %d. Response text:\n\n%s",
                $statusCode,
                $this->browserSession->getPage()->getText()
            )
        );
    }
}
