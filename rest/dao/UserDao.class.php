<?php
    require_once __DIR__.'/BaseDao.class.php';

    class UserDao extends BaseDao 
    {
        public function __construct()
        {
            parent::__construct("Users");
        }

        public function get_user_by_email($email)
        {
            return $this->query_unique("SELECT * FROM Users WHERE Email = :email", ['email' => $email]);
        }

        public function get_user_by_username($username)
        {
            return $this->query_unique("SELECT * FROM Users WHERE Username = :username", ['username' => $username]);
        }

        public function add_new_admin($adminId)
        {
            return $this->query_unique("SELECT * FROM Users WHERE Username = :username", ['username' => $username]);
        }
    }