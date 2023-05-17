<?php
    require_once __DIR__.'/BaseService.class.php';
    require_once __DIR__.'/../dao/PurchasesDao.class.php';

    class PurchasesService extends BaseService {

        private $bookDao;
        private $userDao;

        public function __construct()
        {
            parent::__construct(new PurchaseDao());
            $this->bookDao = new BooksDao();
            $this->userDao = new UsersDao();
        }

        public function get_purchase_and_book_and_user()
        {
            return $this->dao->get_purchase_and_book_and_user();
        }

        public function get_purchase_and_book_and_user_by_id($id)
        {
            return $this->dao->get_purchase_and_book_and_user_by_id($id);
        }

        public function add_purchase($purchaseDescriptor)
        {
            $book = $this->bookDao->get_by_id($purchaseDescriptor['BookID']);
            $user = $this->userDao->get_user_by_name($purchaseDescriptor['User_Name'],$purchaseDescriptor['User_Last_Name']);

            if($book['In_inventory'] < 0){
                return null;
            } else {
                date_default_timezone_set('Europe/Sarajevo');
                $purchaseDescriptor['Date_Of_Purchase'] = date("Y-m-d");
                $purchaseDescriptor['Time_Of_Purchase']= date("Y-m-d H:i:s");
                
                $purchase = $this->dao->add(["BookID"=>$book['id'],
                                            "Time_Of_Purchase"=>$purchaseDescriptor['Time_Of_Purchase'],
                                            "Date_Of_Purchase"=>$purchaseDescriptor['Date_Of_Purchase'],
                                            "Sold_By"=>$user['id']]);

                
                $newInventory = $book['In_inventory'] - 1;
                $this->bookDao->update(['In_inventory'=>$newInventory],$book['id']);
                return $purchase;                            
            }
        }
    }
