<?php
    require_once __DIR__.'/BaseService.class.php';
    require_once __DIR__.'/../dao/UserDao.class.php';

    class UserService extends BaseService 
    {
        public function __construct() 
        {
            parent::__construct(new UserDao());
        }

        public function get_user_by_email($email)
        {
            return $this->dao->get_user_by_email($email);
        }

        public function get_user_by_username($username)
        {
            return $this->dao->get_user_by_username($username);
        }

        public function update_to_admin($adminId)
        {
            return $this->dao->update_to_admin($adminId);
        }

        public function update_to_user($adminId)
        {
            return $this->dao->update_to_user($adminId);
        }
    }