<?php

    require_once __DIR__.'/BaseDao.class.php';

    class BooksAndWritersDao extends BaseDao {
        private static $instance = null;

        private function __construct(){
            parent::__construct("booksandwriters");
        }

        public static function get_instance() {
            if (!isset(self::$instance)) {
            self::$instance = new self();
            }
            return self::$instance;
        }

        public function get_BaW($bookid)
        {
            return $this->query_unique("SELECT * FROM BooksAndWriters WHERE bookid=:bookid",['bookid'=>$bookid]);
        }

        public function delete_book($bookid)
        {
            $stm = $this->conn->prepare("DELETE FROM BooksAndWriters WHERE bookid = :bookid");
            $stm->bindParam(':bookid',$bookid);
            $stm->execute();
        }

        public function delete_writer($writerid)
        {
            $stm = $this->conn->prepare("DELETE FROM BooksAndWriters WHERE writerid = :writerid");
            $stm->bindParam(':writerid',$writerid);
            $stm->execute();
        }
    }
