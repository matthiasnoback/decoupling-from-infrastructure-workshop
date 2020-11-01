<?php
declare(strict_types=1);

include __DIR__ . '/header.php';

?>
    <form action="/registerUser" method="post">
        <div>
            <label for="name">Your name</label>
            <input name="name" id="name" type="text">
        </div>
        <div>
            <button type="submit">Submit</button>
        </div>
    </form>
<?php

include __DIR__ . '/footer.php';
