<?php

    require_once __DIR__.'/BaseService.class.php';
    require_once __DIR__.'/../dao/BooksAndWritersDao.class.php';

    class BooksAndWritersService extends BaseService {
        public function __construct()
        {
            parent::__construct(BooksAndWritersDao::get_instance());
        }

        public function delete_book($bookid){
            return $this->dao->delete_book($bookid);
        }

        public function delete_writer($writerid){
            return $this->dao->delete_writer($writerid);
        }

        public function get_BaW($id)
        {
            return $this->dao->get_BaW($id);
        }
    }
