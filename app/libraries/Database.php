<?php
    /*
    * PDO Database class
    * Connect to database
    * Create prepared statements
    * Bind Values
    * Return rows and results
    */
    class Database {
        private $host = DB_HOST;
        private $user = DB_USER;
        private $pass = DB_PASS;
        private $dbname = DB_NAME;

        //Database handler
        private $dbh;

        private $stmt;
        private $error;

        public function __construct() {
            //Set dsn(data source name)
            $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
            $options = array(
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            );

            //Create PDO instance
            try {
                $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
            } catch(PDOException $e) {
                $this->error = $e->getMessage();
                echo $this->error;
            }
        }

        //Prepare statement with query
        public function query($sql) {
            $this->stmt = $this->dbh->prepare($sql);
        }

        //Bind values
        public function bind($param, $value, $type = null) {
            if(is_null($type)) {
                switch(true) {
                    case(is_int(true)):
                        $type = PDO::PARAM_INT;
                        break;
                    case(is_bool(true)):
                        $type = PDO::PARAM_INT;
                        break;
                    case(is_null(true)):
                        $type = PDO::PARAM_INT;
                        break;
                    default:
                        $type = PDO::PARAM_STRING;
                }      
            }
            $this->stmt = bindValue($param, $value, $type);
        }

        //Execute the prepared statement
        public function execute() {
            return $this->stmt->execute();
        }

        //Get results set as array of objects
        public function resultSet() {
            $this->execute();
            return $this->stmt->fetchAll(PDO::FETCH_OBJ);
        }

        //Get single record as object
        public function single() {
            $this->execute();
            return $this->stmt->fetch(PDO::FETCH_OBJ);
        }

        //Get row count
        public function rowCount() {
            return $this->stmt->rowCount();
        }
    }