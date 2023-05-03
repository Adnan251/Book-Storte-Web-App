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
            return $this->query_unique("SELECT Quantity FROM inventory WHERE BooksID =:BooksID", ['BooksID' => $BooksID]);
        }

        public function update_quantity($quantity, $bookId)
        {
            return $this->query_unique("UPDATE users SET Quantity =:quantity WHERE BooksID =:bookId", ['quantity' => $quantity,'bookId' => $bookId]);
        }
    }