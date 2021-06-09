<?php
declare(strict_types=1);

use DevPro\Application\UpcomingTraining;

include __DIR__ . '/_header.php';

/** @var array<UpcomingTraining> $upcomingTrainings */

?>
<h1>Upcoming trainings</h1>
<ul>
<?php
foreach ($upcomingTrainings as $upcomingTraining) {
    ?>
    <li>
        <?php echo $upcomingTraining->title(); ?>
    </li>
    <?php
}
?>
</ul>

<?php

include __DIR__ . '/_footer.php';
