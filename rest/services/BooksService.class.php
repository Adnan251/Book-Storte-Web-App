<?php
    require_once __DIR__.'/BaseService.class.php';
    require_once __DIR__.'/../dao/BooksDao.class.php';

    class BooksService extends BaseService 
    {
        public function __construct() 
        {
            parent::__construct(new BooksDao());
        }

        public function get_book_by_title($title)
        {
            return $this->dao->get_book_by_title($title);
        }

        public function get_book_by_author_id($authorId)
        {
            return $this->dao->get_book_by_author_id($authorId);
        }

        public function get_book_by_price_desc($upDown)
        {
            return $this->dao->get_book_by_price_desc($upDown);
        }
    }
