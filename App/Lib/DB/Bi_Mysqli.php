<?php
/**
 * author:Bison
 * 2016.1.1
 */
namespace App\Lib\DB;
class Bi_Mysqli {
    private $user = "";
    private $host = "";
    private $pwd = "";
    private $db = "";
    private $port = "";
    private $con;
    private $char = "utf8";
    public function __construct($host, $username, $passwd, $db = "", $port = 3306, $char = "utf8") {
        $this->host = $host;
        $this->user = $username;
        $this->pwd = $passwd;
        $this->db = $db;
        $this->port = $port;
        $this->con = new \mysqli($this->host, $this->user, $this->pwd, $this->db, $this->port);
        $this->con->query("set names " . $char . "");
    } 
    public function fetch_one($sql) {
        $rs = $this->con->query($sql);
        $result = $rs->fetch_assoc();
        $this->sql_free($rs);
        return $result;
    } 

    public function fetch_one_cell($sql) {
        $rs = $this->con->query($sql);
        $finfo = $rs->fetch_field();
        $result = $rs->fetch_assoc();
        $return = $result[$finfo->name];
        $this->sql_free($rs);
        return $return;
    } 

    public function fetch_array($sql) {
        
        $rs = $this->con->query($sql);
        while ($result[] = $rs->fetch_assoc()) {
        } 
        $this->sql_free($rs);
        $this->array_remove($result, (count($result)-1));
        return $result;
    } 

    public function last_id() {
        return $this->con->insert_id;
    } 

    public function bi_query($sql) {
        $rs = $this->con->query($sql);
        if ($rs) {
            return true;
        } else {
            return false;
        } 
    } 
    public function sql_free($rs) {
        $rs->free_result();
    } 
    public function close() {
        $this->con->close();
    } 
    public function use_db($DBname) {
        $this->con->select_db($DBname);
    } 
    public function set_char($chars) {
        $this->con->query("set names '$chars'");
    } 

    public function array_remove(&$arr, $offset) {
        array_splice($arr, $offset, 1);
    } 
} 

?>