<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure;

use Assert\Assert;
use RuntimeException;

final class ContainerConfiguration
{
    private string $environment;
    private string $varDirectory;
    private string $abstractApiApiKey;

    private function __construct(string $environment, string $varDirectory, string $abstractApiApiKey)
    {
        $this->environment = $environment;
        $this->varDirectory = $varDirectory;
        $this->abstractApiApiKey = $abstractApiApiKey;
    }

    public static function create(string $environment, string $projectRootDir, array $envVariables = []): self
    {
        $varDir = $projectRootDir . '/var';

        if (in_array(
            $environment,
            ['input_adapter_test', 'output_adapter_test', 'end_to_end_test', 'use_case_test'],
            true
        )) {
            $varDir = sys_get_temp_dir();
        }

        return new self($environment, $varDir, self::getEnv($envVariables, 'ABSTRACT_API_API_KEY'));
    }

    public static function createForOutputAdapterTesting(): self
    {
        return self::create('output_adapter_test', sys_get_temp_dir(), getenv());
    }

    public static function createForUseCaseTesting(): self
    {
        return self::create('use_case_test', sys_get_temp_dir(), getenv());
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
        Assert::that($this->varDirectory)->directory()->writeable();

        return $this->varDirectory;
    }

    public function abstractApiApiKey(): string
    {
        return $this->abstractApiApiKey;
    }
}
