<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure;

use Common\EventDispatcher\EventDispatcher;
use MeetupOrganizing\Application\Clock;
use MeetupOrganizing\Infrastructure\Framework\TemplateRenderer;
use MeetupOrganizing\Infrastructure\Web\Controllers;
use MeetupOrganizing\Infrastructure\Web\WebApplication;
use Test\Adapter\MeetupOrganizing\Infrastructure\InputAdapterTestServiceContainer;
use Test\Adapter\MeetupOrganizing\Infrastructure\OutputAdapterTestServiceContainer;
use Test\EndToEnd\EndToEndTestServiceContainer;

/**
 * Not final but also not abstract because we want to be able to override some methods, yet use this as the actual
 * development service container.
 */
abstract class AbstractDevelopmentServiceContainer extends AbstractServiceContainer
{
    private ?Session $session = null;

    public static function create(ContainerConfiguration $containerConfiguration): self
    {
        if ($containerConfiguration->environment() === 'input_adapter_test') {
            return new InputAdapterTestServiceContainer($containerConfiguration);
        } elseif ($containerConfiguration->environment() === 'end_to_end') {
            return new EndToEndTestServiceContainer($containerConfiguration);
        } elseif ($containerConfiguration->environment() === 'output_adapter_test') {
            return new OutputAdapterTestServiceContainer($containerConfiguration);
        }

        return new DevelopmentServiceContainer($containerConfiguration);
    }

    private function controllers(): Controllers
    {
        return new Controllers(
            $this->application(),
            $this->securityUsers(),
            $this->session(),
            $this->templateRenderer()
        );
    }

    protected function clock(): Clock
    {
        return new SystemClock();
    }

    protected function registerSubscribers(EventDispatcher $eventDispatcher): void
    {
        // Register subscribers that should be available in every environment in the parent method
        parent::registerSubscribers($eventDispatcher);

        // Register additional event subscribers that are only meant to be notified in a development environment:
    }

    private function templateRenderer(): TemplateRenderer
    {
        return new TemplateRenderer($this->globalTemplateVariables());
    }

    /**
     * @return array<string,mixed>
     */
    private function globalTemplateVariables(): array
    {
        return [
            'session' => $this->session()
        ];
    }

    private function session(): Session
    {
        return $this->session ?? $this->session = new Session();
    }

    public function webApplication(): WebApplication
    {
        return WebApplication::createFromGlobalsWithControllers($this->controllers());
    }
}
