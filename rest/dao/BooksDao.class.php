<?php

    require_once __DIR__.'/BaseDao.class.php';

    class UserDao extends BaseDao {

        public function __construct(){
            parent::__construct("user");
        }

        public function get_user_by_title($title){
            return $this->query_unique("SELECT * FROM books WHERE Title = :title", ['title' => $title]);
        }

    }