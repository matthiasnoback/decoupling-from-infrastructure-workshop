<?php
declare(strict_types=1);

namespace Adapter\DevPro\Infrastructure\Web;

use Behat\Mink\Driver\GoutteDriver;
use Behat\Mink\Session;
use Behat\Mink\WebAssert;
use PHPUnit\Framework\TestCase;

final class ControllersTest extends TestCase
{
    private string $baseUrl;
    private Session $session;

    protected function setUp(): void
    {
        $this->baseUrl = 'http://' . (getenv('WEB_HOSTNAME') ?? 'localhost') . ':8080';

        $driver = new GoutteDriver();
        $this->session = new Session($driver);
        $this->session->start();
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
    public function it_calls_the_application_service_(): void
    {
        $this->session->visit($this->baseUrl . '/registerUser');

        $page = $this->session->getPage();
        $page->fillField('username', '');
        $page->pressButton('Submit');

        $this->assertSession()->pageTextContains('Username should not be empty');
    }

    private function assertSession(): WebAssert
    {
        $this->addToAssertionCount(1);

        return new WebAssert($this->session);
    }
}
