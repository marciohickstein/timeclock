<?php

class Sql
{
    private $conn;
    private $debug;
    private $errorSql;
    private $debugRows;

    /**
     * Load sqlconfig.php and get connection data.
     * After create a new instance of PDO and connect with database.
     *
     * @return void
     */
    function __construct()
    {
        // Load to catch connection data. Change this file with your database!
        require_once("sqlconfig.php");

        // Try to connect with database
        if (isset($debugdb))
            $this->debug = $debugRows = $debugdb;
        $dsn = "$driver:host=$hostdb;port=$portdb;dbname=$dbname";
        $this->showDebugMessage("Connecting with <b>$dsn</b>");
        $this->conn = new PDO($dsn, $userdb, $passdb);
    }

    public function showDebugMessage($message)
    {
        if ($this->debug)
            echo $message . "<br/>";
    }

    public function setDebugRows($debugRows)
    {
        $this->debugRows = $debugRows;

        return $this;
    }

    public function setDebug($enable)
    {
        $this->debug = $enable;
    }

    public function getDebug()
    {
        return $this->debug;
    }

    public function getErrorCode()
    {
        return isset($this->errorSql) ? (int)$this->errorSql[1] : 0;
    }

    public function getErrorMessage()
    {
        return isset($this->errorSql) ? $this->errorSql[2] : "";
    }

    private function setParam($stmt, $key, $value)
    {
        $this->showDebugMessage("|_Replace $key -> $value");
        $stmt->bindParam($key, $value);
    }

    private function setParams($stmt, $params = array())
    {
        foreach ($params as $key => $value) {
            $this->setParam($stmt, $key, $value);
        }
    }

    public function query($query, $params = array())
    {
        $this->showDebugMessage("Executing SQL: <b>$query</b>");

        $stmt = $this->conn->prepare($query);
        $this->setParams($stmt, $params);
        $ret = $stmt->execute();
        if ($ret == true) {
            $this->errorSql = null;
            $this->showDebugMessage("|_Result: SQL executed <b>successfully!</b>");
        } else {
            $this->errorSql = $stmt->errorInfo();
            $this->showDebugMessage("|_Result: SQL executed with error: <b>" . $this->getErrorMessage() . "</b>");
            $stmt = null;
        }

        return $stmt;
    }

    public function select($query, $params = array())
    {
        $results = array();
        $stmt = $this->query($query, $params);
        if ($stmt != null)
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($this->debug && $this->debugRows && count($results) > 0) {
            $columns = array_keys($results[0]);
            echo "<table>";
            echo "<tr>";
            foreach ($columns as $col) {
                echo "<th>" . strtoupper($col) . "</th>";
            }
            echo "</tr>";

            for ($i = 0; $i < count($results); $i++) {
                $row = $results[$i];
                echo "<tr>";
                foreach ($row as $col) {
                    echo "<td>$col</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        }

        return $results;
    }
}
?>