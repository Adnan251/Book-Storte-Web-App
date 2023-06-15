<?php

require_once __DIR__.'/BaseDao.class.php';

class WritersDao extends BaseDao
{
    private static $instance = null;

    private function __construct()
    {
        parent::__construct("writers");
    }

    public static function get_instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get_writer_by_names($lastName, $firstName)
    {
        return $this->query_unique("SELECT *
                                     FROM writers
                                     WHERE Writer_Name = :first_name AND Writer_Last_Name = :last_name", ['first_name' => $firstName, 'last_name' => $lastName]);
    }
}
