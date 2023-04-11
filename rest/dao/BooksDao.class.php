<?php

    require_once __DIR__.'/BaseDao.class.php';

    class BooksDao extends BaseDao 
    {

        public function __construct()
        {
            parent::__construct("Books");
        }

        public function get_book_by_title($title)
        {
            $title=strtolower($title);
            return $this->query_unique("SELECT * FROM Books WHERE LOWER(Title) LIKE '%".$title."%'");
        }

        public function get_book_by_author_id($authorId)
        {
            return $this->query_unique("SELECT * FROM Books WHERE AuthorsID =: authorId", ["authorId" => $authorId]);
        }

        public function get_book_by_price_desc($upDown)
        {   
            if($upDown == 1){
                return $this->query_unique("SELECT * FROM Books ORDER BY Price DESC");
            }else{
                return $this->query_unique("SELECT * FROM Books ORDER BY Price ASC");
            }
        }

    }