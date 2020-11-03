<?php
declare(strict_types=1);

/** @var \DevPro\Infrastructure\Session $session */

foreach ($session->getFlashes() as $type => $flashes) {
    foreach ($flashes as $message) {
        ?>
        <div class="alert alert-<?php echo escape($type); ?>"><?php echo escape($message); ?></div>
        <?php
    }
}
