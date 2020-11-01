<?php
declare(strict_types=1);

/** @var string|null $error */

include __DIR__ . '/header.php';

if ($error !== null) {
    ?><p><strong><?php echo $error; ?></strong></p><?php
}
?>
    <form action="/login" method="post">
        <div>
            <label for="username">Username</label>
            <input name="username" id="username" type="text">
        </div>
        <div>
            <button type="submit">Submit</button>
        </div>
    </form>
<?php

include __DIR__ . '/footer.php';
