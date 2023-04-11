<?php

    require_once __DIR__.'/BaseDao.class.php';

    class OrderDao extends BaseDao 
    {

        public function __construct()
        {
            parent::__construct("Orders");
        }

        public function get_order_by_userId($user)
        {
            return $this->query_unique("SELECT * FROM Orders WHERE UsersID =: UsersID", ["UsersID" => $user]);
        }

        public function get_order_by_status($status)
        {
            return $this->query_unique("SELECT * FROM Orders WHERE Status = :Status", ["Status" => $status]);
        }
    }