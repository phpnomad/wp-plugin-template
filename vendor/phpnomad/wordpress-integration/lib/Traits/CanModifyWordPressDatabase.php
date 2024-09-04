<?php

namespace PHPNomad\Integrations\WordPress\Traits;

use PHPNomad\Datastore\Exceptions\DatastoreErrorException;

trait CanModifyWordPressDatabase
{
    /**
     * @param string $query
     * @param ...$args
     *
     * @return void
     * @throws DatastoreErrorException
     */
    protected function wpdbQuery(string $query, ...$args): void
    {
        global $wpdb;

        $wpdb->query($this->maybePrepare($query, ...$args));

        if ($wpdb->last_error) {
            throw new DatastoreErrorException('Query responded with error: ' . $wpdb->last_error);
        }
    }

    /**
     * @param string $query
     * @param ...$args
     * @return string|null
     */
    protected function wpdbGetVar(string $query, ...$args)
    {
        global $wpdb;

        return $wpdb->get_var($this->maybePrepare($query, ...$args));
    }

    private function maybePrepare(string $query, ...$args): string
    {
        global $wpdb;

        if (!empty($args)) {
            $query = $wpdb->prepare($query, $args);
        }

        return $query;
    }
}
