<?php
declare(strict_types=1);

namespace DevPro\Infrastructure;

use Assert\Assert;
use RuntimeException;

final class ContainerConfiguration
{
    private string $environment;
    private ?string $varDirectory;
    private ?string $abstractApiApiKey;

    private function __construct(string $environment, ?string $varDirectory, ?string $abstractApiApiKey)
    {
        $this->environment = $environment;
        $this->varDirectory = $varDirectory;
        $this->abstractApiApiKey = $abstractApiApiKey;
    }

    public static function create(string $environment, string $projectRootDir, array $envVariables = []): self
    {
        if ($environment === 'input_adapter_test') {
            return new self(
                'input_adapter_test',
                sys_get_temp_dir(),
                'not needed'
            );
        } else if ($environment === 'output_adapter_test') {
            return self::createForOutputAdapterTesting($envVariables);
        } elseif ($environment === 'end_to_end_test') {
            return new self(
                'end_to_end_test',
                sys_get_temp_dir(),
                self::getEnv($envVariables, 'ABSTRACT_API_API_KEY')
            );
        }

        return new self(
            $environment,
            $projectRootDir . '/var',
            self::getEnv($envVariables, 'ABSTRACT_API_API_KEY')
        );
    }

    public static function createForOutputAdapterTesting(array $envVariables): self
    {
        return new self(
            'output_adapter_test',
            sys_get_temp_dir(),
            self::getEnv($envVariables, 'ABSTRACT_API_API_KEY')
        );
    }

    public static function createForUseCaseTesting(): self
    {
        return new self(
            'use_case_test',
            sys_get_temp_dir(),
            null
        );
    }

    private static function getEnv(array $envVariables, string $key): string
    {
        if (!isset($envVariables[$key])) {
            throw new RuntimeException(sprintf('Environment variable "%s" has to be defined', $key));
        }

        return (string)$envVariables[$key];
    }

    public function environment(): string
    {
        return $this->environment;
    }

    public function varDirectory(): string
    {
        Assert::that($this->varDirectory)->string('varDirectory has not been configured');
        Assert::that($this->varDirectory)->directory()->writeable();

        return $this->varDirectory;
    }

    public function abstractApiApiKey(): string
    {
        Assert::that($this->abstractApiApiKey)->string('AbstractApi API key has not been configured');

        return $this->abstractApiApiKey;
    }
}
