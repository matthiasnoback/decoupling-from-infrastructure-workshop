<?php
declare(strict_types=1);

include __DIR__ . '/_header.php';

?>
    <h1>Register</h1>
    <form action="/registerUser" method="post">
        <div class="form-group">
            <label class="control-label" for="username">Username</label>
            <input class="form-control" name="username" id="username" type="text">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
<?php

include __DIR__ . '/_footer.php';
