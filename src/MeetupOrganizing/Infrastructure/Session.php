<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure;

final class Session
{
    /**
     * @var array<string,mixed>
     */
    private array $sessionData;

    public function __construct()
    {
        if (php_sapi_name() === 'cli') {
            $this->sessionData = [];
        } else {
            session_start();
            $this->sessionData = &$_SESSION;
        }
    }

    /**
     * @return mixed
     * @param mixed $defaultValue
     */
    public function get(string $key, $defaultValue = null)
    {
        if (isset($this->sessionData[$key])) {
            return $this->sessionData[$key];
        }

        return $defaultValue;
    }

    /**
     * @param mixed $value
     */
    public function set(string $key, $value): void
    {
        $this->sessionData[$key] = $value;
    }

    public function addErrorFlash(string $message): void
    {
        $this->addFlash('danger', $message);
    }

    public function addSuccessFlash(string $message): void
    {
        $this->addFlash('success', $message);
    }

    private function addFlash(string $type, string $message): void
    {
        $this->sessionData['flashes'][$type][] = $message;
    }

    /**
     * @return array<string,array<string>>
     */
    public function getFlashes(): array
    {
        $flashes = $this->sessionData['flashes'] ?? [];

        $this->sessionData['flashes'] = [];

        return $flashes;
    }

    public function clear(): void
    {
        // Preserve flashes
        $unsetKeys = array_diff(array_keys($this->sessionData), ['flashes']);
        foreach ($unsetKeys as $key) {
            unset($this->sessionData[$key]);
        }
    }
}
