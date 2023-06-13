<?php

    require_once __DIR__.'/BaseDao.class.php';

    class OrdersDao extends BaseDao{
        private static $instance = null;

        private function __construct(){
            parent::__construct("orders");
        }

        public static function get_instance() {
            if (!isset(self::$instance)) {
            self::$instance = new self();
            }
            return self::$instance;
        }

        public function get_orders_and_users()
        {
            $stm = "SELECT o.id, o.book_name, o.Order_Amount, o.Order_price, o.Date_of_Order, o.Date_of_Delivery, u.User_Name, u.User_Last_Name 
                    FROM Orders o 
                    JOIN Users u ON u.id = o.ordered_by       
                    ORDER BY o.id ASC";
            $result = $this->conn->prepare($stm);
            $result->execute();
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }

        public function get_orders_and_users_by_id($id)
        {
            $stm = "SELECT o.id, o.book_name, o.Order_Amount, o.Order_price, o.Date_of_Order, o.Date_of_Delivery, u.User_Name, u.User_Last_Name
                    FROM Orders o
                    JOIN Users u ON u.id = o.ordered_by
                    WHERE o.id = :id";
            $result = $this->conn->prepare($stm);
            $result->execute(['id'=>$id]);
            return @reset($result->fetchAll(PDO::FETCH_ASSOC));
        }
}
