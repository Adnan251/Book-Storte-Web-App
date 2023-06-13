<?php

    require_once __DIR__.'/BaseService.class.php';
    require_once __DIR__.'/../dao/PublisherDao.class.php';

    class PublishersService extends BaseService {
        
        public function __construct()
        {
            parent::__construct(PublisherDao::get_instance());
        }

        public function get_publisher_by_name($name){
            return $this->dao->get_publisher_by_name($name);
        }
    }
