<?php
    require_once __DIR__.'/BaseService.class.php';
    require_once __DIR__.'/../dao/AuthorDao.class.php';

    class AuthorService extends BaseService 
    {
        public function __construct() 
        {
            parent::__construct(new AuthorDao());
        }

        public function get_author_by_fullname($text)
        {
            return $this->dao->get_author_by_fullname($text);
        }
    }