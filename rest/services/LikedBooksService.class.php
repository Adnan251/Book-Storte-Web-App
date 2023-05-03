<?php
    require_once __DIR__.'/BaseService.class.php';
    require_once __DIR__.'/../dao/LikedBooksDao.class.php';

    class LikedBooksService extends BaseService 
    {
        public function __construct() 
        {
            parent::__construct(new InventoryDao());
        }

        public function get_books_by_user($userId)
        {
            return $this->dao->get_books_by_user($userId);
        }

    }
