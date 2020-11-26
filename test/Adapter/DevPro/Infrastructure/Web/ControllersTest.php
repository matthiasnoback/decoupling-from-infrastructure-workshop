<?php
declare(strict_types=1);

namespace Test\Adapter\DevPro\Infrastructure\Web;

use Behat\Mink\Driver\Goutte\Client;
use Behat\Mink\Driver\GoutteDriver;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Session;
use Behat\Mink\WebAssert;
use DevPro\Application\Users\CreateUser;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Test\Adapter\DevPro\Infrastructure\ApplicationSpy;

final class ControllersTest extends TestCase
{
    private string $baseUrl;
    private Session $browserSession;
    private Client $client;

    protected function setUp(): void
    {
        $this->baseUrl = 'http://' . (getenv('WEB_HOSTNAME') ?? 'localhost') . ':8080';

        $this->client = new Client();
        $driver = new GoutteDriver($this->client);
        $this->browserSession = new Session($driver);
        $this->browserSession->start();

        $this->client->followRedirects(false);
        $this->browserSession->setCookie('environment', 'input_adapter_test');
    }

    /**
     * @test
     * @see Controllers::registerUserController()
     */
    public function it_shows_a_form_error_if_the_username_is_invalid(): void
    {
        $this->browserSession->visit($this->baseUrl . '/registerUser');

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
        $this->browserSession->visit($this->baseUrl . '/registerUser');

        $page = $this->browserSession->getPage();
        $page->fillField('username', 'Matthias');
        $page->pressButton('Submit');

        $this->assertResponseWasSuccessful();
        $this->assertThatCommandWasProcessed(new CreateUser('Matthias'));

        $this->followRedirect();

        $this->assertBrowserSession()->pageTextContains('Registration was successful');
    }

    private function assertBrowserSession(): WebAssert
    {
        // A trick to let PHPUnit know we're making an assertion here
        $this->addToAssertionCount(1);

        return new WebAssert($this->browserSession);
    }

    /**
     * For debugging purposes only
     */
    private function printPage(): void
    {
        echo $this->browserSession->getPage()->getContent();
    }

    private function followRedirect(): void
    {
        $this->client->followRedirect();
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

    private function findOrFail(string $cssLocator): NodeElement
    {
        $element = $this->browserSession->getPage()->find('css', $cssLocator);

        Assert::assertInstanceOf(
            NodeElement::class,
            $element,
            'Expected to find element with CSS selector: ' . $cssLocator
        );

        return $element;
    }
}
