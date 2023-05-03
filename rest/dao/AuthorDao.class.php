<?php
    require_once __DIR__.'/BaseDao.class.php';

    class AuthorDao extends BaseDao 
    {
        public function __construct()
        {
            parent::__construct("authors");
        }

        public function get_author_by_name($text)
        {
            $name = strtolower($text);
            return $this->query_unique("SELECT * FROM authors WHERE LOWER(Name) LIKE '%".$text."%'");
        }
    }