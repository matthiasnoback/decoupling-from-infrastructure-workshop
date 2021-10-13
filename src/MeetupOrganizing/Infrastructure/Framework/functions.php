<?php
declare(strict_types=1);

/**
 * @param mixed $value
 */
function escape($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES);
}
