<?php
declare(strict_types=1);

include __DIR__ . '/_header.php';

?>
    <h1>Login</h1>
    <form action="/login" method="post">
        <div class="form-group<?php if (isset($formErrors['username'])) { ?> has-error<?php } ?>">
            <label class="control-label" for="username">Username</label>
            <input class="form-control" name="username" id="username" type="text">
            <?php if (isset($formErrors['username'])) { ?><span class="help-block"><?php echo escape($formErrors['username']); ?></span><?php } ?>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
<?php

include __DIR__ . '/_footer.php';
