<?php

namespace Sillicon\DatabaseConnection;

interface DatabaseConnectionInterface
{
    /**
     * Create a new database connection
     *
     * @return \PDO
     */
    public function open(): \PDO;

    /**
     * Close database connection
     *
     * @return void
     */
    public function close(): void;
}
