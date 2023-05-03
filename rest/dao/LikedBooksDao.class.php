<?php

    require_once __DIR__.'/BaseDao.class.php';

    class LikedBooksDao extends BaseDao 
    {
        public function __construct()
        {
            parent::__construct("likedbooks");
        }

        public function get_books_by_user($Users_ID)
        {
            return $this->query_unique("SELECT Books_ID FROM inventory WHERE Users_ID = :Users_ID", ['Users_ID' => $Users_ID]);
        }

        public function add($bookID, $userID)
        {
            return $this->query_unique("INSERT INTO inventory (Books_ID, Users_ID) VALUES (".$bookID.", ".$userID.")");
        } 
    }