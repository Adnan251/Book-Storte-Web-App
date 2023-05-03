<?php
    require_once __DIR__.'/BaseService.class.php';
    require_once __DIR__.'/../dao/InventoryDao.class.php';

    class InventoryService extends BaseService 
    {
        public function __construct() 
        {
            parent::__construct(new InventoryDao());
        }

        public function get_amount_by_book_id($booksId)
        {
            return $this->dao->get_amount_by_book_id($booksId);
        }

        public function update_quantity($quantity, $bookId)
        {
            return $this->dao->update_quantity($quantity, $bookId);
        }
    }
