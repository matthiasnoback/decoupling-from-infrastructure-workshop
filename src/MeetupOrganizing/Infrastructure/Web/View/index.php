<?php
declare(strict_types=1);

use MeetupOrganizing\Application\Meetups\UpcomingMeetup;

/** @var string $username */
/** @var array<UpcomingMeetup> $upcomingMeetups */

include __DIR__ . '/_header.php';

?>
<p>Hello, <?php echo escape($username); ?>!</p>

<h2>Upcoming meetups</h2>
<?php

if ($upcomingMeetups === []) {
    ?><p>There are no upcoming meetups</p><?php
}

foreach ($upcomingMeetups as $upcomingMeetup) {
    ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo escape($upcomingMeetup->title()); ?></h3>
        </div>
        <div class="panel-body">
            <?php echo escape($upcomingMeetup->dateAndTime()); ?>
        </div>
    </div>
<?php
}

include __DIR__ . '/_footer.php';
