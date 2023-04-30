<?php

    require_once __DIR__.'/BaseDao.class.php';

    class InventoryDao extends BaseDao 
    {
        public function __construct()
        {
            parent::__construct("inventory");
        }

        public function get_amount_by_book_id($BooksID)
        {
            return $this->query_unique("SELECT Quantity FROM Inventory WHERE BooksID = :BooksID", ['BooksID' => $BooksID]);
        }
    }