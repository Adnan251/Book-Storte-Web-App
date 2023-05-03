<?php

    require_once __DIR__.'/BaseDao.class.php';

    class BooksDao extends BaseDao 
    {

        public function __construct()
        {
            parent::__construct("books");
        }

        public function get_book_by_title($title)
        {
            $title=strtolower($title);
            return $this->query_unique("SELECT * FROM books WHERE LOWER(Title) LIKE '%".$title."%'");
        }

        public function get_book_by_author_id($authorId)
        {
            return $this->query_unique("SELECT * FROM books WHERE AuthorsID =: authorId", ["authorId" => $authorId]);
        }

        public function get_book_by_genre($genre)
        {   
            $genre = strtolower($genre);
            return $this->query_unique("SELECT * FROM books WHERE LOWER(Title) LIKE '%".$genre."%'");  
        }

    }