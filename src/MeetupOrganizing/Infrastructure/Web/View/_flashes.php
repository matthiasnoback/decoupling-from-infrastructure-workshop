<?php
declare(strict_types=1);

/** @var \MeetupOrganizing\Infrastructure\Session $session */

foreach ($session->getFlashes() as $type => $flashes) {
    foreach ($flashes as $message) {
        ?>
        <div class="flash alert alert-<?php echo escape($type); ?>"><?php echo escape($message); ?></div>
        <?php
    }
}
