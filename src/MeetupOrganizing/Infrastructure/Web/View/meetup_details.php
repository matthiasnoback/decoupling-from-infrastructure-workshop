<?php
declare(strict_types=1);

use MeetupOrganizing\Application\Meetups\MeetupDetails;

/** @var MeetupDetails $meetupDetails */

include __DIR__ . '/_header.php';

?>
    <h1><?php echo escape($meetupDetails->title()); ?></h1>
    <p><?php echo escape($meetupDetails->description()); ?></p>
    <form method="post" action="/rsvpToMeetup">
        <input type="hidden" name="meetupId" value="<?php echo escape($meetupDetails->meetupId()); ?>">
        <button type="submit" class="btn btn-success">RSVP</button>
    </form>
<?php

include __DIR__ . '/_footer.php';
