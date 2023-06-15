<?php

require_once __DIR__.'/BaseService.class.php';
require_once __DIR__.'/../dao/WritersDao.class.php';

class WritersService extends BaseService
{
    public function __construct()
    {
        parent::__construct(WritersDao::get_instance());
    }

    public function get_writer_by_names($lastname, $firstname)
    {
        return $this->dao->get_writer_by_names($lastname, $firstname);
    }
}
