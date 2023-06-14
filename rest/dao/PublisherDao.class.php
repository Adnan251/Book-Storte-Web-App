<?php
    require_once __DIR__.'/BaseDao.class.php';

    class PublisherDao extends BaseDao{
        private static $instance = null;

        private function __construct(){
            parent::__construct("publishers");
        }

        public static function get_instance() {
            if (!isset(self::$instance)) {
            self::$instance = new self();
            }
            return self::$instance;
        }

        public function get_by_publisher_name($name)
        {
            return $this->query_unique("SELECT * FROM publishers WHERE name = :name",['name'=>$name]);
        }
    }
