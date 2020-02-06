<?php
/**
 * Class UpcomingEvents
 * @package DevPro\Application *
 * @copyright   2020 UniWeb bvba
 * @since       2020-02-06 15:38
 * @author      michael.rosmane
 */

namespace DevPro\Application;

interface UpcomingEventsRepository
{
    public function list(\DateTimeImmutable $now): array;
    public function add(EventForList $training): void;
}