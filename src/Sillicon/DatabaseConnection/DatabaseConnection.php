<?php

namespace Sillicon\DatabaseConnection;

use PDO;
use PDOException;
use Sillicon\DatabaseConnection\Exceptions\DatabaseConnectionException;

class DatabaseConnection implements DatabaseConnectionInterface
{
    /**
     * Pdo instance
     * @var PDO
     */
    protected PDO $pdoInstance;

    /**
     * Database credentials
     * @var array
     */
    protected array $credentials;

    public function __construct( array $credentials )
    {
        $this->credentials = $credentials;
    }

    /**
     * @inheritDoc
     */
    public function open(): PDO
    {
        try {
            return $this->pdoInstance = new PDO(
                $this->credentials['dsn'],
                $this->credentials['username'],
                $this->credentials['password'],
                [
                    PDO::ATTR_EMULATE_PREPARES   => false,
                    PDO::ATTR_PERSISTENT         => true,
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        } catch ( PDOException $e ) {
            throw new DatabaseConnectionException( $e->getMessage(), (int) $e->getCode() );
        }
    }

    /**
     * @inheritDoc
     */
    public function close(): void
    {
        $this->pdoInstance = null;
    }
}
