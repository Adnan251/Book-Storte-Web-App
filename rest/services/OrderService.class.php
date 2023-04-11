<?php
    require_once __DIR__.'/BaseService.class.php';
    require_once __DIR__.'/../dao/OrderDao.class.php';

    class OrderService extends BaseService 
    {
        public function __construct() 
        {
            parent::__construct(new OrderDao());
        }

        public function get_order_by_userId($userId)
        {
            return $this->dao->get_order_by_userId($userId);
        }

        public function get_order_by_status($status)
        {
            return $this->dao->get_order_by_status($status);
        }
    }