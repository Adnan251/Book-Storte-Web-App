<?php
    require_once __DIR__.'/BaseService.class.php';
    require_once __DIR__.'/../dao/OrderBookDao.class.php';

    class OrderBookService extends BaseService 
    {
        public function __construct() 
        {
            parent::__construct(new OrderBookDao());
        }

        public function get_total_price_of_item($itemId)
        {
            return $this->dao->get_total_price_of_item($itemId);
        }
    }
