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
    <div class="panel panel-default meetup">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo escape($upcomingMeetup->title()); ?></h3>
        </div>
        <div class="panel-body">
            <p><?php echo escape($upcomingMeetup->dateAndTime()); ?></p>
            <p><a href="/meetupDetails?meetupId=<?php echo escape($upcomingMeetup->meetupId()); ?>" class="btn btn-success">Details</a></p>
        </div>
    </div>
<?php
}

include __DIR__ . '/_footer.php';
