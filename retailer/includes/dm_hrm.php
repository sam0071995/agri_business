<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of db
 *
 * @author FTA
 */
class dbHrm
{
    private $conn;
    private $host;
    private $user;
    private $password;
    private $baseName;
    private $port;
    private $Debug;

    function __construct($params = array())
    {
        $this->conn = false;
        $this->host = '192.168.7.10'; //hostname
        //        $this->host = '192.168.6.16'; //hostname
        //        $this->host = 'localhost'; //hostname
        $this->user = 'fta_hrm'; //username
        //        $this->user = 'root'; //username
        $this->password = 'Hsrp@84'; //password
        //        $this->password = ''; //password
        $this->baseName = 'fta_hrm'; //name of your database
        $this->port = '';
        $this->debug = true;
        $this->connect();
    }

    function __destruct()
    {
        $this->disconnect();
    }

    function connect()
    {
        if (!$this->conn) {
            try {
                $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->baseName . '', $this->user, $this->password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
            } catch (Exception $e) {
                die('Erreur : ' . $e->getMessage());
            }
            if (!$this->conn) {
                $this->status_fatal = true;
                echo 'Connection BDD failed';
                die();
            } else {
                $this->status_fatal = false;
            }
        }
        return $this->conn;
    }

    function disconnect()
    {
        if ($this->conn) {
            $this->conn = null;
        }
    }
    function getEmpDataByEmpCode($empcode)
    {
        $where = "reporting in ($empcode)";
        return $result = $this->mysql_selects($tbl_fields = '*', $table_name = 'hsrp_employee', $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    }

    //    ajit-----------------------------------------------------------------------------------------------------------------------------------
    function num_rows($tbl_fields = '*', $table_name = '', $where = '', $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '')
    {
        $reponse = array();
        $sql = "SELECT ";
        $sql .= $tbl_fields;
        $sql .= " FROM ";
        $sql .= $table_name;
        if ($where != '') {
            $sql .= " WHERE " . $where;
        }
        if ($order_by != '') {
            $sql .= " ORDER BY " . $order_by;
        }
        if ($group_by != '') {
            $sql .= " GROUP BY " . $group_by;
        }
        if ($asc == 1) {
            $sql .= " ASC";
        }
        if ($desc == 1) {
            $sql .= " DESC";
        }
        if ($limit != '') {
            $sql .= " limit " . $limit;
        }
        if ($table_name == 'lid_sr_no_store') {
            //            echo $sql;
            //            exit;
        }
        $result = $this->conn->prepare($sql);
        $ret = $result->execute();
        if ($ret) {
            $result->setFetchMode(PDO::FETCH_OBJ);
            $reponse = $result->fetchAll();
            return count($reponse);
        } else {
            return 0;
        }
    }
    function mysql_select($tbl_fields = '*', $table_name = '', $where = '', $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '')
    {
        $reponse = array();
        $sql = "SELECT ";
        $sql .= $tbl_fields;
        $sql .= " FROM ";
        $sql .= $table_name;
        if ($where != '') {
            $sql .= " WHERE " . $where;
        }
        if ($order_by != '') {
            $sql .= " ORDER BY " . $order_by;
        }
        if ($group_by != '') {
            $sql .= " GROUP BY " . $group_by;
        }
        if ($asc == 1) {
            $sql .= " ASC";
        }
        if ($desc == 1) {
            $sql .= " DESC";
        }
        if ($limit != '') {
            $sql .= " limit " . $limit;
        }
        if ($table_name == 'inventory_grn') {
            //            echo $sql;
            //            exit;
        }
        $result = $this->conn->prepare($sql);
        $ret = $result->execute();
        if ($ret) {
            $result->setFetchMode(PDO::FETCH_OBJ);
            $reponse = $result->fetch();
            return $reponse;
        } else {
            return false;
        }
    }

    function mysql_selects($tbl_fields = '*', $table_name = '', $where = '', $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '')
    {
        $sql = "SELECT ";
        $sql .= $tbl_fields;
        $sql .= " FROM ";
        $sql .= $table_name;
        if ($where != '') {
            $sql .= " WHERE " . $where;
        }
        if ($order_by != '') {
            $sql .= " ORDER BY " . $order_by;
        }
        if ($group_by != '') {
            $sql .= " GROUP BY " . $group_by;
        }
        if ($asc == 1) {
            $sql .= " ASC";
        }
        if ($desc == 1) {
            $sql .= " DESC";
        }
        if ($limit != '') {
            $sql .= " limit " . $limit;
        }
        //echo $sql;
        if ($table_name == 'hsrp_employee') {
            //   echo $sql;
            //  exit;
        }

        $result = $this->conn->prepare($sql);
        $ret = $result->execute();
        if (!$ret) {
            echo 'PDO::errorInfo():';
            echo '<br />';
            echo 'error SQL: ' . $sql;
            die();
        }
        $result->setFetchMode(PDO::FETCH_OBJ);
        $reponse = $result->fetchAll();

        return $reponse;
    }
    function updateSum($table_name = '', $data = '', $where = '')
    {
        $field_q = "";
        foreach ($data as $key => $value) {
            $field_q .= $key . "=" . $value . ",";
        }
        $field_q = rtrim($field_q, ',');
        if (isset($where)) {
            $where = " WHERE " . $where;
        }
        $query = "UPDATE $table_name SET $field_q $where";
        if ($table_name == 'inventory_master') {
            //            echo $query;
            //            exit;
        }
        $result = $this->conn->prepare($query);
        $ret = $result->execute();
        if (!$ret) {
            echo 'PDO::errorInfo():';
            echo '<br />';
            echo 'error SQL: ' . $sql;
            die();
        } else {
            return TRUE;
        }
    }
    function update($table_name = '', $data = '', $where = '')
    {
        $field_q = "";
        foreach ($data as $key => $value) {
            $field_q .= $key . "='" . $value . "',";
        }
        $field_q = rtrim($field_q, ',');
        if (isset($where)) {
            $where = " WHERE " . $where;
        }
        $query = "UPDATE $table_name SET $field_q $where";
        if ($table_name == 'purchase_order') {
            //    echo $query;
            //    exit;
        }
        $result = $this->conn->prepare($query);
        $ret = $result->execute();
        if (!$ret) {
            echo 'PDO::errorInfo():';
            echo '<br />';
            echo 'error SQL: ' . $query;
            die();
        } else {
            return TRUE;
        }
    }
    function insert($table_name = '', $data = '')
    {
        $field_q = "";
        $value_q = "'";
        foreach ($data as $key => $value) {
            $field_q .= $key . ", ";
            $value_q .= $value . "', '";
        }
        $field_q = rtrim($field_q, ', ');
        $value_q = rtrim($value_q, ", '");
        $value_q .= "'";
        $query = "INSERT INTO $table_name (" . $field_q . ") VALUES(" . $value_q . ")";
        if ($table_name == 'inventory_grn') {
            //            echo $query;
            //            exit;
        }
        $result = $this->conn->prepare($query);
        $ret = $result->execute();
        if (!$ret) {
            echo 'PDO::errorInfo():';
            echo '<br />';
            echo 'error SQL: ' . $sql;
            die();
        } else {
            return TRUE;
        }
    }

    function delete($table_name = '', $where = '')
    {
        if (isset($where)) {
            $where = " WHERE " . $where;
        }
        $query = "delete from $table_name $where";
        $result = $this->conn->prepare($query);
        $ret = $result->execute();
        if (!$ret) {
            echo 'PDO::errorInfo():';
            echo '<br />';
            echo 'error SQL: ' . $sql;
            die();
        } else {
            return TRUE;
        }
    }

    function get_tbl_column($table_name = '')
    {
        $row = array();
        $sql = "SHOW COLUMNS FROM " . $table_name . ";";

        $result = $this->conn->prepare($sql);
        $ret = $result->execute();
        if (!$ret) {
            echo 'PDO::errorInfo():';
            echo '<br />';
            echo 'error SQL: ' . $sql;
            die();
        }
        $result->setFetchMode(PDO::FETCH_OBJ);
        $reponse = $result->fetchAll();
        return $reponse->Field;
    }

    function execute($sql)
    {
        if (!$response = $this->conn->exec($sql)) {
            echo 'PDO::errorInfo():';
            echo '<br />';
            echo 'error SQL: ' . $sql;
            die();
        }
        return $response;
    }
}
