<?php
declare(strict_types=1);

use DevPro\Application\UpcomingTraining;

include __DIR__ . '/_header.php';

/** @var array<UpcomingTraining> $trainings */

?>
<h1>Upcoming trainings</h1>
<ul>
    <?php
    foreach ($trainings as $training) {
        ?>
        <li><?php echo escape($training->title()); ?></li>
        <?php
    }
    ?>
</ul>
<?php
include __DIR__ . '/_footer.php';
