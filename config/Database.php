<?php
    require_once 'crids.php';
    class Database{
        //DB prams
        public $crid;
        private $db_name = 'orders_ingredients';
        private $conn;

        //DB connect
        public function connect(){
            $this->crid = new Crids();
            $this->conn = null;
            try{
                $this->conn = new PDO('mysql:host='.$this->crid->getDBHost().';dbname='.$this->db_name,$this->crid->getDBUsername(),$this->crid->getDBPassword(),array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'"));
                $this->conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                $this->conn->exec("SET CHARACTER SET utf8mb4");
            }catch(PDOException $e){
                echo 'Connection Error:' . $e->getMessage();
            }               
            return $this->conn;
        }
    }