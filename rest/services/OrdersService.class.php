<?php

    require_once __DIR__.'/BaseService.class.php';
    require_once __DIR__.'/../dao/OrdersDao.class.php';
    require_once __DIR__.'/../dao/UsersDao.class.php';
    require_once __DIR__.'/../dao/BooksDao.class.php';

    class OrdersService extends BaseService {

        private $bookDao;
        private $userDao;

        public function __construct()
        {
            parent::__construct(new OrdersDao());
            $this->bookDao = new BooksDao();
            $this->userDao = new UsersDao();
        }

        public function get_orders_and_users()
        {
            return $this->dao->get_orders_and_users();
        }

        public function get_order_by_id($id)
        {
            return $this->dao->get_orders_and_users_by_id($id);
        }

        public function add_order_with_user($orderDescriptor)
        {
            $book = $this->bookDao->get_book_by_name($orderDescriptor['book_name']);
            $user = $this->userDao->get_user_by_name($orderDescriptor['User_Name'],$orderDescriptor['User_Last_Name']);

            if(!isset($book['id'])){
                return null;
            } else {
            // Calculates the amount of books ordered times the book price
            $calcAmount = $orderDescriptor['Order_Amount'] * $book['Book_price']; 

            // If date of delivery is left empty change its value to null
            if($orderDescriptor['Date_of_Delivery']==''){
                $orderDescriptor['Date_of_Delivery']=null;
            }
                $order = $this->dao->add(['Order_Amount'=>$orderDescriptor['Order_Amount'],
                'book_name'=>$orderDescriptor['book_name'],
                'Order_price'=>$calcAmount,
                'Date_of_Order'=>$orderDescriptor['Date_of_Order'],
                'Date_of_Delivery'=>$orderDescriptor['Date_of_Delivery'],
                'ordered_by'=>$user['id']]);

            $newInventory = $orderDescriptor['Order_Amount'] + $book['In_inventory'];
            
            $this->bookDAO->update(['In_inventory'=>$newInventory],$book['id']);
            
            return $order;
            }
        }

        public function update_order($orderDescriptor,$id)
        {
            $book = $this->bookDao->get_book_by_name($orderDescriptor['book_name']);
            $user = $this->userDao->get_user_by_name($orderDescriptor['User_Name'],$orderDescriptor['User_Last_Name']);
            $oldOrder = $this->dao->get_orders_and_users_by_id($id);

            if(!isset($book['id'])){
                return null;
            } else {
                $calcAmount = $orderDescriptor['Order_Amount'] * $book['Book_price'];

                // If date of delivery is left empty change its value to null
                if($orderDescriptor['Date_of_Delivery']==''){
                    $orderDescriptor['Date_of_Delivery']=null;
                }
    
                // If book entry matches the one in the Books table add the order
                    $order = $this->dao->update(['Order_Amount'=>$orderDescriptor['Order_Amount'],
                    'book_name'=>$orderDescriptor['book_name'],
                    'Order_price'=>$calcAmount,
                    'Date_of_Order'=>$orderDescriptor['Date_of_Order'],
                    'Date_of_Delivery'=>$orderDescriptor['Date_of_Delivery'],
                    'ordered_by'=>$user['id']],$id);
    
                $newInventory = $orderDescriptor['Order_Amount'] + ($book['In_inventory']-$oldOrder['Order_Amount']);
                
                $this->bookDao->update(['In_inventory'=>$newInventory],$book['id']);
                
                return $order;
            }
        }
    }