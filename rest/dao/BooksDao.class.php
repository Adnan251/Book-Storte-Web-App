<?php

require_once __DIR__.'/BaseDao.class.php';

class BooksDao extends BaseDao
{
    private static $instance = null;

    public function __construct()
    {
        parent::__construct("books");
    }

    public static function get_instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get_books_with_writer_names()
    {
        $stm='SELECT b.id, b.Book_Name,w.Writer_Name ,w.Writer_Last_Name, p.name, b.Year_of_publishing, b.Book_price, b.In_inventory, b.is_available
                    FROM books b
                    LEFT OUTER JOIN booksandwriters baw ON b.id = baw.bookid
                    LEFT OUTER JOIN writers w ON baw.writerid = w.id
                    JOIN publishers p ON p.id = b.Publisher
                    ORDER BY b.id';
        $result=$this->conn->prepare($stm);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_book_by_name($bookName)
    {
        $stm="SELECT b.id, b.Book_Name, b.Year_of_publishing, b.Book_price, b.In_inventory ";
        $stm.="FROM books b ";
        $stm.="WHERE Book_Name = :book_name";
        $result=$this->conn->prepare($stm);
        $result->execute(['book_name'=>$bookName]);
        return @reset($result->fetchAll(PDO::FETCH_ASSOC));
    }

    public function get_by_id_with_writer_names($id)
    {
        $stm='SELECT b.id, b.Book_Name, w.Writer_Name, w.Writer_Last_Name, p.name, b.Year_of_publishing, b.Book_price, b.In_inventory, b.is_available
                    FROM books b
                    LEFT OUTER JOIN booksandwriters baw ON b.id = baw.bookid
                    LEFT OUTER JOIN writers w ON baw.writerid = w.id
                    JOIN publishers p ON p.id = b.Publisher
                    WHERE b.id = :id';
        $result = $this->conn->prepare($stm);
        $result->execute(['id'=>$id]);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function search_book($name)
    {
        $name=strtolower($name);
        $stm='SELECT b.id, b.Book_Name, w.Writer_Name, w.Writer_Last_Name, p.name, b.Year_of_publishing, b.Book_price, b.In_inventory, b.is_available
                    FROM books b
                    LEFT OUTER JOIN booksandwriters baw ON b.id = baw.bookid
                    LEFT OUTER JOIN writers w ON baw.writerid = w.id
                    JOIN publishers p ON p.id = b.Publisher
                    WHERE LOWER(b.Book_Name) LIKE :name OR LOWER(p.name) LIKE :name OR LOWER(CONCAT(w.Writer_Name, " ", w.Writer_Last_Name)) LIKE :name';
        $result= $this->conn->prepare($stm);
        $result->execute(['name' => '%' . $name . '%']);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_book_by_writer($name, $last_name)
    {
        if($name!=null) {
            $name=strtolower($name);
        }
        if($last_name!=null) {
            $last_name=strtolower($last_name);
        }
        $stm='SELECT b.id, b.Book_Name, w.Writer_Name, w.Writer_Last_Name, p.name, b.Year_of_publishing, b.Book_price, b.In_inventory
                    FROM books b
                    LEFT OUTER JOIN booksandwriters baw ON b.id = baw.bookid
                    LEFT OUTER JOIN writers w ON baw.writerid = w.id
                    JOIN publishers p ON p.id = b.Publisher';

        if($name==null && $last_name!=null) {
            $stm.=" WHERE LOWER(w.Writer_Last_Name) LIKE '%".$last_name."%'";
        } elseif($last_name==null && $name!=null) {
            $stm.=" WHERE LOWER(w.Writer_Name) LIKE '%".$name."%'";
        } elseif($last_name!=null && $name!=null) {
            $stm.=" WHERE LOWER(w.Writer_Name) LIKE '%".$name."%' OR LOWER(w.Writer_Last_Name) LIKE '%".$last_name."%'";
        }

        $result=$this->conn->prepare($stm);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find_book($book)
    {
        $stm = "SELECT EXISTS(SELECT b.Book_Name, b.Year_of_publishing, b.Book_price, b.In_inventory  
                        FROM books b
                        LEFT OUTER JOIN booksandwriters baw ON b.id = baw.bookid
                        LEFT OUTER JOIN writers w ON baw.writerid = w.id 
                        JOIN publishers p ON p.id = b.Publisher
                        WHERE b.Book_Name = '".$book['Book_Name']."' AND
                            w.Writer_Name = '".$book['Writer_Name']."' AND
                            w.Writer_Last_Name = '".$book['Writer_Last_Name']."' AND
                            p.name = '".$book['name']."' AND
                            b.Year_of_publishing = '".$book['Year_of_publishing']."' AND
                            b.Book_price BETWEEN ".$book['Book_price']-0.01." AND ".$book['Book_price']." AND
                            b.In_inventory = ".$book['In_inventory']."
                        ) as found";
        $result = $this->conn->prepare($stm);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}
