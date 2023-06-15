<?php

require_once __DIR__.'/BaseDao.class.php';

class PurchaseDao extends BaseDao
{
    private static $instance = null;

    private function __construct()
    {
        parent::__construct("purchase");
    }

    public static function get_instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get_purchase_and_book_and_user_by_id($id)
    {
        $stmt ="SELECT p.id, b.Book_Name, b.Book_price,p.Time_of_Purchase, p.Date_of_Purchase, u.User_Name, u.User_Last_Name
                    FROM purchase p
                    JOIN books b ON b.id = p.BookID
                    JOIN users u ON p.Sold_By = u.id
                    WHERE p.id=:id";
        $result = $this->conn->prepare($stmt);
        $result->execute(['id'=>$id]);
        return @reset($result->fetchAll(PDO::FETCH_ASSOC));
    }

    public function get_purchase_and_book_and_user()
    {
        $stmt="SELECT p.id, b.Book_Name, b.Book_price,p.Time_of_Purchase, p.Date_of_Purchase, u.User_Name, u.User_Last_Name
                    FROM purchase p
                    JOIN books b ON b.id = p.BookID
                    JOIN users u ON p.Sold_By = u.id
                    ORDER BY p.id ASC";
        $result=$this->conn->prepare($stmt);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}
