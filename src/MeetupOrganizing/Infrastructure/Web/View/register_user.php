<?php
declare(strict_types=1);

include __DIR__ . '/_header.php';

/** @var array<string,string> $formData */
/** @var array<string,string> $formErrors */

?>
    <h1>Register</h1>
    <form action="/registerUser" method="post">
        <div class="form-group<?php if (isset($formErrors['username'])) { ?> has-error<?php } ?>">
            <label class="control-label" for="username">Username</label>
            <input class="form-control" name="username" id="username" type="text"
                   value="<?php echo escape($formData['username']); ?>">
            <?php if (isset($formErrors['username'])) { ?>
                <span class="help-block"><?php echo escape( $formErrors['username']); ?></span>
            <?php } ?>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="isOrganizer" id="isOrganizer"<?php if ($formData['isOrganizer']) { ?> checked<?php } ?>> Register as organizer
            </label>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
<?php

include __DIR__ . '/_footer.php';
