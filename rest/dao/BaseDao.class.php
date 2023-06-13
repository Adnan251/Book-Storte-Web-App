<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once __DIR__.'./../Config.class.php';

    class BaseDao {
        protected $conn;
        private static $pdo_instance = null;
        private $table_name;

        public function __construct($table_name){
            $this->table_name=$table_name;
            $this->conn = self::get_PDO();
            // set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        private static function get_PDO() {
            if (!isset(self::$pdo_instance)) {
            $servername = Config::DB_HOST();
            $username = Config::DB_USERNAME();
            $password = Config::DB_PASSWORD();
            $schema = Config::DB_SCHEME();
            $port = Config::DB_PORT();
            self::$pdo_instance = new PDO("mysql:host=$servername;port=$port;dbname=$schema", $username, $password);
            }
            return self::$pdo_instance;
        }
        
        /**
        * Get all records from database
        */
        public function get_all()
        {
            $stmt = $this->conn->prepare("SELECT * FROM ".$this->table_name);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        /**
        * Get single record by its ID
        */
        public function get_by_id($id){
            $stmt = $this->conn->prepare("SELECT * FROM " .$this->table_name." WHERE id =:id");
            $stmt->execute(["id" => $id]);
            $result = $stmt-> fetchAll(PDO::FETCH_ASSOC);
            return reset($result);
        }

        /**
        * Delete record from database
        */
        public function delete($id)
        {
            $stmt = $this->conn->prepare("DELETE FROM ".$this->table_name." WHERE id=:id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        }

        /**
        * Add record to database
        */
        public function add($entity)
        {
            $query = "INSERT INTO ".$this->table_name." (";
            foreach ($entity as $column => $value) {
                $query .= $column.", ";
            }
            $query = substr($query, 0, -2);
            $query .= ") VALUES (";
            foreach ($entity as $column => $value) {
                $query .= ":".$column.", ";
            }
            $query = substr($query, 0, -2);
            $query .= ")";
            
            $stmt= $this->conn->prepare($query);
            $stmt->execute($entity); // sql injection prevention
            $entity['id'] = $this->conn->lastInsertId();
            return $entity;
        }
        

        /**
        * Update record in database
        */
        public function update($id, $params, $id_column = "id")
        {
            $stmt = "UPDATE ".$this->table_name." SET ";
            foreach($params as $key=>$value){
                $stmt .= " " .$key ." = :". $key .", ";
            }
            $stmt = substr($stmt,0,-2);
            $stmt .= " WHERE id=$id";
            $result = $this->conn->prepare($stmt);
            return $result->execute($params);
        }

        protected function query($query, $params)
        {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        protected function query_unique($query, $params)
        {
            $results = $this->query($query, $params);
            return reset($results);
        }

        
        protected function query_specific($query)
        {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }


    }

