<?php
declare(strict_types=1);

namespace DevPro\Infrastructure\Framework;

use Assert\Assert;

final class TemplateRenderer
{
    private array $globalVariables;

    /**
     * @param array<string,mixed> $globalVariables
     */
    public function __construct(array $globalVariables)
    {
        $this->globalVariables = $globalVariables;
    }

    /**
     * @param array<string,mixed> $templateVariables
     */
    public function render(string $templateFile, array $templateVariables = []): string
    {
        ob_start();

        $allVariables = array_merge($this->globalVariables, $templateVariables);

        if (isset($allVariables['formErrors'])
            && is_array($allVariables['formErrors'])
            && count($allVariables['formErrors']) > 0) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
        }

        extract($allVariables);

        include $templateFile;

        $result = ob_get_clean();
        Assert::that($result)->string();

        return $result;
    }
}
