<?php
    require_once __DIR__.'/BaseDao.class.php';

    class PublisherDao extends BaseDao{
        public function __construct()
        {
            parent::__construct("Publishers");   
        }

        public function get_by_publisher_name($name)
        {
            return $this->query_unique("SELECT *
            FROM Publishers
            WHERE name = :name",['name'=>$name]);
        }
    }
