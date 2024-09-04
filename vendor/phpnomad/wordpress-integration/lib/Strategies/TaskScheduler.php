<?php

namespace PHPNomad\Integrations\WordPress\Strategies;

use PHPNomad\Tasks\Interfaces\CanScheduleTasks;

class TaskScheduler implements CanScheduleTasks
{
    public function runEvery(string $interval, string $identifier, callable $callback): void
    {
        $identifier = 'siren_scheduled_event_' . $identifier;

        if (!wp_next_scheduled($identifier)) {
            wp_schedule_event(time(), $interval, $identifier);
        }

        add_action($identifier, $callback);
    }
}