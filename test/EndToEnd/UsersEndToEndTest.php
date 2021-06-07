<?php
declare(strict_types=1);

namespace Test\EndToEnd;

use Test\Common\BrowserTest;

final class UsersEndToEndTest extends BrowserTest
{
    protected function environment(): string
    {
        return 'end_to_end';
    }

    /**
     * @test
     */
    public function I_am_not_logged_in(): void
    {
        // Given I am on "/"
        $this->browserSession->visit($this->url('/'));

        // Then I should see "Hello, world!"
        $this->assertBrowserSession()->pageTextContains('Hello, world!');
    }

    /**
     * @test
     */
    public function I_log_in_after_registration(): void
    {
        // Given I am on "/registerUser"
        $this->browserSession->visit($this->url('/registerUser'));

        // And I fill in the following:
        //   | Username | Matthias |
        $this->browserSession->getPage()->fillField('Username', 'Matthias');

        // When I press "Submit"
        $this->browserSession->getPage()->pressButton('Submit');

        // And I am on "/login"
        $this->browserSession->visit($this->url('/login'));

        // And I fill in the following:
        //   | Username | Matthias |
        $this->browserSession->getPage()->fillField('Username', 'Matthias');

        // And I press "Submit"
        $this->browserSession->getPage()->pressButton('Submit');

        $this->printPage();


        $this->browserSession->visit($this->url('/'));

        // Then I should see "Hello, Matthias!"
        $this->assertBrowserSession()->pageTextContains('Hello, Matthias!');
    }
}
