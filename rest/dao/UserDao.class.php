<?php
    require_once __DIR__.'/BaseDao.class.php';

    class UserDao extends BaseDao 
    {
        public function __construct()
        {
            parent::__construct("users");
        }

        public function get_user_by_email($email)
        {
            return $this->query_unique("SELECT * FROM users WHERE Email = :email", ['email' => $email]);
        }

        public function get_user_by_username($username)
        {
            return $this->query_unique("SELECT * FROM users WHERE Username = :username", ['username' => $username]);
        }

        public function update_to_admin($userId)
        {
            return $this->query_unique("UPDATE users SET IsAdmin = 1 WHERE ID =: userId", ['userId' => $userId]);
        }

        public function update_to_user($userId)
        {
            return $this->query_unique("UPDATE users SET IsAdmin = 0 WHERE ID =: userId", ['userId' => $userId]);
        }
    }