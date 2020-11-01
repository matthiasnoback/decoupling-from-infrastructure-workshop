<?php
declare(strict_types=1);

namespace Test\EndToEnd;

use Behat\Mink\Element\NodeElement;
use Behat\MinkExtension\Context\MinkContext;
use PHPUnit\Framework\Assert;

final class FeatureContext extends MinkContext
{
    private string $varDirectory;

    public function __construct(string $varDirectory)
    {
        $this->varDirectory = $varDirectory;
    }

    /**
     * @BeforeScenario
     */
    public function deleteDatabase(): void
    {
        $dbFile = $this->varDirectory . '/development.sqlite';
        if (is_file($dbFile)) {
            unlink($dbFile);
        }
    }

    private function findOrFail(string $cssLocator): NodeElement
    {
        $element = $this->getSession()->getPage()->find('css', $cssLocator);

        Assert::assertInstanceOf(
            NodeElement::class,
            $element,
            'Expected to find element with CSS selector: ' . $cssLocator
        );

        return $element;
    }
}
