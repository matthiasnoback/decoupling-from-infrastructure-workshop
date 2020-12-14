<?php
declare(strict_types=1);

namespace Test\Common;

use Behat\Mink\Driver\Goutte\Client;
use Behat\Mink\Driver\GoutteDriver;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Session;
use Behat\Mink\WebAssert;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

abstract class BrowserTest extends TestCase
{
    private string $baseUrl;
    private Client $client;
    protected Session $browserSession;

    protected function setUp(): void
    {
        parent::setUp();

        $this->baseUrl = 'http://' . (getenv('WEB_HOSTNAME') ?? 'localhost') . ':8080';

        $this->client = new Client();
        $driver = new GoutteDriver($this->client);
        $this->browserSession = new Session($driver);
        $this->browserSession->start();

        $this->client->followRedirects(false);
        $this->browserSession->setCookie('environment', $this->environment());
    }

    abstract protected function environment(): string;

    protected function url(string $path): string
    {
        return $this->baseUrl . $path;
    }

    protected function followRedirect(): void
    {
        $this->client->followRedirect();
    }

    protected function assertBrowserSession(): WebAssert
    {
        // A trick to let PHPUnit know we're making an assertion here
        $this->addToAssertionCount(1);

        return new WebAssert($this->browserSession);
    }

    protected function findOrFail(string $cssLocator): NodeElement
    {
        $element = $this->browserSession->getPage()->find('css', $cssLocator);

        Assert::assertInstanceOf(
            NodeElement::class,
            $element,
            'Expected to find element with CSS selector: ' . $cssLocator
        );

        return $element;
    }

    /**
     * For debugging purposes only
     */
    protected function printPage(): void
    {
        echo $this->browserSession->getPage()->getContent();
    }
}
