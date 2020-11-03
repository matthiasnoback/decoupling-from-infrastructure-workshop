<?php
declare(strict_types=1);

namespace Test\EndToEnd;

use DevPro\Infrastructure\DevelopmentServiceContainer;

final class EndToEndTestServiceContainer extends DevelopmentServiceContainer
{
    public function __construct()
    {
        parent::__construct(sys_get_temp_dir(), 'end_to_end_test');
    }
}
