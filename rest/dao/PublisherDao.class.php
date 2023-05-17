<?php
    require_once __DIR__.'/BaseDao.class.php';

    class PublisherDao extends BaseDao{
        public function __construct()
        {
            parent::__construct("Publishers");   
        }

        public function getByPublisherName($name)
        {
            return $this->queryUnique("SELECT *
            FROM Publishers
            WHERE name = :name",['name'=>$name]);
        }
    }
