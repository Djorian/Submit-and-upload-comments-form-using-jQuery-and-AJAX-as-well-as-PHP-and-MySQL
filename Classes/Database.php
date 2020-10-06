<?php

class Database {

    // MySQL hostname
    private $hostname = 'localhost';

    // MySQL username
    private $username = 'root';

    // MySQL password
    private $password = '';

    // MySQL database
    private $database = 'db_1';

    // Database resource
    private $pdo = NULL;

    private $error_pdo;

    private $date;

    public function writeErrorConnectLog($log) {
        date_default_timezone_set('Europe/Moscow');
        $this->date = date('d/m/Y h:i:s a', time());
        $this->error_pdo = fopen('../log/error_connect_log.txt', 'a+');
        $log = mb_convert_encoding($log, "UTF-8");
        fwrite( $this->error_pdo, "$this->date\r\n\r\n$log\r\n\r\n\r\n\r\n");
        fclose($this->error_pdo);
    }

    // Функция для подключения к БД
    private function connect(){
        try {
            $this->pdo = new PDO("mysql:host=$this->hostname;dbname=$this->database", $this->username, $this->password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            return true;
        }
        catch(PDOException $error) {
            $this->writeErrorConnectLog(new \PDOException($error->getMessage()));
            return false;
        }
    }

    // Функция для отключения от БД
    private function disconnect(){
        $this->pdo = NULL;
    }

    // Функция для проверки существует ли таблица для использования с запросами
    private function tableExists($table){
        $sql = "SHOW TABLES FROM $this->database LIKE '$table'";

        $statement = $this->pdo->prepare($sql);

        if ($statement->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Функция для вставки в БД
    public function insert ($table, $params=array()){
        // Проверить соединение с БД
        if ($this->connect()){
            // Проверить, существует ли таблица
            if($this->tableExists($table)){
                $sql = 'INSERT INTO `'.$table.'` (`'.implode('`, `',array_keys($params)).'`) VALUES ("' . implode('", "', $params) . '")';

                $statement = $this->pdo->prepare($sql);

                if ($statement->execute($params)) {
                    return 1;
                } else {
                    return 0;
                }
            } else {
                return 0;
            }
            $this->disconnect();
        }
    }

    // Функция для выборки из БД
    public function select ($rows = '*', $table, $join = null, $where = null, $order = null, $limit = null) {
        // Проверить соединение с БД
        if ($this->connect()){
            // Проверить, существует ли таблица
            if($this->tableExists($table)){
                $sql = "SELECT $rows FROM $table";

                if($join != null){
                    $sql .= " JOIN $join";
                }
                if($where != null){
                    $sql .= " WHERE $where";
                }
                if($order != null){
                    $sql .=  " ORDER BY $order";
                }
                if($limit != null){
                    $sql .= " LIMIT $limit";
                }

                $statement = $this->pdo->prepare($sql);

                if ($statement->execute()) {
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                    return $result;
                } else {
                    return 0;
                }
            } else {
                return 0;
            }
            $this->disconnect();
        }
    }

    // Функция для обновления строки в БД
    public function update($table,$params=array(),$where){
        // Проверить соединение с БД
        if ($this->connect()){
            // Проверить, существует ли таблица
            if($this->tableExists($table)){
                // Создать массив для хранения всех столбцов для обновления
                $args = array();

                foreach($params as $field=>$value){
                    // Отделить каждый столбец соответствующим значением
                    $args[] = "$field='$value'";
                }

                $sql = 'UPDATE ' . $table . ' SET ' . implode(',', $args) . ' WHERE ' . $where;

                $statement = $this->pdo->prepare($sql);

                if ($statement->execute()) {
                    return 1;
                } else {
                    return 0;
                }
            } else {
                return 0;
            }
            $this->disconnect();
        }
    }

    // Функция для удаления таблицы или строки (строк) из БД
    public function delete($table,$where = null) {
        // Проверить соединение с БД
        if ($this->connect()){
            // Проверить, существует ли таблица
            if($this->tableExists($table)){
                // Таблица существует - проверить, удаляем ли мы строки или таблицу
                if($where == null){
                    // Создать запрос на удаление таблицы
                    $sql = "DROP TABLE $table";
                }else{
                    // Создать запрос на удаление строк
                    $sql = "DELETE FROM $table WHERE $where";
                }
                $statement = $this->pdo->prepare($sql);

                if ($statement->execute()) {
                    return 1;
                } else {
                    return 0;
                }
            } else {
                return 0;
            }
            $this->disconnect();
        }
    }
}

?>