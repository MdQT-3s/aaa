<?php

class MySql extends mysqli
{

    private $isConnected = false;

    public function __construct($config)
    {
        parent::__construct(
            $config["serverName"],
            $config["login"],
            $config["password"],
            $config["tableName"],
            $config["port"]
        );

        // $this->isConnected = true;
        $this->isConnected = !$this->connect_error;
    }

    public function QueryDB($request)
    {
        if ($this->isConnected == true) {
            $result = $this->query($request); 
            return $result ? $result->fetch_all(MYSQLI_ASSOC) : false;
        } else {
            return false;
        }
    }

    public function uniqCheck($table, $column, $value)
    {
        $sql = "SELECT $column  FROM  $table WHERE $column = \"$value\"";
        return boolval($this->QueryDB($sql));
    }
}
