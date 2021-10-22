<?php
declare(strict_types=1);

use MeetupOrganizing\Application\Meetups\MeetupDetails;

/** @var MeetupDetails $meetupDetails */

include __DIR__ . '/_header.php';

?>
    <h1><?php echo escape($meetupDetails->title()); ?></h1>
    <p class="meetup_description"><?php echo escape($meetupDetails->description()); ?></p>
    <h2>Attendees</h2>
    <ul class="meetup_attendees">
        <?php
        foreach ($meetupDetails->attendeeNames() as $attendeeName) {
            ?>
            <li class="meetup_attendee"><?php echo escape($attendeeName); ?></li>
            <?php
        }
        ?>
    </ul>
    <form method="post" action="/rsvpToMeetup">
        <input type="hidden" name="meetupId" value="<?php echo escape($meetupDetails->meetupId()); ?>">
        <button type="submit" class="btn btn-success">RSVP</button>
    </form>
<?php

include __DIR__ . '/_footer.php';
