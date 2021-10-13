<?php
declare(strict_types=1);
/** @var string $username */

include __DIR__ . '/_header.php';

?>
<p>Hello, <?php echo escape($username); ?>!</p>
<?php

include __DIR__ . '/_footer.php';
