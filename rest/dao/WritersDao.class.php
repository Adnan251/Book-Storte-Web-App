<?php

    require_once __DIR__.'/BaseDao.class.php';

    class WritersDao extends BaseDao {
        public function __construct()
        {
            parent::__construct("Writers");
        }

        public function get_writer_by_names($lastName, $firstName)
        {
          return $this->query_unique("SELECT *
                                     FROM Writers
                                     WHERE Writer_Name = :first_name AND Writer_Last_Name = :last_name", ['first_name' => $firstName, 'last_name' => $lastName]);
        }
}
