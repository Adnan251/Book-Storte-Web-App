<?php
    require_once __DIR__.'/BaseService.class.php';
    require_once __DIR__.'/../dao/UsersDao.class.php';

    class UsersService extends BaseService {

        public function __construct()
        {
            parent::__construct(new UsersDao());
        }

        public function get_user_by_email($email)
        {
            return $this->dao->get_user_by_email($email);
        }
    }

