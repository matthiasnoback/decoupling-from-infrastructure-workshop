<?php
declare(strict_types=1);

include __DIR__ . '/_header.php';

/** @var array<string,string> $formData */
/** @var array<string,string> $formErrors */

?>
    <h1>Schedule a training</h1>
    <form action="/scheduleTraining" method="post">
        <div class="form-group<?php if (isset($formErrors['title'])) { ?> has-error<?php } ?>">
            <label class="control-label" for="title">Title</label>
            <input class="form-control" name="title" id="title" type="text"
                   value="<?php echo escape($formData['title']); ?>">
            <?php if (isset($formErrors['title'])) { ?>
                <span class="help-block"><?php echo escape( $formErrors['title']); ?></span>
            <?php } ?>
        </div>
        <div class="form-group<?php if (isset($formErrors['scheduledDate'])) { ?> has-error<?php } ?>">
            <label class="control-label" for="scheduledDate">Scheduled date</label>
            <input class="form-control" name="scheduledDate" id="scheduledDate" type="text"
                   value="<?php echo escape($formData['scheduledDate']); ?>">
            <?php if (isset($formErrors['scheduledDate'])) { ?>
                <span class="help-block"><?php echo escape( $formErrors['scheduledDate']); ?></span>
            <?php } ?>
        </div>
        <div class="form-group<?php if (isset($formErrors['country'])) { ?> has-error<?php } ?>">
            <label class="control-label" for="country">Country</label>
            <input class="form-control" name="country" id="country" type="text"
                   value="<?php echo escape($formData['country']); ?>">
            <?php if (isset($formErrors['country'])) { ?>
                <span class="help-block"><?php echo escape( $formErrors['country']); ?></span>
            <?php } ?>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
<?php

include __DIR__ . '/_footer.php';
