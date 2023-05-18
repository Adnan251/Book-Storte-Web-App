<?php

    require_once __DIR__.'/BaseDao.class.php';

    class UsersDao extends BaseDao{
        
        public function __construct()
        {
            parent::__construct("Users");
        }

        public function get_user_by_email($email)
        {
            return $this->query_unique("Select * FROM Users WHERE User_email=:email",['email'=>$email]);
        }

        public function get_user_by_name($name, $lastname)
        {
            return $this->query_unique("SELECT * FROM Users WHERE User_Name =:name AND User_Last_Name=:lastname",['name'=>$name,'lastname'=>$lastname]);
        }
}
