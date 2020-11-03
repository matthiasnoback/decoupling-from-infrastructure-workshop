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
     */
    public function it_shows_a_form_error_if_the_username_is_invalid_register_user(): void
    {
        $this->session->visit($this->baseUrl . '/login');

        $page = $this->session->getPage();
        $page->fillField('username', 'Invalid');
        $page->pressButton('Submit');

        $this->assertSession()->pageTextContains('Invalid username');
    }

    private function assertSession(): WebAssert
    {
        return new WebAssert($this->session);
    }
}
