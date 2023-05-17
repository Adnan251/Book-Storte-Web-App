<?php

    require_once __DIR__.'/BaseDao.class.php';

    class BooksAndWritersDao extends BaseDao {
        public function __construct()
        {
            parent::__construct("BooksAndWriters");
        }

        public function get_BaW($bookid)
        {
            return $this->queryUnique("SELECT * FROM BooksAndWriters WHERE bookid=:bookid",['bookid'=>$bookid]);
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
            $stm->bindParam(';writerid',$writerid);
            $stm->execute();
        }
    }
