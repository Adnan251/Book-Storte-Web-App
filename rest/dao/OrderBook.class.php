<?php

    require_once __DIR__.'/BaseDao.class.php';

    class OrderBookDao extends BaseDao 
    {

        public function __construct()
        {
            parent::__construct("OrdersBooks");
        }

        public function get_total_price_of_item($ID)
        {
            $quantity = $this->query_unique("SELECT Quantity FROM OrdersBooks WHERE ID =: ID", ["ID" => $ID]);
            $booksId = $this->query_unique("SELECT BooksID FROM OrdersBooks WHERE ID =: ID", ["ID" => $ID]);
            $price = $this->query_unique("SELECT Price FROM Books WHERE ID =: ID", ["ID" => $booksId]);
            return $quantity * $price;
        }

    }