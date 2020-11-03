<?php
declare(strict_types=1);

include __DIR__ . '/header.php';

?>
    <form action="/registerUser" method="post">
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
