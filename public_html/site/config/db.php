<?php
class db extends error_logging
{
    protected $dbHost = "loading";
    protected $dbName = "loading";
    protected $dbUser = "loading";
    protected $dbPass = "loading";
    function __construct() {
        $this->dbHost = getenv('DB_HOST');
        $this->dbName = getenv('DB_DATABASE');
        $this->dbUser = getenv('DB_USERNAME');
        $this->dbPass = getenv('DB_PASSWORD');
    }
}
?>
