<?php
    require_once __DIR__.'/BaseDao.class.php';

    class AuthorDao extends BaseDao 
    {
        public function __construct()
        {
            parent::__construct("Authors");
        }

        public function get_author_by_fullname($text)
        {
            $list = explode(" ", $text);
            $name = strtolower($list[0]);
            $surname = strtolower($list[1]);
            return $this->query_unique("SELECT * FROM Authors WHERE LOWER(Name) =: name ans LOWER(Surname) =: surname", ['name' => $name, 'surname' => $surname]);
        }
    }